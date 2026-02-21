# PANDUAN DEPLOY AMAN (DATA TIDAK HILANG & QR TETAP VALID)

Panduan ini dibuat khusus agar **QR Code, Surat Pemberitahuan, dan Data DPT** yang sudah Anda buat di Laptop (Local) **TETAP BISA DISCAN (VALID)** saat aplikasi di-online-kan nanti.

---

## ⚠️ PRINSIP UTAMA (JANGAN DILANGGAR)

Agar data sinkron dan QR Code bisa discan di server online, Anda **WAJIB** mematuhi 3 hukum ini:

1.  🔴 **JANGAN GENERATE `APP_KEY` BARU DI SERVER.**
    *   `APP_KEY` adalah kunci rahasia untuk membuat QR Code. Jika kunci ini berubah, semua QR Code yang sudah dicetak akan menjadi sampah (invalid).
    *   **Solusi:** Copy `APP_KEY` dari file `.env` di laptop ke server.

2.  🔴 **JANGAN RESET DATABASE DI SERVER.**
    *   Perintah `php artisan migrate:fresh` atau `db:seed` di server itu **DILARANG KERAS** setelah deploy pertama.
    *   Jika dijalankan, ID Mahasiswa akan berubah (misal: Budi yang tadinya ID 5 jadi ID 9). QR Code Budi (berisi ID 5) akan gagal discan.
    *   **Solusi:** Import database (`.sql`) dari laptop ke server.

3.  🔴 **JANGAN INPUT ULANG DATA MANUAL DI SERVER.**
    *   Jangan mengetik ulang data mahasiswa satu per satu di server. Pastikan semua data berasal dari database laptop yang di-export.

---

## TAHAP 1: PERSIAPAN DI LAPTOP (SEBELUM DEPLOY)

Lakukan ini di komputer/laptop tempat Anda mengembangkan aplikasi.

### 1. Amankan Kunci Rahasia (`APP_KEY`)
1.  Buka file bernama `.env` di folder project Anda.
2.  Cari baris yang berawalan `APP_KEY=`.
3.  Copy seluruh isinya (biasanya diawali `base64:....`).
4.  **Simpan kode ini baik-baik** (misal di Notepad HP atau dicatat). Ini "nyawa" dan kunci QR Code Anda.

### 2. Backup Database (Export SQL)
1.  Buka aplikasi database manager Anda (phpMyAdmin / HeidiSQL / DBeaver).
2.  Pilih database aplikasi (misal: `kpum`).
3.  Klik menu **Export** atau **Dump**.
4.  Pilih format **SQL**.
5.  Pastikan opsi **"Structure and Data"** (Struktur dan Data) dicentang.
6.  Simpan file tersebut, beri nama misal: `backup_siap_deploy_v1.sql`.

### 3. Backup File Upload (Tanda Tangan & Stempel)
QR Code dan surat menggunakan gambar tanda tangan dan stempel yang tersimpan di folder storage. Folder ini juga harus dibawa.

1.  Buka folder project Anda di File Explorer.
2.  Masuk ke folder: `storage/app/public`.
3.  Di sana ada folder-folder hasil upload (misal: `signatures`, `stamps`, `candidates`, `parties`).
4.  Select semua folder tersebut, lalu **Zip/Compress** menjadi satu file (misal: `storage_public_backup.zip`).

---

## TAHAP 2: PROSES DEPLOY DI SERVER (HOSTING/VPS)

Saat Anda sudah membeli hosting/domain dan siap online, lakukan langkah ini.

### 1. Upload Source Code
Upload semua file project Laravel Anda ke hosting (biasanya ke folder `public_html` atau sesuai panduan hosting), **KECUALI** folder `node_modules` dan `vendor` (sebaiknya install dependencies di server jika akses SSH ada, atau upload folder `vendor` jika pakai shared hosting tanpa SSH).

### 2. Konfigurasi Environment (`.env`)
1.  Di File Manager hosting, cari file `.env.example` lalu rename menjadi `.env` (atau edit file `.env` yang sudah ada).
2.  **EDIT BAGIAN INI DENGAN HATI-HATI:**

   ```ini
   APP_NAME="KPUM Online"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://nama-domain-anda.com

   # 🚨 PENTING: PASTE KODE DARI TAHAP 1 DI SINI
   APP_KEY=base64:GENERATE_DARI_LAPTOP_ANDA_JANGAN_UBAH
   
   # Konfigurasi Database Hosting
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_hosting
   DB_USERNAME=username_database_hosting
   DB_PASSWORD=password_database_hosting
   ```

### 3. Restore Database (Import SQL)
1.  Masuk ke **phpMyAdmin** di CPanel hosting.
2.  Pilih database yang baru Anda buat.
3.  Klik menu **Import**.
4.  Upload file `backup_siap_deploy_v1.sql` yang Anda buat di Tahap 1.
5.  Klik **Go/Kiriman**.
    *   *Catatan: Jangan jalankan perintah `php artisan migrate` lagi karena database sudah lengkap strukturnya dari import ini.*

### 4. Restore File Upload
1.  Di File Manager hosting, masuk ke `storage/app/public`.
2.  Upload file `storage_public_backup.zip` dari Tahap 1.
3.  Extract zip tersebut di sana.
4.  Pastikan folder `signatures`, `stamps`, dll sudah muncul.
5.  **PENTING (Shared Hosting):** Pastikan folder `storage` ter-link ke `public`.
    *   Biasanya di CPanel ada fitur "Laravel storage link".
    *   Atau jika punya akses terminal SSH, jalankan: `php artisan storage:link`.
    *   Atau manual: Buat folder bernama `storage` di dalam folder `public_html` (yang untuk akses web), lalu copy isi `storage/app/public` ke sana (cara darurat).

---

## TAHAP 3: VERIFIKASI AKHIR

Setelah deploy selesai, lakukan tes ini sebelum disebar ke mahasiswa:

1.  **Cek Login:** Coba login dengan akun admin yang biasa Anda pakai di laptop (password harusnya sama karena database hasil import).
2.  **Tes Scan QR:** Ambil salah satu surat pemberitahuan **yang sudah diprint dari laptop**. Coba scan menggunakan fitur scanner di web yang **sudah online**.
    *   ✅ **Sukses:** Jika muncul data mahasiswa.
    *   ❌ **Gagal:** Jika muncul "Invalid Key" atau "Data Tidak Ditemukan", berarti Anda salah memasukkan `APP_KEY` atau salah import database.

---

## 🆘 Troubleshooting (Simpan Bagian Ini)

**Q: Saya lupa copy APP_KEY dan sudah terlanjur generate baru di hosting. Apa yang terjadi?**
A: Semua QR Code lama (di surat fisik) tidak bisa dibaca lagi. Anda harus mencetak ulang SEMUA surat pemberitahuan dengan QR baru.

**Q: Saya lupa backup database dan malah migrate:fresh di hosting.**
A: ID Mahasiswa akan berantakan. Scan QR Code lama akan menampilkan data orang lain atau not found. Anda harus drop database hosting, lalu import ulang backup `.sql` dari laptop (jika masih ada).

**Q: Gambar tanda tangan tidak muncul di surat PDF saat digenerate online.**
A: Cek folder `storage/app/public/signatures`. Pastikan file gambarnya ada. Cek juga permission folder (biasanya 755 untuk folder, 644 untuk file). Pastikan `symlink` (shortcut) dari `public/storage` ke `storage/app/public` sudah benar.

Simpan panduan ini baik-baik! Semoga sukses deploy-nya! 🚀
