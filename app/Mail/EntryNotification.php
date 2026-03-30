<?php

namespace App\Mail;

use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntryNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Entry $entry,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Entry: ' . $this->entry->flyer_name . ' (' . $this->entry->number_of_birds . ' birds)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.entry-notification',
        );
    }
}
