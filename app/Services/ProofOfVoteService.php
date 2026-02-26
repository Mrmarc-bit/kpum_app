<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ProofOfVoteService
{
    /**
     * Generate the Proof of Vote PDF for a specific user.
     *
     * @param \App\Models\Mahasiswa $user
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generatePdf(Mahasiswa $user)
    {
        // Check if Faculty is empty and try to derive it from Prodi
        $faculty = $user->fakultas;
        if (empty($faculty) && !empty($user->prodi)) {
            foreach (Mahasiswa::PRODI_LIST as $prodiKey => $fakultasValue) {
                if (stripos($prodiKey, $user->prodi) !== false) {
                    $faculty = $fakultasValue;
                    break;
                }
            }
        }

        // Gather Vote Data
        $voteData = [
            'name' => $user->name, 
            'nim' => $user->nim,
            'faculty' => $faculty ?? '-', 
            'study_program' => $user->prodi,
            'presma_voted' => $user->voted_at ? true : false,
            'presma_date' => $user->voted_at,
            'dpm_voted' => $user->dpm_voted_at ? true : false,
            'dpm_date' => $user->dpm_voted_at,
            'timestamp' => now()->format('d F Y H:i:s'),
        ];
        
        // Helper to get base64 image
        $getBase64Image = $this->getImageLoader();

        $letterSettings = [
            'header' => Setting::get('letter_header', 'KOMISI PEMILIHAN UMUM MAHASISWA'),
            'sub_header' => Setting::get('letter_sub_header', 'UNIVERSITAS CONTOSO'),
            'body_top' => Setting::get('letter_body_top', 'Menerangkan bahwa mahasiswa dengan identitas di bawah ini:'),
            'body_bottom' => Setting::get('letter_body_bottom', 'Telah menggunakan hak suaranya pada Pemilihan Umum Raya Mahasiswa tahun ini. Surat ini adalah dokumen sah dan dapat digunakan sebagai bukti partisipasi.'),
            'footer' => Setting::get('letter_footer', 'Dokumen ini dibuat secara otomatis oleh sistem e-voting.'),
            
            'signature_base64' => $getBase64Image(Setting::get('letter_signature_path')),
            'logo_base64' => $getBase64Image(Setting::get('app_logo')),
            
            'signature_name' => Setting::get('letter_signature_name', 'Ma\'rufatul Khouro'),
            'signature_place_date' => Setting::get('letter_signature_place_date', 'Cilacap, ..... Februari 2026'),
            'signature_title' => Setting::get('letter_signature_title', 'Ketua KPUM'),
            'signature_nim' => Setting::get('letter_signature_nim', '22AF13003'),
            'stamp_base64' => $getBase64Image(Setting::get('letter_stamp_path')),
            'watermark_text' => Setting::get('letter_watermark_text', 'OFFICIAL COPY'),
        ];

        $pdf = Pdf::loadView('pdf.proof-of-vote', compact('voteData', 'letterSettings'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    public function generateSampleProof()
    {
        // Dummy User Data
        $voteData = [
            'name' => 'Mahasiswa Contoh', 
            'nim' => '12345678',
            'faculty' => 'Fakultas Teknik', 
            'study_program' => 'Informatika',
            'presma_voted' => true,
            'presma_date' => now(),
            'dpm_voted' => false,
            'dpm_date' => null,
            'timestamp' => now()->format('d F Y H:i:s'),
        ];
        
        $getBase64Image = $this->getImageLoader();

        $letterSettings = [
            'header' => Setting::get('letter_header', 'KOMISI PEMILIHAN UMUM MAHASISWA'),
            'sub_header' => Setting::get('letter_sub_header', 'UNIVERSITAS CONTOSO'),
            'body_top' => Setting::get('letter_body_top', 'Menerangkan bahwa mahasiswa dengan identitas di bawah ini:'),
            'body_bottom' => Setting::get('letter_body_bottom', 'Telah menggunakan hak suaranya pada Pemilihan Umum Raya Mahasiswa tahun ini. Surat ini adalah dokumen sah dan dapat digunakan sebagai bukti partisipasi.'),
            'footer' => Setting::get('letter_footer', 'Dokumen ini dibuat secara otomatis oleh sistem e-voting.'),
            
            'signature_base64' => $getBase64Image(Setting::get('letter_signature_path')),
            'logo_base64' => $getBase64Image(Setting::get('app_logo')),
            
            'signature_name' => Setting::get('letter_signature_name', 'Ma\'rufatul Khouro'),
            'signature_place_date' => Setting::get('letter_signature_place_date', 'Cilacap, ..... Februari 2026'),
            'signature_title' => Setting::get('letter_signature_title', 'Ketua KPUM'),
            'signature_nim' => Setting::get('letter_signature_nim', '22AF13003'),
            'stamp_base64' => $getBase64Image(Setting::get('letter_stamp_path')),
            'watermark_text' => Setting::get('letter_watermark_text', 'OFFICIAL COPY'),
        ];

        $pdf = Pdf::loadView('pdf.proof-of-vote', compact('voteData', 'letterSettings'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf;
    }

    /**
     * Generate Notification Letter for a specific student.
     * 
     * @param \App\Models\Mahasiswa $mahasiswa
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateNotificationLetter(Mahasiswa $mahasiswa, array $preloadedSettings = [])
    {
        $qrService = new \App\Services\QrCodeService();
        
        // Ensure Access Code exists
        if (empty($mahasiswa->access_code)) {
            $mahasiswa->access_code = $qrService->generateAccessCode();
            $mahasiswa->save();
        }

        $encryptedQrData = $qrService->generateSecureCode($mahasiswa);
        
        // Generate QR Code Image using BaconQrCode directly
        $qrImage = null;
        try {
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrSvg = $writer->writeString($encryptedQrData);
            
            // Convert SVG to base64 for embedding in PDF
            $qrImage = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
        } catch (\Exception $e) {
            // Fallback: QR code will not be displayed
            Log::warning('QR Code generation failed: ' . $e->getMessage());
        }

        // Determine Faculty
        $faculty = $mahasiswa->fakultas;
        if (empty($faculty) && !empty($mahasiswa->prodi)) {
            foreach (Mahasiswa::PRODI_LIST as $prodiKey => $fakultasValue) {
                if (stripos($prodiKey, $mahasiswa->prodi) !== false) {
                    $faculty = $fakultasValue;
                    break;
                }
            }
        }

        // Helper to get setting or default
        $getSetting = function($key, $default) use ($preloadedSettings) {
            return $preloadedSettings[$key] ?? Setting::get($key, $default);
        };

        // Images are already base64 in preloadedSettings if passed from controller
        $signatureBase64 = $preloadedSettings['signature_base64'] ?? ($this->getImageLoader())(Setting::get('letter_signature_path'));
        $logoBase64 = $preloadedSettings['logo_base64'] ?? ($this->getImageLoader())(Setting::get('app_logo'));

        $data = [
            'header' => $getSetting('notification_header', 'KOMISI PEMILIHAN UMUM MAHASISWA'),
            'sub_header' => $getSetting('notification_sub_header', 'UNIVERSITAS CONTOSO'),
            'dpt_code' => $mahasiswa->access_code,
            'title' => $getSetting('notification_title', 'PEMBERITAHUAN PEMUNGUTAN SUARA'),
            'body_top' => $getSetting('notification_body_top', 'Kami beritahukan kepada seluruh mahasiswa bahwa Pemilihan Umum Raya Mahasiswa akan dilaksanakan pada:'),
            'name' => $mahasiswa->name,
            'nim' => $mahasiswa->nim,
            'faculty' => $faculty ?? '-',
            'study_program' => $mahasiswa->prodi,
            'date' => $getSetting('notification_date', 'Senin, 20 Oktober 2024'),
            'time' => $getSetting('notification_time', '08:00 - 16:00 WIB'),
            'location' => $getSetting('notification_location', 'E-Voting Portal (kpum.univ.ac.id)'),
            'body_bottom' => $getSetting('notification_body_bottom', 'Demikian pemberitahuan ini kami sampaikan. Gunakan hak pilih Anda dengan bijak.'),
            
            'signature_place_date' => $getSetting('notification_signature_place_date', 'Cilacap, ..... Februari 2026'),
            'signature_title' => $getSetting('notification_signature_title', 'Ketua KPUM'),
            'signature_name' => $getSetting('notification_signature_name', 'Ma\'rufatul Khouro'),
            'signature_nim' => $getSetting('notification_signature_nim', '22AF13003'),
            
            'signature_base64' => $signatureBase64,
            'stamp_base64' => $preloadedSettings['stamp_base64'] ?? ($this->getImageLoader())(Setting::get('letter_stamp_path')),
            'logo_base64' => $logoBase64,

            'qr_image' => $qrImage // Pass the QR Code image
        ];

        $pdf = Pdf::loadView('pdf.notification-letter', compact('data'));
        // Custom size: 21cm x 14.85cm (Half A4 / A5 Landscape)
        $pdf->setPaper([0, 0, 595.28, 420.95], 'portrait');

        return $pdf;
    }

    public function generateNotificationSample()
    {
        $getBase64Image = $this->getImageLoader();
        
        // Sample Encrypted QR
        $qrService = new \App\Services\QrCodeService();
        $dummyUser = new Mahasiswa(['id' => 999, 'nim' => '12345678', 'name' => 'Sample', 'fakultas' => 'Teknik']);
        $encryptedQrData = $qrService->generateSecureCode($dummyUser);
        
        $qrImage = null;
        try {
            $renderer = new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(300),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            );
            $writer = new \BaconQrCode\Writer($renderer);
            $qrSvg = $writer->writeString($encryptedQrData);
            
            // Convert SVG to base64 for embedding in PDF
            $qrImage = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
        } catch (\Exception $e) {
            Log::warning('Proof of Vote: QR Code generation failed: ' . $e->getMessage());
        }
        
        $data = [
            'header' => Setting::get('notification_header', 'KOMISI PEMILIHAN UMUM MAHASISWA'),
            'sub_header' => Setting::get('notification_sub_header', 'UNIVERSITAS CONTOSO'),
            'dpt_code' => 'DPT-SAMPLE-001',
            'title' => Setting::get('notification_title', 'PEMBERITAHUAN PEMUNGUTAN SUARA'),
            'body_top' => Setting::get('notification_body_top', 'Kami beritahukan kepada seluruh mahasiswa bahwa Pemilihan Umum Raya Mahasiswa akan dilaksanakan pada:'),
            'name' => 'Mahasiswa Contoh',
            'nim' => '12345678',
            'faculty' => 'Fakultas Teknik',
            'study_program' => 'Informatika',
            'date' => Setting::get('notification_date', 'Senin, 20 Oktober 2024'),
            'time' => Setting::get('notification_time', '08:00 - 16:00 WIB'),
            'location' => Setting::get('notification_location', 'E-Voting Portal (kpum.univ.ac.id)'),
            'body_bottom' => Setting::get('notification_body_bottom', 'Demikian pemberitahuan ini kami sampaikan. Gunakan hak pilih Anda dengan bijak.'),
            
            'signature_place_date' => Setting::get('notification_signature_place_date', 'Cilacap, ..... Februari 2026'),
            'signature_title' => Setting::get('notification_signature_title', 'Ketua KPUM'),
            'signature_name' => Setting::get('notification_signature_name', 'Ma\'rufatul Khouro'),
            'signature_nim' => Setting::get('notification_signature_nim', '22AF13003'),
            
            'signature_base64' => $getBase64Image(Setting::get('letter_signature_path')), 
            'stamp_base64' => $getBase64Image(Setting::get('letter_stamp_path')),
            'logo_base64' => $getBase64Image(Setting::get('app_logo')),
            'qr_code' => $qrImage
        ];

        $pdf = Pdf::loadView('pdf.notification-letter', compact('data'));
        // Custom size: 21cm x 14.85cm (Half A4 / A5 Landscape)
        // 1 cm = 28.3465 points
        // 21 cm = 595.28 points, 14.85 cm = 420.95 points
        $pdf->setPaper([0, 0, 595.28, 420.95], 'portrait');

        return $pdf;
    }

    /**
     * Preload all settings and images for batch processing.
     * @return array
     */
    public function getBatchSettings()
    {
        $getBase64Image = $this->getImageLoader();
        
        return [
            'notification_header' => Setting::get('notification_header', 'KOMISI PEMILIHAN UMUM MAHASISWA'),
            'notification_sub_header' => Setting::get('notification_sub_header', 'UNIVERSITAS CONTOSO'),
            'notification_title' => Setting::get('notification_title', 'PEMBERITAHUAN PEMUNGUTAN SUARA'),
            'notification_body_top' => Setting::get('notification_body_top', 'Kami beritahukan kepada seluruh mahasiswa bahwa Pemilihan Umum Raya Mahasiswa akan dilaksanakan pada:'),
            'notification_date' => Setting::get('notification_date', 'Senin, 20 Oktober 2024'),
            'notification_time' => Setting::get('notification_time', '08:00 - 16:00 WIB'),
            'notification_location' => Setting::get('notification_location', 'E-Voting Portal (kpum.univ.ac.id)'),
            'notification_body_bottom' => Setting::get('notification_body_bottom', 'Demikian pemberitahuan ini kami sampaikan. Gunakan hak pilih Anda dengan bijak.'),
            
            'notification_signature_place_date' => Setting::get('notification_signature_place_date', 'Cilacap, ..... Februari 2026'),
            'notification_signature_title' => Setting::get('notification_signature_title', 'Ketua KPUM'),
            'notification_signature_name' => Setting::get('notification_signature_name', 'Ma\'rufatul Khouro'),
            'notification_signature_nim' => Setting::get('notification_signature_nim', '22AF13003'),
            
            'signature_base64' => $getBase64Image(Setting::get('letter_signature_path')),
            'stamp_base64' => $getBase64Image(Setting::get('letter_stamp_path')),
            'logo_base64' => $getBase64Image(Setting::get('app_logo')),
        ];
    }

    private function getImageLoader()
    {
        return function ($path) {
            if (!extension_loaded('gd')) {
                 Log::warning('GD Extension not loaded. PDF images disabled to prevent crash.');
                return null;
            }

            if (empty($path)) {
                return null;
            }

            $fullPath = public_path($path);
            
            if (!file_exists($fullPath)) {
                $cleanPath = str_replace('storage/', '', $path);
                $storagePath = storage_path('app/public/' . $cleanPath);
                if (file_exists($storagePath)) {
                    $fullPath = $storagePath;
                }
            }

            if (file_exists($fullPath)) {
                try {
                    $type = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
                    
                    if (file_exists($fullPath)) {
                        $im = null;
                        if ($type === 'webp' && function_exists('imagecreatefromwebp')) {
                            $im = @imagecreatefromwebp($fullPath);
                        } elseif (in_array($type, ['jpg', 'jpeg']) && function_exists('imagecreatefromjpeg')) {
                            $im = @imagecreatefromjpeg($fullPath);
                        } elseif ($type === 'png' && function_exists('imagecreatefrompng')) {
                            $im = @imagecreatefrompng($fullPath);
                        }

                        // If we have an image resource, let's resize it to be safe for DomPDF
                        if ($im) {
                            $width = imagesx($im);
                            $height = imagesy($im);
                            $targetWidth = 400; // Sufficient for signatures/stamps
                            
                            if ($width > $targetWidth) {
                                $targetHeight = floor($height * ($targetWidth / $width));
                                $tmp = imagecreatetruecolor($targetWidth, $targetHeight);
                                imagealphablending($tmp, false);
                                imagesavealpha($tmp, true);
                                imagecopyresampled($tmp, $im, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
                                $im = $tmp;
                            }

                            ob_start();
                            imagealphablending($im, false);
                            imagesavealpha($im, true);
                            imagepng($im); // Always output as PNG for DomPDF compatibility
                            $data = ob_get_clean();
                            return 'data:image/png;base64,' . base64_encode($data);
                        }
                    }

                    // Fallback to direct read if GD fails or type is unsupported
                    $data = file_get_contents($fullPath);
                    if ($data === false) {
                        Log::warning("ProofOfVoteService: Failed to read file contents: " . $fullPath);
                        return null;
                    }
                    return 'data:image/' . $type . ';base64,' . base64_encode($data);
                } catch (\Exception $e) {
                    Log::error("ProofOfVoteService: Error processing image " . $fullPath . ": " . $e->getMessage());
                    return null;
                }
            } else {
                Log::warning("ProofOfVoteService: Image not found at any path: " . $path);
            }
            
            return null;
        };
    }
}
