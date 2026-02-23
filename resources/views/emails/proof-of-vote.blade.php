<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Bukti Resmi Pemilihan</title>
    <style type="text/css">
        /* Base Resets */
        body { margin: 0; padding: 0; min-width: 100%; width: 100%; background-color: #f0f4f8; -webkit-font-smoothing: antialiased; }
        table { border-spacing: 0; border-collapse: collapse; }
        td { padding: 0; vertical-align: top; }
        img { border: 0; }
        a { text-decoration: none; color: #3b82f6; }
        
        /* Mobile Optimizations */
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; max-width: 600px !important; }
            .content-padding { padding-left: 20px !important; padding-right: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <!-- Outer Wrapper -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f0f4f8" style="background-color: #f0f4f8;">
        <tr>
            <td align="center" style="padding-top: 40px; padding-bottom: 40px;">
                
                <!-- Main Container -->
                <table border="0" cellpadding="0" cellspacing="0" width="600" class="container" bgcolor="#ffffff" style="background-color: #ffffff; width: 600px; max-width: 600px; border: 1px solid #e2e8f0;">
                    
                    <!-- Header Banner (Solid Color, No Gradient) -->
                    <tr>
                        <td align="center" bgcolor="#1e40af" style="background-color: #1e40af; padding: 40px 20px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        @if($logoUrl)
                                            <a href="https://kpum.web.id" target="_blank" style="display: inline-block;">
                                                <img src="{{ $logoUrl }}" alt="Logo KPUM" width="80" height="80"
                                                    style="display: block; border: 0; border-radius: 50%; background-color: #ffffff; padding: 5px; object-fit: contain;">
                                            </a>
                                        @else
                                            <a href="https://kpum.web.id" target="_blank" style="display: inline-block; text-decoration: none;">
                                                <div style="width: 70px; height: 70px; background-color: #ffffff; color: #1e40af; border-radius: 50%; text-align: center; line-height: 70px; font-weight: bold; font-size: 28px; margin: 0 auto;">K</div>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #ffffff; font-size: 24px; font-weight: bold; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; letter-spacing: 1px;">
                                        SUKSES MEMILIH!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #bfdbfe; font-size: 14px; margin-top: 5px; text-transform: uppercase; letter-spacing: 2px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-top: 5px;">
                                        Komisi Pemilihan Umum Mahasiswa
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="content-padding" style="padding: 40px 30px; color: #334155; line-height: 1.6; font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                            
                            <!-- Greeting -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="font-size: 20px; font-weight: bold; color: #1e293b; padding-bottom: 20px;">
                                        Halo, {{ $user->name }}! 👋
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        Terima kasih telah berpartisipasi dalam pesta demokrasi kampus tahun ini. 
                                        Suara Anda telah berhasil direkam dan sangat berarti untuk masa depan organisasi kemahasiswaan kita.
                                    </td>
                                </tr>
                            </table>

                            <!-- Highlight Box (No Border Radius) -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#eff6ff" style="background-color: #eff6ff; border-left: 4px solid #3b82f6; margin-top: 5px; margin-bottom: 25px;">
                                <tr>
                                    <td style="padding: 15px 20px; font-size: 14px; color: #1e40af; font-weight: 500;">
                                        🗳️ <strong>Status:</strong> Anda telah menyelesaikan seluruh tahapan pemilihan (Presma & DPM).
                                    </td>
                                </tr>
                            </table>

                            <!-- Paragraphs -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        Sebagai arsip pribadi, kami melampirkan dokumen resmi <strong>Surat Bukti Pilihan</strong> dalam email ini. 
                                        Dokumen ini adalah bukti sah partisipasi Anda.
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 30px;">
                                        Semoga pemimpin dan wakil yang terpilih dapat mengemban amanah dengan baik. 
                                        Mari kita kawal bersama hasil pemilihan ini!
                                    </td>
                                </tr>
                                
                                <!-- Signature -->
                                <tr>
                                    <td style="padding-top: 10px;">
                                        Salam Demokrasi,<br />
                                        <span style="color: #1e40af; font-weight: bold; display: inline-block; margin-top: 5px;">Tim Teknis KPUM</span>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f8fafc" style="background-color: #f8fafc; padding: 20px; border-top: 1px solid #e2e8f0;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="font-size: 12px; color: #94a3b8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-bottom: 10px;">
                                        Email ini dikirim secara otomatis oleh Sistem E-Voting KPUM.
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-size: 12px; color: #94a3b8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                        &copy; {{ date('Y') }} Komisi Pemilihan Umum Mahasiswa. All rights reserved.
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 10px; font-size: 12px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                        <a href="https://kpum.web.id" style="color: #3b82f6; text-decoration: none;">Website</a> &bull; 
                                        <a href="https://instagram.com/kpumunugha" style="color: #3b82f6; text-decoration: none;">Instagram</a> &bull; 
                                        <a href="https://kpum.web.id/bantuan" style="color: #3b82f6; text-decoration: none;">Bantuan</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!-- End Main Container -->

                <!-- Spacer -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td style="font-size: 0; line-height: 0; padding-top: 40px;">&nbsp;</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>
