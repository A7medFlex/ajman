<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UnreleasedEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected string $type, protected string $title, protected string $username)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تم رفض طلبكم من قبل الإدارة ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.unreleased-email',
            with: [
                'username' => $this->username,
                'type' => $this->type,
                'title' => $this->title,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
