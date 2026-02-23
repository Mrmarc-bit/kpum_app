<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mahasiswa extends Authenticatable
{
    use HasFactory, Notifiable;

    // Fakultas Keguruan dan Ilmu Pendidikan (FKIP)
    const PRODI_LIST = [
        'Bimbingan Dan Konseling (BK)' => 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)',
        'Pendidikan Guru Sekolah Dasar (PGSD)' => 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)',
        'Pendidikan Islam Anak Usia Dini (PIAUD)' => 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)',
        'Manajemen Pendidikan Islam (MPI)' => 'Fakultas Keguruan dan Ilmu Pendidikan (FKIP)',

        // Fakultas Matematika dan Komputer (FMIKOM)
        'Matematika (MAT)' => 'Fakultas Matematika dan Komputer (FMIKOM)',
        'Informatika (INF)' => 'Fakultas Matematika dan Komputer (FMIKOM)',
        'Sistem Informasi (SI)' => 'Fakultas Matematika dan Komputer (FMIKOM)',

        // Fakultas Teknologi Industri (FTI)
        'Teknik Industri (TIND)' => 'Fakultas Teknologi Industri (FTI)',
        'Teknik Kimia (TKIM)' => 'Fakultas Teknologi Industri (FTI)',
        'Teknik Mesin (TM)' => 'Fakultas Teknologi Industri (FTI)',

        // Fakultas Ekonomi (FE)
        'Ekonomi Pembangunan (EP)' => 'Fakultas Ekonomi (FE)',

        // Fakultas Keagamaan Islam (FKI)
        'Pendidikan Agama Islam (PAI)' => 'Fakultas Keagamaan Islam (FKI)',
        'Pendidikan Guru Madrasah Ibtidaiyah (PGMI)' => 'Fakultas Keagamaan Islam (FKI)',
        'Komunikasi dan Penyiaran Islam' => 'Fakultas Keagamaan Islam (FKI)',
        'Hukum Keluarga Islam (Ahwal Syakhshiyyah)' => 'Fakultas Keagamaan Islam (FKI)',
    ];

    protected $guard = 'mahasiswa';

    protected $fillable = [
        'nim',
        'name',
        'email',
        'password', // will store hashed DOB
        'dob',
        'access_code',
        'attended_at',
        'attendance_officer',
        'prodi',
        'fakultas',
        'voted_at',
        'dpm_voted_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'access_code', // Hide access code from serialization by default
    ];

    protected $casts = [
        'dob' => 'date',
        'voted_at' => 'datetime',
        'dpm_voted_at' => 'datetime',
        'attended_at' => 'datetime',
        'password' => 'hashed',
    ];
}
