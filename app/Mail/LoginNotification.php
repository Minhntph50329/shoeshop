<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $time;
    public $ip;
    public $userAgent;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $time, $ip, $userAgent)
    {
        $this->user = $user;
        $this->time = $time;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cảnh báo: Đăng nhập mới vào tài khoản Veloce Shoe Shop',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.login_notification',
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
