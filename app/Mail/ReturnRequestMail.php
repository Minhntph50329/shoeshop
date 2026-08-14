<?php

namespace App\Mail;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReturnRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refund;

    public function __construct(Refund $refund)
    {
        // Eager load all relations needed by the email template
        $refund->loadMissing([
            'order',
            'user',
            'items.product.images',
            'items.productVariant.images',
        ]);
        $this->refund = $refund;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận yêu cầu trả hàng – Đơn hàng #' . $this->refund->order->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.return_request',
        );
    }
}
