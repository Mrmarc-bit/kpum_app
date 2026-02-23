<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
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
    public $logoBase64 = null;
    protected $pdfContent;

    /**
     * Create a new message instance.
     */
    public function __construct(Mahasiswa $user, $pdfContent)
    {
        $this->user = $user;
        $this->pdfContent = $pdfContent;

        // Load logo as base64 so Gmail can display it inline (not blocked as external URL)
        $logoPath = Setting::get('app_logo');
        if ($logoPath) {
            $fullPath = public_path($logoPath);
            if (!file_exists($fullPath)) {
                $cleanPath = str_replace('storage/', '', $logoPath);
                $fullPath = storage_path('app/public/' . $cleanPath);
            }
            if (file_exists($fullPath)) {
                $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
                $mime = in_array($ext, ['png', 'webp', 'jpg', 'jpeg']) ? 'image/' . $ext : 'image/png';
                $this->logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
            }
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
