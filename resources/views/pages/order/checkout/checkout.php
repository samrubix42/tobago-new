<?php

use App\Contracts\PaymentGatewayInterface;
use App\Models\Cart;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusLog;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

new class extends Component
{
    public string $paymentMethod = 'online';

    public ?int $selectedAddressId = null;
    public bool $useNewAddress = false;
    public bool $editSelectedAddress = false;
    public bool $saveAddressForLater = true;

    public string $fullName = '';
    public string $phone = '';
    public string $email = '';
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $landmark = '';
    public string $addressType = 'home';
    public string $city = '';
    public string $state = '';
    public string $country = 'India';
    public string $pincode = '';
    public string $customerNote = '';

    public bool $isPincodeLoading = false;
    public ?string $pincodeHint = null;

    public bool $showSuccess = false;
    public ?string $placedOrderNumber = null;
    public bool $showConfirmationSlide = false;
    public bool $showFailure = false;
    public ?string $failedOrderNumber = null;
    public ?string $failedPaymentMessage = null;

    public function mount(): void
    {
        $this->dispatchFlashToast();

        if (Auth::check()) {
            merge_guest_cart_for_user((int) Auth::id(), session()->getId());

            /** @var User|null $user */
            $user = Auth::user();
            $defaultAddress = $user?->addresses()->where('is_default', true)->first() ?? $user?->addresses()->first();

            if ($defaultAddress) {
                $this->selectedAddressId = $defaultAddress->id;
                $this->fillFromAddress($defaultAddress);
                $this->useNewAddress = false;
                $this->editSelectedAddress = false;
                return;
            }

            $this->fullName = (string) ($user?->name ?? '');
            $this->email = (string) ($user?->email ?? '');
            $this->useNewAddress = true;
            return;
        }

        $this->useNewAddress = true;
        $this->saveAddressForLater = false;
    }

    public function openNewAddressForm(): void
    {
        $this->useNewAddress = true;
        $this->editSelectedAddress = false;
        $this->selectedAddressId = null;

        /** @var User|null $user */
        $user = Auth::user();
        $this->fullName = (string) ($user?->name ?? '');
        $this->email = (string) ($user?->email ?? '');
        $this->phone = '';
        $this->addressLine1 = '';
        $this->addressLine2 = '';
        $this->landmark = '';
        $this->addressType = 'home';
        $this->city = '';
        $this->state = '';
        $this->country = 'India';
        $this->pincode = '';
        $this->pincodeHint = null;
    }

    public function startEditSelectedAddress(): void
    {
        if (! Auth::check()) {
            return;
        }

        if (! $this->selectedAddressId) {
            return;
        }

        /** @var User $user */
        $user = Auth::user();
        $address = $user->addresses()->whereKey($this->selectedAddressId)->first();

        if (! $address) {
            return;
        }

        $this->fillFromAddress($address);
        $this->useNewAddress = false;
        $this->editSelectedAddress = true;
    }

    public function selectAddress(int $addressId): void
    {
        if (! Auth::check()) {
            return;
        }

        /** @var User $user */
        $user = Auth::user();
        $address = $user->addresses()->whereKey($addressId)->first();

        if (! $address) {
            return;
        }

        $this->selectedAddressId = $address->id;
        $this->fillFromAddress($address);
        $this->useNewAddress = false;
        $this->editSelectedAddress = false;
    }

    public function editAddress(int $addressId): void
    {
        $this->selectAddress($addressId);
        $this->editSelectedAddress = true;
    }

    public function cancelAddressForm(): void
    {
        if (! Auth::check()) {
            $this->useNewAddress = true;
            $this->editSelectedAddress = false;
            return;
        }

        /** @var User $user */
        $user = Auth::user();
        $defaultAddress = $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        if ($defaultAddress) {
            $this->selectedAddressId = $defaultAddress->id;
            $this->fillFromAddress($defaultAddress);
            $this->useNewAddress = false;
            $this->editSelectedAddress = false;
            return;
        }

        $this->useNewAddress = true;
        $this->editSelectedAddress = false;
    }

    public function updatedSelectedAddressId($value): void
    {
        if (! Auth::check()) {
            return;
        }

        if (! $value) {
            return;
        }

        /** @var User $user */
        $user = Auth::user();
        $address = $user->addresses()->whereKey($value)->first();

        if ($address) {
            $this->fillFromAddress($address);
            $this->useNewAddress = false;
            $this->editSelectedAddress = false;
        }
    }

    public function updatedPincode(string $value): void
    {
        $pin = preg_replace('/\D+/', '', $value);
        $this->pincode = $pin;

        if (strlen($pin) !== 6) {
            $this->pincodeHint = null;
            return;
        }

        $this->lookupPincode($pin);
    }

    public function saveAddressChanges(): void
    {
        if (! $this->selectedAddressId || $this->useNewAddress) {
            return;
        }

        $this->validateAddressInput();

        /** @var User $user */
        $user = Auth::user();
        $address = $user->addresses()->whereKey($this->selectedAddressId)->first();

        if (! $address) {
            return;
        }

        $address->update($this->addressPayload());

        $this->editSelectedAddress = false;
        $this->useNewAddress = false;

        $this->dispatch('toast-show', [
            'message' => 'Address updated successfully.',
            'type' => 'success',
            'position' => 'top-right',
        ]);
    }

    public function placeOrder(): void
    {
        $this->paymentMethod = 'online';
        $this->showFailure = false;
        $this->failedOrderNumber = null;
        $this->failedPaymentMessage = null;

        Log::info('Checkout placeOrder hit', [
            'payment_method' => $this->paymentMethod,
            'user_id' => Auth::id(),
            'session_id' => session()->getId(),
        ]);

        $cart = $this->resolveCart();
        if (! $cart || ! $cart->items()->exists()) {
            Log::warning('Checkout placeOrder blocked: empty cart', [
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
            ]);
            $this->dispatch('toast-show', [
                'message' => 'Your cart is empty.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return;
        }

        $this->validateAddressInput();

        try {
            $order = $this->createOrderFromCart($cart, clearCart: false);
            Log::info('Checkout order created', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_method' => $this->paymentMethod,
                'total' => (float) $order->total,
            ]);
        } catch (\RuntimeException $e) {
            Log::warning('Checkout order creation failed', [
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
                'error' => $e->getMessage(),
            ]);
            $this->dispatch('toast-show', [
                'message' => $e->getMessage(),
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return;
        }

        /** @var PaymentGatewayInterface $paymentGateway */
        $paymentGateway = app(PaymentGatewayInterface::class);
        Log::info('Checkout initiating PhonePe payment', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);
        $paymentResponse = $paymentGateway->initiatePayment($order);
        Log::info('Checkout PhonePe initiate response', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'success' => (bool) ($paymentResponse['success'] ?? false),
            'status' => (string) ($paymentResponse['status'] ?? ''),
            'gateway_order_id' => (string) ($paymentResponse['gateway_order_id'] ?? ''),
            'redirect_url_present' => ! empty($paymentResponse['redirect_url']),
            'message' => (string) ($paymentResponse['message'] ?? ''),
        ]);

        $order->update([
            'payment_gateway' => 'phonepe',
            'payment_gateway_order_id' => (string) ($paymentResponse['gateway_order_id'] ?? $order->payment_gateway_order_id),
            'payment_state' => (string) ($paymentResponse['status'] ?? $order->payment_state),
            'payment_response_payload' => is_array($paymentResponse['payload'] ?? null) ? $paymentResponse['payload'] : null,
        ]);

        if (! ($paymentResponse['success'] ?? false) || empty($paymentResponse['redirect_url'])) {
            Log::warning('Checkout PhonePe initiate failed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => (string) ($paymentResponse['status'] ?? ''),
                'message' => (string) ($paymentResponse['message'] ?? ''),
            ]);
            $order->update([
                'payment_status' => 'failed',
                'payment_failure_reason' => (string) ($paymentResponse['message'] ?? 'Unable to initiate PhonePe payment.'),
            ]);

            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'status' => $order->status,
                'note' => 'PhonePe payment initiation failed: ' . (string) ($paymentResponse['message'] ?? 'Unknown error'),
                'source' => 'system',
                'logged_at' => now(),
            ]);

            $this->failedOrderNumber = $order->order_number;
            $this->failedPaymentMessage = (string) ($paymentResponse['message'] ?? 'Unable to initiate PhonePe payment. Please try again.');
            $this->showFailure = true;
            $this->showConfirmationSlide = false;

            $this->dispatch('toast-show', [
                'message' => $this->failedPaymentMessage,
                'type' => 'error',
                'position' => 'top-right',
            ]);
            return;
        }

        $order->update([
            'payment_failure_reason' => null,
        ]);

        Log::info('Checkout redirecting to PhonePe', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
        ]);

        $this->showConfirmationSlide = false;
        $this->redirect((string) $paymentResponse['redirect_url'], navigate: false);
    }

    protected function createOrderFromCart(Cart $cart, bool $clearCart): Order
    {
        return DB::transaction(function () use ($cart, $clearCart) {
            $cart->load(['items.product.images', 'items.product.category', 'coupon']);

            $requiredStockByProductId = $cart->items
                ->groupBy('product_id')
                ->map(fn ($items) => (int) $items->sum('quantity'));

            $shippingAmount = $this->calculateShipping((float) $cart->total);
            $finalTotal = (float) $cart->total + $shippingAmount;

            $order = Order::query()->create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => Auth::check() ? (int) Auth::id() : null,
                'session_id' => session()->getId(),
                'coupon_id' => $cart->coupon_id,
                'coupon_code' => $cart->coupon?->code,
                'coupon_type' => $cart->coupon?->type,
                'coupon_value' => (float) ($cart->coupon?->value ?? 0),
                'subtotal' => (float) $cart->subtotal,
                'discount' => (float) $cart->discount,
                'shipping_amount' => $shippingAmount,
                'total' => $finalTotal,
                'payment_method' => $this->paymentMethod,
                'payment_status' => 'pending',
                'status' => 'confirmed',
                'delivery_type' => 'in_hand_delivery',
                'customer_name' => $this->fullName,
                'customer_phone' => $this->phone,
                'customer_email' => $this->email ?: null,
                'address_line1' => $this->addressLine1,
                'address_line2' => $this->addressLine2 ?: null,
                'landmark' => $this->landmark ?: null,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'pincode' => $this->pincode,
                'customer_note' => $this->customerNote ?: null,
                'placed_at' => now(),
            ]);

            OrderStatusLog::query()->create([
                'order_id' => $order->id,
                'status' => 'confirmed',
                'note' => 'Order placed successfully. Order confirmed.',
                'source' => 'customer',
                'logged_at' => now(),
            ]);

            foreach ($requiredStockByProductId as $productId => $requiredQty) {
                /** @var Product|null $product */
                $product = Product::query()->lockForUpdate()->find($productId);

                if (! $product || $product->is_out_of_stock || (int) $product->stock < (int) $requiredQty) {
                    $name = $product?->name ?? 'One or more products';
                    throw new \RuntimeException($name . ' does not have enough stock.');
                }

                $product->stock = max(0, (int) $product->stock - (int) $requiredQty);
                $product->save();

                InventoryLog::query()->create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => (int) $requiredQty,
                    'reference_type' => 'order',
                    'reference_id' => $order->id,
                    'note' => 'Stock deducted when order was placed.',
                ]);
            }

            foreach ($cart->items as $item) {
                $snapshotImage = $item->product?->images?->firstWhere('is_primary', true)?->image
                    ?? $item->product?->images?->first()?->image;

                OrderItem::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name ?? 'Product',
                    'sku' => $item->product?->sku,
                    'product_image' => $snapshotImage,
                    'product_category' => $item->product?->category?->title,
                    'quantity' => (int) $item->quantity,
                    'price' => (float) $item->price,
                    'total' => (float) $item->total,
                ]);
            }

            if (Auth::check() && $this->useNewAddress && $this->saveAddressForLater) {
                UserAddress::query()->create(array_merge($this->addressPayload(), [
                    'user_id' => (int) Auth::id(),
                    'is_default' => false,
                ]));
            }

            if ($clearCart) {
                $cart->items()->delete();
                $cart->update([
                    'coupon_id' => null,
                    'subtotal' => 0,
                    'discount' => 0,
                    'total' => 0,
                ]);
            }

            return $order;
        });
    }

    public function openOrderConfirmation(): void
    {
        $this->showFailure = false;
        $this->failedOrderNumber = null;
        $this->failedPaymentMessage = null;

        $cart = $this->resolveCart();
        if (! $cart || ! $cart->items()->exists()) {
            $this->dispatch('toast-show', [
                'message' => 'Your cart is empty.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);
            return;
        }

        $this->validateAddressInput();
        $this->showConfirmationSlide = true;
    }

    public function closeOrderConfirmation(): void
    {
        $this->showConfirmationSlide = false;
    }

    protected function resolveCart(): ?Cart
    {
        $query = Cart::query();

        if (Auth::check()) {
            $query->where('user_id', (int) Auth::id());
        } else {
            $query->whereNull('user_id')->where('session_id', session()->getId());
        }

        return $query->latest('id')->first();
    }

    protected function calculateShipping(float $cartTotal): float
    {
        $deliveryFee = (float) app_setting('delivery_fee', 0);
        $freeDeliveryAmount = (float) app_setting('free_delivery_amount', 0);

        if ($freeDeliveryAmount > 0 && $cartTotal >= $freeDeliveryAmount) {
            return 0;
        }

        return max($deliveryFee, 0);
    }

    protected function validateAddressInput(): void
    {
        $this->validate([
            'paymentMethod' => ['required', 'in:online'],
            'fullName' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'email' => ['nullable', 'email', 'max:255'],
            'addressLine1' => ['required', 'string', 'min:5', 'max:255'],
            'addressLine2' => ['nullable', 'string', 'max:255'],
            'landmark' => ['nullable', 'string', 'max:255'],
            'addressType' => ['required', 'in:home,work,other'],
            'city' => ['required', 'string', 'min:2', 'max:100'],
            'state' => ['required', 'string', 'min:2', 'max:100'],
            'country' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'regex:/^[1-9][0-9]{5}$/'],
            'customerNote' => ['nullable', 'string', 'max:500'],
        ], [
            'phone.regex' => 'Phone number must be 10 digits.',
            'pincode.regex' => 'Pincode must be a valid 6-digit Indian pincode.',
        ]);
    }

    protected function addressPayload(): array
    {
        return [
            'full_name' => $this->fullName,
            'phone' => $this->phone,
            'address_line1' => $this->addressLine1,
            'address_line2' => $this->addressLine2 ?: null,
            'landmark' => $this->landmark ?: null,
            'type' => $this->addressType,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
            'pincode' => $this->pincode,
        ];
    }

    protected function fillFromAddress(UserAddress $address): void
    {
        $this->fullName = (string) $address->full_name;
        $this->phone = (string) $address->phone;
        $this->addressLine1 = (string) $address->address_line1;
        $this->addressLine2 = (string) ($address->address_line2 ?? '');
        $this->landmark = (string) ($address->landmark ?? '');
        $this->addressType = (string) ($address->type ?? 'home');
        $this->city = (string) $address->city;
        $this->state = (string) $address->state;
        $this->country = (string) $address->country;
        $this->pincode = (string) $address->pincode;
        $this->email = (string) (Auth::user()?->email ?? $this->email);
        $this->pincodeHint = null;
    }

    protected function lookupPincode(string $pin): void
    {
        try {
            $this->isPincodeLoading = true;
            $response = Http::timeout(8)->get('https://api.postalpincode.in/pincode/' . $pin);

            if (! $response->ok()) {
                $this->pincodeHint = 'Unable to validate pincode right now.';
                return;
            }

            $payload = $response->json();
            $first = is_array($payload) ? ($payload[0] ?? null) : null;
            $postOffice = $first['PostOffice'][0] ?? null;

            if (! $postOffice) {
                $this->pincodeHint = 'No location found for this pincode.';
                return;
            }

            $this->state = (string) ($postOffice['State'] ?? $this->state);

            if ($this->city === '') {
                $this->city = (string) ($postOffice['District'] ?? ($postOffice['Name'] ?? $this->city));
            }

            $this->country = (string) ($postOffice['Country'] ?? 'India');
            $this->pincodeHint = 'State and city auto-filled from pincode.';
        } catch (\Throwable $e) {
            $this->pincodeHint = 'Pincode lookup failed. Please fill state and city manually.';
        } finally {
            $this->isPincodeLoading = false;
        }
    }

    protected function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }

    protected function dispatchFlashToast(): void
    {
        if (session()->has('success')) {
            $this->dispatch('toast-show', [
                'message' => (string) session('success'),
                'type' => 'success',
                'position' => 'top-right',
            ]);
            session()->forget('success');
        }

        if (session()->has('error')) {
            $this->dispatch('toast-show', [
                'message' => (string) session('error'),
                'type' => 'error',
                'position' => 'top-right',
            ]);
            session()->forget('error');
        }
    }

    public function render()
    {
        $cart = $this->resolveCart();

        /** @var User|null $user */
        $user = Auth::user();
        $addresses = $user ? $user->addresses()->latest()->get() : collect();

        $shippingAmount = $cart ? $this->calculateShipping((float) $cart->total) : 0;
        $grandTotal = ($cart?->total ?? 0) + $shippingAmount;

        return view('pages.order.checkout.checkout', [
            'cart' => $cart,
            'items' => $cart?->items()->with('product.images')->get() ?? collect(),
            'addresses' => $addresses,
            'shippingAmount' => $shippingAmount,
            'grandTotal' => $grandTotal,
        ]);
    }
};
