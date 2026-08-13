<?php

namespace App\Mail;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RefundSuccessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refund;

    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hoàn tiền thành công cho đơn hàng #' . $this->refund->order->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.refund_successful',
        );
    }
}
