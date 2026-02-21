<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Mahasiswa;

class ProofOfVoteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    protected $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Mahasiswa $user, $pdfContent)
    {
        $this->user = $user;
        $this->pdfContent = $pdfContent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bukti Resmi Pemilihan KPUM',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.proof-of-vote',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => base64_decode($this->pdfContent), 'Bukti_Pilih_' . $this->user->nim . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
