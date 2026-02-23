<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use App\Models\Mahasiswa;
use App\Models\Setting;

class ProofOfVoteMail extends Mailable
{
    use SerializesModels;

    public $user;
    public $logoUrl = null;
    protected $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Mahasiswa $user, $pdfContent)
    {
        $this->user = $user;
        $this->pdfContent = $pdfContent;

        // Build public URL for logo using asset() — same as landing page
        // Setting::get('app_logo') returns path like "storage/settings/logo.webp"
        $logoPath = Setting::get('app_logo');
        if ($logoPath) {
            $this->logoUrl = asset((string) $logoPath);
        }
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
