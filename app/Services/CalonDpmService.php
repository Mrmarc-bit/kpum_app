<?php

namespace App\Services;

use App\Models\CalonDpm;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CalonDpmService
{
    protected ImageService $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Create a new Calon DPM with thread-safe numbering.
     *
     * @param array $data
     * @param UploadedFile|null $foto
     * @return CalonDpm
     */
    public function create(array $data, ?UploadedFile $foto = null): CalonDpm
    {
        // Use Redis atomic lock to prevent race conditions on numbering
        // Block for up to 5 seconds waiting for the lock
        return Cache::lock('manage_calon_dpm_sequence', 5)->block(5, function () use ($data, $foto) {
            return DB::transaction(function () use ($data, $foto) {
                // Handle numbering logic inside the lock
                if (!isset($data['urutan_tampil'])) {
                    $lastNo = CalonDpm::max('urutan_tampil');
                    $data['urutan_tampil'] = $lastNo ? $lastNo + 1 : 1;
                }

                if (empty($data['nomor_urut'])) {
                    $data['nomor_urut'] = (string) $data['urutan_tampil'];
                }

                // Handle file upload
                if ($foto) {
                    $data['foto'] = $this->imageService->upload($foto, $data['nama'] ?? 'calon-dpm');
                }

                // Infer Faculty if needed
                if (empty($data['fakultas']) && !empty($data['prodi'])) {
                    $data['fakultas'] = $this->getFakultasByProdi($data['prodi']);
                }

                return CalonDpm::create($data);
            });
        });
    }

    /**
     * Update an existing Calon DPM.
     *
     * @param CalonDpm $calon
     * @param array $data
     * @param UploadedFile|null $foto
     * @return CalonDpm
     */
    public function update(CalonDpm $calon, array $data, ?UploadedFile $foto = null): CalonDpm
    {
        return DB::transaction(function () use ($calon, $data, $foto) {
            // Logic for nomor_urut fallback if cleared
            if (empty($data['nomor_urut']) && !$calon->nomor_urut) {
                 // If not set and not in DB, use urutan_tampil or new input
                 $val = $data['urutan_tampil'] ?? $calon->urutan_tampil;
                 $data['nomor_urut'] = (string) $val;
            }

            // Handle file upload
            if ($foto) {
                if ($calon->getRawOriginal('foto')) {
                    $this->imageService->delete((string) $calon->getRawOriginal('foto'));
                }
                $data['foto'] = $this->imageService->upload($foto, $data['nama'] ?? 'calon-dpm');
            }

            // Infer Faculty
            if (empty($data['fakultas']) && !empty($data['prodi'])) {
                $data['fakultas'] = $this->getFakultasByProdi($data['prodi']);
            }

            $calon->update($data);
            
            return $calon->refresh();
        });
    }

    /**
     * Delete a Calon DPM.
     *
     * @param CalonDpm $calon
     * @return bool
     */
    public function delete(CalonDpm $calon): bool
    {
        return DB::transaction(function () use ($calon) {
            if ($calon->getRawOriginal('foto')) {
                $this->imageService->delete((string) $calon->getRawOriginal('foto'));
            }
            return $calon->delete();
        });
    }

    /**
     * Helper to get Faculty by Prodi.
     * Pure function, safe for Octane.
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
