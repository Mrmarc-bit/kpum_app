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

        // Load logo as base64 (resized to 80x80) so Gmail shows it inline without clipping
        $logoPath = Setting::get('app_logo');
        if ($logoPath) {
            $fullPath = public_path($logoPath);
            if (!file_exists($fullPath)) {
                $cleanPath = str_replace('storage/', '', $logoPath);
                $fullPath = storage_path('app/public/' . $cleanPath);
            }
            if (file_exists($fullPath) && extension_loaded('gd')) {
                try {
                    // Resize to 80x80 PNG to keep email size small (< 10KB for logo)
                    $src = imagecreatefromstring(file_get_contents($fullPath));
                    if ($src !== false) {
                        $thumb = imagecreatetruecolor(80, 80);
                        // Preserve transparency
                        imagealphablending($thumb, false);
                        imagesavealpha($thumb, true);
                        $transparent = imagecolorallocatealpha($thumb, 0, 0, 0, 127);
                        imagefilledrectangle($thumb, 0, 0, 80, 80, $transparent);
                        imagecopyresampled($thumb, $src, 0, 0, 0, 0, 80, 80, imagesx($src), imagesy($src));
                        ob_start();
                        imagepng($thumb, null, 6); // compression 6 for small size
                        $pngData = ob_get_clean();
                        imagedestroy($src);
                        imagedestroy($thumb);
                        $this->logoBase64 = 'data:image/png;base64,' . base64_encode($pngData);
                    }
                } catch (\Throwable $e) {
                    // Fallback: embed original (may be large but better than nothing)
                    $ext = pathinfo($fullPath, PATHINFO_EXTENSION);
                    $mime = in_array($ext, ['png', 'webp', 'jpg', 'jpeg']) ? 'image/' . $ext : 'image/png';
                    $this->logoBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($fullPath));
                }
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
