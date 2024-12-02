<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class EmailCheckinReminder extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

public function __construct(User $user)
{
    $this->user = $user;
}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Email nhắc nhở check in ',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'fe_email.checkin_reminder',
            with: [
                'user' => $this->user,
                
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
