<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WordSuggestionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $word;
    public $meaning;
    public $sourceReference;
    public $name;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($word, $meaning, $sourceReference, $name = null, $email = null)
    {
        $this->word = $word;
        $this->meaning = $meaning;
        $this->sourceReference = $sourceReference;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'नवीन शब्द सुचना - ' . $this->word,
            from: $this->email ?? config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.word-suggestion',
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
