<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        // Eager load all relations needed by the email template
        $order->loadMissing([
            'items.product.images',
            'items.productVariant.images',
            'payment',
        ]);
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Đặt hàng thành công – Đơn hàng #' . $this->order->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_success',
        );
    }
}
