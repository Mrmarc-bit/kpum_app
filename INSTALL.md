# Panduan Instalasi KPUM

Berikut adalah cara untuk menginstal dan menjalankan aplikasi ini di lingkungan baru.

## Prasyarat

- PHP 8.2 atau lebih baru
- Composer
- Node.js & NPM
- Database (MySQL/MariaDB/SQLite)

## Langkah Instalasi

1. **Clone Repository (atau download source code)**

   ```bash
   git clone <repository_url>
   cd kpum
   ```

2. **Jalankan Setup Otomatis**
   Kami telah menyediakan script setup untuk mempermudah instalasi.

   ```bash
   composer run setup
   ```

   *Perintah ini akan melakukan: install dependencies, setup .env, generate key, migrate database, install package frontend, dan membuat symlink storage.*

3. **Jalankan Server**

   ```bash
   composer run dev
   ```

## Instalasi Manual (Jika setup otomatis gagal)

1. **Install PHP Dependencies**

   ```bash
   composer install
   ```

2. **Setup Environment**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Setup Database**
   Edit file `.env` dan sesuaikan konfigurasi database (DB_DATABASE, DB_USERNAME, dll). Kemudian jalankan:

   ```bash
   php artisan migrate
   ```

4. **link Storage (PENTING UNTUK GAMBAR/LOGO)**
   Agar logo dan gambar bisa muncul, wajib jalankan perintah ini:

   ```bash
   php artisan storage:link
   ```

5. **Install Frontend**

   ```bash
   npm install
   npm run build
   ```

6. **Jalankan Aplikasi**

   ```bash
   npm run dev
   # dan di terminal lain:
   php artisan serve
   ```
