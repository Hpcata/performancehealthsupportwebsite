<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QueryGenerated extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $query;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $query)
    {
        $this->user = $user;
        $this->query = $query;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Query Generated',
            from: 'admin@gmail.com',
            to: $this->user->email
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.mail.query_generated',
            with: [
                'userName' => $this->user->first_name . ' ' . $this->user->last_name,
                'queryUser' => $this->query->name,
                'email' => $this->query->email,
                'mobile' => $this->query->mobile_number,
                'message' => $this->query->message,
            ],
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
