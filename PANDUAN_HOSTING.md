# Panduan Deployment di Hosting (Shared Hosting / cPanel)

Fitur download massal ini menggunakan **Queue System** agar website tidak "hang" atau *timeout* saat memproses ratusan file PDF.

Berikut adalah cara mengatur agar fitur ini berjalan lancar di hosting biasa (Shared Hosting/cPanel) yang mungkin memiliki keterbatasan resource.

## 1. Pastikan Konfigurasi .env

Pastikan file `.env` di hosting Anda menggunakan opsi ini:

```env
QUEUE_CONNECTION=database
```

JANGAN gunakan `sync` untuk fitur berat seperti ini, karena akan menyebabkan error "504 Gateway Timeout" di browser.

## 2. Setting "Worker" di cPanel (Wajib Ada!)

Agar proses download berjalan di background, hosting perlu menjalankan perintah worker secara berkala. Di Shared Hosting, cara paling aman dan stabil adalah menggunakan **Cron Job**.

### Langkah-langkah di cPanel

1. Masuk ke **cPanel**.
2. Cari menu **Cron Jobs**.
3. Tambahkan Cron Job baru.
4. **Common Settings**: Pilih `Once Per Minute` (`* * * * *`).
5. **Command**: Masukkan perintah berikut (sesuaikan path project Anda):

```bash
cd /home/username/public_html/kpum && /usr/local/bin/php artisan queue:work --stop-when-empty --timeout=600
```

**Penjelasan Command:**

- `cd /home/username/public_html/kpum`: Masuk ke folder project Anda.
- `/usr/local/bin/php`: Path ke PHP (bisa jadi cuma `php`, cek panduan hosting Anda).
- `artisan queue:work --stop-when-empty`: Menjalankan proses antrian sampai kosong, lalu berhenti. Ini aman karena tidak berjalan terus menerus (bukan *daemon*), sehingga hosting tidak akan mematikan prosesnya.
- `--timeout=600`: Memberikan waktu 10 menit per job agar tidak dipotong di tengah jalan.

## 3. Cara Cek Apakah Berjalan?

1. Buka website Anda.
2. Coba request download surat.
3. Tunggu 1-2 menit.
4. Jika status di tabel berubah dari "Pending" -> "Processing" -> "Selesai", berarti Cron Job sudah berhasil berjalan!

---

## Opsi Darurat (Tidak Disarankan)

Jika hosting Anda **sangat** terbatas dan tidak boleh pakai Cron Job, Anda bisa memaksa sistem berjalan manual (tapi browser akan loading lama/spin terus):

1. Ubah `.env` menjadi:

    ```env
    QUEUE_CONNECTION=sync
    ```

2. **Resiko**: Jika data Prodi banyak (>50 mahasiswa), browser mungkin akan *timeout* atau *white screen* sebelum download selesai.

**Sangat disarankan tetap menggunakan metode `database` + Cron Job.**

---

## Panduan Deployment di VPS / Dedicated Server (Systemd)

Jika Anda menggunakan VPS (Ubuntu, Debian, CentOS) dengan akses root/sudo, metode terbaik adalah menggunakan **Systemd Service**. Ini lebih stabil, otomatis restart jika crash, dan berjalan sebagai background process yang sebenarnya.

### 1. Persiapan File Service

Kami sudah menyediakan script installer otomatis untuk server berbasis Systemd (Ubuntu/Debian).

**File Terkait:**

1. `install_worker_service.sh` (Installer otomatis)
2. `kpum-queue-worker.service` (Konfigurasi Service)

### 2. Cara Install (Otomatis)

1. Upload semua file project ke VPS.
2. Edit file `kpum-queue-worker.service` **SEBELUM install**:
   - Sesuaikan `User=...` (misal: `www-data` atau user deploy Anda)
   - Sesuaikan `Group=...`
   - Sesuaikan `WorkingDirectory=/path/to/project`
   - Sesuaikan `ExecStart=/usr/bin/php /path/to/project/artisan queue:work ...`

3. Beri izin eksekusi pada script installer:

   ```bash
   chmod +x install_worker_service.sh
   ```

4. Jalankan installer dengan sudo:

   ```bash
   sudo ./install_worker_service.sh
   ```

Script ini akan otomatis:

- Menyalin service file ke `/etc/systemd/system/`.
- Mereload systemd daemon.
- Mengaktifkan auto-start saat boot.
- Menjalankan worker saat itu juga.

### 3. Cara Cek Status

Gunakan perintah bawaan systemd:

```bash
# Cek status (Running/Error)
sudo systemctl status kpum-queue-worker

# Lihat log error jika ada masalah
journalctl -u kpum-queue-worker -f
```

### 4. Cara Restart (Setelah Update Code)

Setiap kali Anda men-deploy ulang kode (git pull), Anda **WAJIB** me-restart queue worker agar perubahan kode terbaca:

```bash
sudo systemctl restart kpum-queue-worker
```

Atau gunakan perintah:

```bash
php artisan queue:restart
```
