<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyRegistration extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verifyUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $verifyUrl)
    {
        $this->user = $user;
        $this->verifyUrl = $verifyUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đăng ký tài khoản Veloce Shoe Shop',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verify_registration',
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
