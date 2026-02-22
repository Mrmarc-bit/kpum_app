# Panduan Lengkap Deploy KPUM Menggunakan Docker di VPS (Ubuntu)

Dokumen ini berisi langkah-langkah sistematis dari awal hingga akhir untuk melakukan proses *deployment* ulang aplikasi KPUM App di Server VPS.

---

## 1. Persyaratan Sistem & Server
- **OS Server:** Ubuntu Desktop/Server (Direkomendasikan versi 22.04 LTS atau terbaru).
- **Akses:** Root atau user dengan hak *sudo*.
- **Aplikasi Wajib:**
  - `git`
  - `docker`
  - `docker-compose` (Docker Plugin)

*(Pastikan firewall / UFWVPS Anda mengizinkan port `80` dan `443`)*

## 2. Mengambil Kode (Clone Repositori)
Masuk ke direktori utama di server (misalnya `/var/www/` atau `/home/user/`) lalu lakukan *clone*:
```bash
git clone https://github.com/Mrmarc-bit/kpum_app.git
cd kpum_app
```

## 3. Konfigurasi Environment (`.env`)
Salin kerangka konfigurasi yang sudah disediakan:
```bash
cp .env.example .env
```

Buka dan sesuaikan file `.env` tersebut (`nano .env`). Fokus pada poin-poin krusial di bawah ini:
```env
APP_NAME="KPUM System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

# Kredensial Database (Bebas namun wajib sama dengan Docker)
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=kpum_db
DB_USERNAME=kpum_user
DB_PASSWORD=ISI_DENGAN_PASSWORD_KUAT

# Infrastruktur Lanjutan 
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

# Memaksa Cloudflare / Proxy menampilkan HTTPS dan Aset utuh
ASSET_URL=https://domain-anda.com
FORCE_HTTPS=true
```

## 4. Build dan Jalankan Container Docker
Jalankan proses *build* *images* PHP, Nginx, MySQL, dan Redis, sekaligus menyalakannya di latar belakang:
```bash
docker compose up -d --build
```
*(Catatan: Dockerfile akan secara otomatis menggunakan `host network` saat tahap instalasi Composer untuk mengeksekusi dependensi PHP guna menghindari gagalnya masalah DNS saat proses build).*

## 5. Persiapan Akhir Aplikasi (Masuk ke dalam Sistem)
Setelah semua balok container Docker berstatus `Up`, kita wajib melakukan inisialisasi pada logika aplikasi utama (Container bernama `app`):

### A. Buat "Kunci Utama" Keamanan
```bash
docker compose exec app php artisan key:generate
```

### B. Menyesuaikan Izin Folder (Permission)
Folder penyimpanan dan *cache* wajib diberikan hak kelola kepada entitas sistem aplikasi (`www-data` atau UID 1000).
```bash
docker compose exec app chown -R 1000:1000 /var/www/kpum_app/storage /var/www/kpum_app/bootstrap/cache
docker compose exec app chmod -R 775 /var/www/kpum_app/storage /var/www/kpum_app/bootstrap/cache
```

### C. Eksekusi Skema Database
Suntikkan struktur tabel dan langsung isikan "Tiga User Awal" bawaan sistem (Super Admin, Petugas, Mahasiswa Demo).
⚠️ *Hati-hati: Perintah ini menghapus semua data jika database telah berisi data lama!*
```bash
docker compose exec app php artisan migrate:fresh --seed --force
```

### D. Optimasi Kecepatan (Live Production)
Cache pengaturan dan struktur *view* supaya tidak lambat.
```bash
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan view:cache
docker compose exec app php artisan config:cache
```

## 6. Konfigurasi Domain dan Koneksi Secure (Cloudflare Proxy)
Aplikasi hanya memaparkan dan mengekspos Port HTTP `80` di internal mesin Docker.

Agar gembok HTTPS menyala tanpa bentrok di aplikasi:
1. Masuk ke *dashboard* domain pelindung, **Cloudflare**.
2. Daftarkan DNS `A Record` Anda dan arahkan ke IPv4 Publik Server VPS Anda. Pastikan ikon Awan berstatus *Proxied* (Warna Oranye).
3. Masuk ke halaman **SSL/TLS -> Overview**.
4. Ubah mode Enkripsi SSL menjadi **Flexible**.
   *(Flexible berarti Cloudflare berkomunikasi aman (HTTPS) ke pengunjung, namun dari Cloudflare ke VPS hanya HTTP biasa, hal ini paling serasi dengan setup Docker kita saat ini yang berjalan di Port 80).*

---

## Memperbarui Sistem di Masa Mendatang (Git Pull)
Jika kami tim developer melakukan update kode baru, Anda tidak perlu mulai dari awal. Anda hanya perlu mengeksekusi ini di terminal dalam direktori VPS:

```bash
# 1. Menarik berkas yang baru diganti
git pull origin main

# 2. (Opsional) Jika yang beruah adalah dependensi/composer.json:
docker compose exec app composer install --optimize-autoloader --no-dev

# 3. Segarkan cache view
docker compose exec app php artisan view:clear
```
Jika file yang Anda ubah memiliki pengaruh terhadap infrastruktur utama (misalnya perubahan versi di `Dockerfile` atau modifikasi `docker-compose.yml`), jalankan kembali:
```bash
docker compose up -d --build
```
