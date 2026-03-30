<?php

namespace App\Mail;

use App\Models\Entry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EntryConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Entry $entry,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('olr.site_name') . ' - Entry Confirmation (' . $this->entry->reference . ')',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.entry-confirmation',
        );
    }
}
