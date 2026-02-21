<?php

namespace App\Services;

use App\Models\Kandidat;
use App\Models\AuditLog;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class KandidatService
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Create a new Presma Kandidat with thread-safe numbering.
     *
     * @param array $data
     * @param UploadedFile|null $foto
     * @param UploadedFile|null $fotoWakil
     * @return Kandidat
     */
    public function create(array $data, ?UploadedFile $foto = null, ?UploadedFile $fotoWakil = null): Kandidat
    {
        // Atomic Lock to prevent race conditions on 'no_urut'
        return Cache::lock('manage_kandidat_sequence', 5)->block(5, function () use ($data, $foto, $fotoWakil) {
            return DB::transaction(function () use ($data, $foto, $fotoWakil) {
                // Auto generate no_urut safely
                if (!isset($data['no_urut'])) {
                    $lastNo = Kandidat::max('no_urut');
                    $data['no_urut'] = $lastNo ? $lastNo + 1 : 1;
                }

                // Handle file uploads
                if ($foto) {
                    $data['foto'] = $this->imageService->upload($foto, $data['nama_ketua'] ?? 'ketua');
                }
                if ($fotoWakil) {
                    $data['foto_wakil'] = $this->imageService->upload($fotoWakil, $data['nama_wakil'] ?? 'wakil');
                }

                // Infer Faculty
                if (empty($data['fakultas_ketua']) && !empty($data['prodi_ketua'])) {
                    $data['fakultas_ketua'] = $this->getFakultasByProdi($data['prodi_ketua']);
                }
                if (empty($data['fakultas_wakil']) && !empty($data['prodi_wakil'])) {
                    $data['fakultas_wakil'] = $this->getFakultasByProdi($data['prodi_wakil']);
                }

                $kandidat = Kandidat::create($data);

                // Audit Log
                AuditLog::create([
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name ?? 'System',
                    'action' => 'CREATE KANDIDAT',
                    'details' => 'Menambahkan kandidat: ' . $data['nama_ketua'] . ' & ' . $data['nama_wakil'],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);

                return $kandidat;
            });
        });
    }

    /**
     * Update an existing Kandidat.
     */
    public function update(Kandidat $kandidat, array $data, ?UploadedFile $foto = null, ?UploadedFile $fotoWakil = null): Kandidat
    {
        return DB::transaction(function () use ($kandidat, $data, $foto, $fotoWakil) {
            // Handle file uploads
            if ($foto) {
                if ($kandidat->getRawOriginal('foto')) {
                    $this->imageService->delete((string) $kandidat->getRawOriginal('foto'));
                }
                $data['foto'] = $this->imageService->upload($foto, $data['nama_ketua'] ?? 'ketua');
            }
            if ($fotoWakil) {
                if ($kandidat->getRawOriginal('foto_wakil')) {
                    $this->imageService->delete((string) $kandidat->getRawOriginal('foto_wakil'));
                }
                $data['foto_wakil'] = $this->imageService->upload($fotoWakil, $data['nama_wakil'] ?? 'wakil');
            }

            // Infer Faculty
            if (empty($data['fakultas_ketua']) && !empty($data['prodi_ketua'])) {
                $data['fakultas_ketua'] = $this->getFakultasByProdi($data['prodi_ketua']);
            }
            if (empty($data['fakultas_wakil']) && !empty($data['prodi_wakil'])) {
                $data['fakultas_wakil'] = $this->getFakultasByProdi($data['prodi_wakil']);
            }

            $kandidat->update($data);
            
            return $kandidat->refresh(); // Return fresh instance
        });
    }

    /**
     * Delete a Kandidat.
     */
    public function delete(Kandidat $kandidat): bool
    {
        return DB::transaction(function () use ($kandidat) {
            if ($kandidat->getRawOriginal('foto')) {
                $this->imageService->delete((string) $kandidat->getRawOriginal('foto'));
            }
            if ($kandidat->getRawOriginal('foto_wakil')) {
                $this->imageService->delete((string) $kandidat->getRawOriginal('foto_wakil'));
            }

            // Capture details before deletion for audit
            $details = 'Menghapus kandidat ID: ' . $kandidat->id . ' (' . $kandidat->nama_ketua . ')';

            $deleted = $kandidat->delete();

            AuditLog::create([
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? 'System',
                'action' => 'DELETE KANDIDAT',
                'details' => $details,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            return $deleted;
        });
    }

    /**
     * Helper to get Faculty by Prodi.
     */
    private function getFakultasByProdi(string $prodi): string
    {
        $fkip = ['Bimbingan Konseling (BK)', 'Pendidikan Guru SD (PGSD)', 'Pendidikan Islam Anak Usia Dini (PIAUD)', 'Manajamen Pendidikan Islam (MPI)'];
        $fmikom = ['Matematika (MAT)', 'Informatika (INF)', 'Sistem Informasi (SI)'];
        $fti = ['Teknik Industri (TIND)', 'Teknik Kimia (TKIM)', 'Teknik Mesin (TM)'];
        $fe = ['Manajemen (MAN)', 'Ekonomi Pembangunan (EP)'];
        $fki = ['Pendidikan Agama Islam (PAI)', 'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)', 'Komunikasi Penyiaran Islam (KPI)', 'Hukum Keluarga Islam (HKI)'];

        if (in_array($prodi, $fkip)) return 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)';
        if (in_array($prodi, $fmikom)) return 'Fakultas Matematika dan Komputer (FMIKOM)';
        if (in_array($prodi, $fti)) return 'Fakultas Teknologi Industri (FTI)';
        if (in_array($prodi, $fe)) return 'Fakultas Ekonomi (FE)';
        if (in_array($prodi, $fki)) return 'Fakultas Keagamaan Islam (FKI)';

        return 'Umum';
    }
}
