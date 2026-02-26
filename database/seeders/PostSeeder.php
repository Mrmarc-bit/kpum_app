<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::where('role', 'admin')->first() ?? \App\Models\User::first();

        if (!$admin) return;

        $posts = [
            [
                'title' => 'Pendaftaran Calon Ketua KPUM 2026 Resmi Dibuka',
                'excerpt' => 'Kesempatan bagi seluruh mahasiswa UNUGHA untuk berkontribusi dalam pesta demokrasi kampus.',
                'content' => 'KPUM UNUGHA secara resmi membuka pendaftaran bagi mahasiswa yang ingin mencalonkan diri sebagai pengurus inti...',
                'category' => 'Pengumuman',
            ],
            [
                'title' => 'Sosialisasi E-Voting di Fakultas Teknik',
                'excerpt' => 'Tim Technical Support KPUM melakukan roadshow untuk memastikan pemahaman sistem e-voting.',
                'content' => 'Dalam rangka meningkatkan partisipasi pemilih, KPUM mengadakan sosialisasi teknis mengenai penggunaan platform...',
                'category' => 'Berita',
            ],
            [
                'title' => 'Update Ketentuan DPT Pemilwa 2026',
                'excerpt' => 'Informasi penting mengenai perubahan syarat pemilih tetap untuk mahasiswa angkatan terbaru.',
                'content' => 'Berdasarkan rapat pleno KPUM, terdapat beberapa pembaruan mengenai kriteria Daftar Pemilih Tetap...',
                'category' => 'Informasi',
            ],
        ];

        foreach ($posts as $post) {
            \App\Models\Post::create([
                'title' => $post['title'],
                'slug' => \Illuminate\Support\Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'category' => $post['category'],
                'is_published' => true,
                'published_at' => now(),
                'user_id' => $admin->id,
            ]);
        }
    }
}
