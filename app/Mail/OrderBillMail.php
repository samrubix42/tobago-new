<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderBillMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Order $order)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invoice for Order #' . $this->order->order_number . ' - Tobac-Go Hookah Store',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Eager load order items to prevent query issues
        $this->order->loadMissing('items');

        return new Content(
            view: 'emails.order-bill',
            with: [
                'order' => $this->order,
                'items' => $this->order->items,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
