<?php

use App\Models\InventoryLog;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::admin')] class extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public ?int $productId = null;
    public ?int $logsProductId = null;

    public string $productName = '';
    public string $productSku = '';
    public int $currentStock = 0;

    public string $type = 'in'; // in|out|sale|return|adjust|reserve|release|replace
    public int $quantity = 0; // for adjust, can be negative
    public ?string $reference_type = null;
    public $reference_id = null;
    public ?string $note = null;

    public int $logsPerPage = 10;

    public function updatedSearch(): void
    {
        $this->resetPage('productsPage');
    }

    public function updatedPerPage(): void
    {
        $this->resetPage('productsPage');
    }

    public function resetAdjustForm(): void
    {
        $this->resetValidation();

        $this->productId = null;
        $this->productName = '';
        $this->productSku = '';
        $this->currentStock = 0;
        $this->type = 'in';
        $this->quantity = 0;
        $this->reference_type = null;
        $this->reference_id = null;
        $this->note = null;
    }

    public function openAdjustModal(int $productId): void
    {
        $product = Product::query()->select(['id', 'name', 'sku', 'stock'])->findOrFail($productId);

        $this->resetValidation();
        $this->productId = $product->id;
        $this->productName = $product->name;
        $this->productSku = (string) ($product->sku ?? '');
        $this->currentStock = (int) $product->stock;
        $this->type = 'in';
        $this->quantity = 0;
        $this->reference_type = null;
        $this->reference_id = null;
        $this->note = null;
    }

    public function updateStock(): void
    {
        $this->validate([
            'productId' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'in:in,out,sale,return,adjust,reserve,release,replace'],
            'quantity' => ['required', 'integer'],
            'reference_type' => ['nullable', 'string', 'max:50'],
            'reference_id' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $type = $this->type;
        $qty = (int) $this->quantity;

        if ($type === 'adjust') {
            if ($qty === 0) {
                $this->addError('quantity', 'Quantity cannot be 0 for adjust.');
                return;
            }
        } else {
            if ($qty <= 0) {
                $this->addError('quantity', 'Quantity must be greater than 0.');
                return;
            }
        }

        $qtyAbs = abs($qty);
        $decreaseTypes = ['out', 'sale', 'reserve'];
        $delta = $type === 'adjust'
            ? $qty
            : (in_array($type, $decreaseTypes, true) ? -$qtyAbs : $qtyAbs);

        $result = DB::transaction(function () use ($type, $delta, $qty, $qtyAbs) {
            /** @var Product $product */
            $product = Product::query()->lockForUpdate()->findOrFail($this->productId);

            $newStock = (int) $product->stock + (int) $delta;
            if ($newStock < 0) {
                return [
                    'ok' => false,
                    'stock' => (int) $product->stock,
                ];
            }

            $product->stock = $newStock;

            $product->stock = $newStock;
            
            // Logic: track stock_in for positive changes, stock_out for negative changes
            if ($delta > 0) {
                $product->stock_in = (int) $product->stock_in + (int) $delta;
            } elseif ($delta < 0) {
                $product->stock_out = (int) $product->stock_out + (int) abs($delta);
            }

            $product->save();

            $logNote = trim((string) $this->note);
            $referenceType = trim((string) ($this->reference_type ?? ''));
            $referenceType = $referenceType !== '' ? $referenceType : null;

            InventoryLog::create([
                'product_id' => $product->id,
                'type' => $type,
                'quantity' => $type === 'adjust' ? $qty : $qtyAbs,
                'reference_type' => $referenceType,
                'reference_id' => $this->reference_id,
                'note' => $logNote !== '' ? $logNote : null,
            ]);

            return [
                'ok' => true,
                'stock' => (int) $product->stock,
            ];
        });

        $this->currentStock = (int) ($result['stock'] ?? $this->currentStock);

        if (! ($result['ok'] ?? false)) {
            $this->dispatch('toast-show', [
                'message' => 'Not enough stock to decrease.',
                'type' => 'warning',
                'position' => 'top-right',
            ]);

            return;
        }

        $this->dispatch('toast-show', [
            'message' => 'Inventory updated successfully.',
            'type' => 'success',
            'position' => 'top-right',
        ]);

        $this->dispatch('close-inventory-adjust-modal');
        $this->resetAdjustForm();
        $this->resetPage('productsPage');
    }

    public function openLogsModal(int $productId): void
    {
        $product = Product::query()->select(['id', 'name', 'sku', 'stock'])->findOrFail($productId);
        $this->logsProductId = $product->id;
        $this->productName = $product->name;
        $this->productSku = (string) ($product->sku ?? '');
        $this->currentStock = (int) $product->stock;
        $this->resetPage('logsPage');
    }

    public function closeLogsModal(): void
    {
        $this->logsProductId = null;
        $this->resetPage('logsPage');
    }

    public function render()
    {
        $products = Product::query()
            ->select(['id', 'name', 'slug', 'sku', 'stock', 'is_out_of_stock', 'status', 'category_id'])
            ->with(['category:id,title'])
            ->when($this->search !== '', function (Builder $query) {
                $query->where(function (Builder $nested) {
                    $nested->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('slug', 'like', '%' . $this->search . '%')
                        ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage, ['*'], 'productsPage');

        $logs = null;
        $logsStartBalance = null;
        if ($this->logsProductId) {
            $logsQuery = InventoryLog::query()
                ->where('product_id', $this->logsProductId)
                ->orderByDesc('id');

            $logs = $logsQuery->paginate($this->logsPerPage, ['*'], 'logsPage');

            $offset = max(0, ((int) $logs->currentPage() - 1) * (int) $logs->perPage());
            if ($offset === 0) {
                $logsStartBalance = (int) $this->currentStock;
            } else {
                $newerDeltaSum = (clone $logsQuery)
                    ->limit($offset)
                    ->selectRaw("COALESCE(SUM(CASE
                        WHEN type IN ('in','return','release','replace') THEN ABS(quantity)
                        WHEN type IN ('out','sale','reserve') THEN -ABS(quantity)
                        WHEN type = 'adjust' THEN quantity
                        ELSE 0
                    END), 0) as delta_sum")
                    ->value('delta_sum');

                $logsStartBalance = (int) $this->currentStock - (int) $newerDeltaSum;
            }
        }

        return view('admin.inventory.inventory', [
            'products' => $products,
            'logs' => $logs,
            'logsStartBalance' => $logsStartBalance,
        ]);
    }
};
