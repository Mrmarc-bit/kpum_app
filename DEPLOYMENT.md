# Panduan Deployment Aplikasi KPUM dengan Docker

Dokumen ini berisi panduan lengkap untuk menjalankan aplikasi E-Voting KPUM menggunakan Docker. Metode ini sangat disarankan untuk konsistensi lingkungan pengembangan (local) maupun produksi (server).

## Prasyarat

Sebelum memulai, pastikan server atau komputer Anda sudah terinstall:

1.  **Docker** & **Docker Compose**
    *   Windows/Mac: Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
    *   Linux (Server):
        ```bash
        curl -fsSL https://get.docker.com -o get-docker.sh
        sh get-docker.sh
        ```

2.  **Git** (Untuk clone repository).

---

## 1. Persiapan Awal

1.  **Clone Repository**
    Salin kode aplikasi ke komputer/server Anda:
    ```bash
    git clone https://github.com/username/repo-kpum.git
    cd repo-kpum
    ```

2.  **Konfigurasi Environment (.env)**
    Salin file contoh `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```

    **PENTING:** Sesuaikan konfigurasi database di file `.env` agar cocok dengan `docker-compose.yml`:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=db
    DB_PORT=3306
    DB_DATABASE=kpum
    DB_USERNAME=laravel
    DB_PASSWORD=root

    REDIS_HOST=redis
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    # Ganti dengan domain asli saat production
    APP_URL=http://localhost:8000 
    ```

---

## 2. Instalasi & Menjalankan Aplikasi

Jalankan perintah berikut untuk membangun (build) image dan menjalankan container:

```bash
docker-compose up -d --build
```
*   `up`: Menjalankan container.
*   `-d`: Detached mode (jalan di background).
*   `--build`: Memaksa docker membangun ulang image (penting saat pertama kali atau ada perubahan Dockerfile).

Tunggu hingga proses selesai. Docker akan menginstall PHP, Nginx, MySQL, dan Redis secara otomatis.

---

## 3. Setup Database & Aplikasi

Setelah container berjalan, Anda perlu menginstall dependensi PHP dan melakukan migrasi database.

1.  **Install Dependensi (Composer & NPM)**
    ```bash
    docker-compose exec app composer install
    docker-compose exec app npm install && npm run build
    ```

2.  **Generate Key Aplikasi**
    ```bash
    docker-compose exec app php artisan key:generate
    ```

3.  **Migrasi & Seeding Database**
    Mebuat tabel dan mengisi data awal (admin default, dll):
    ```bash
    docker-compose exec app php artisan migrate --seed
    ```

4.  **Link Storage (Agar gambar muncul)**
    ```bash
    docker-compose exec app php artisan storage:link
    ```

---

## 4. Akses Aplikasi

*   **Halaman Utama:** Buka browser dan akses [http://localhost:8000](http://localhost:8000)
    *   Jika di server VPS, ganti `localhost` dengan IP Public server Anda.
*   **Database Manager:** Anda bisa menggunakan software seperti DBeaver atau TablePlus dengan koneksi:
    *   Host: `localhost` (atau IP Server)
    *   Port: `3306` (sesuai mapping port di docker-compose jika dibuka, default docker internal)
    *   User: `laravel`
    *   Pass: `root`

---

## 5. Perintah Berguna (Cheatsheet)

Karena aplikasi berjalan dalam isolasi container, perintah artisan tidak bisa dijalankan langsung di terminal biasa. Gunakan awalan `docker-compose exec app`.

**Menjalankan Perintah Artisan:**
```bash
docker-compose exec app php artisan <perintah>
# Contoh:
docker-compose exec app php artisan make:controller TestController
```

**Melihat Log Aplikasi:**
```bash
docker-compose logs -f app
```

**Melihat Log Nginx (Web Server):**
```bash
docker-compose logs -f nginx
```

**Restart Worker (Jika ada perubahan kode pengiriman email):**
```bash
docker-compose restart pro-worker
```

**Masuk ke dalam Terminal Container:**
```bash
docker-compose exec app bash
```

**Mematikan Aplikasi:**
```bash
docker-compose down
```

---

## 6. Troubleshooting (Masalah Umum)

**A. Permission Error pada folder storage/logs**
Jika muncul error "The stream or file ... could not be opened...", jalankan:
```bash
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

**B. Database Connection Refused**
Pastikan di `.env` tertulis `DB_HOST=db`, BUKAN `localhost` atau `127.0.0.1`. Container aplikasi dan container database berkomunikasi lewat jaringan internal docker dengan nama service (`db`).

**C. Perubahan .env tidak terbaca**
Jika Anda mengubah file `.env` setelah container jalan, bersihkan cache konfigurasi:
```bash
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan config:cache
```
Lalu restart container:
```bash
docker-compose restart app
```
