# Toko — Aplikasi Produk & Order (Pertemuan 1)

## 1. Tentang Proyek

Proyek **Toko** adalah aplikasi Produk & Order berbasis web yang dibangun secara bertahap dari nol untuk mempelajari arsitektur modern full-stack menggunakan Laravel 13, Inertia.js v3, Svelte 5, dan database PostgreSQL. Dokumen ini menandai penyelesaian **Pertemuan 1 dari 9**, yang berfokus pada:
- Setup stack Laravel + Inertia + Svelte dengan database PostgreSQL.
- Pembuatan model dan migration `Category` dengan UUID v7 (`HasUuids`) dan `$fillable`.
- Seeding data awal minimal 3 kategori (`CategorySeeder`) dipanggil dari `DatabaseSeeder`.
- Pembuatan route `GET /categories` bernama `categories.index` ke `CategoryController@index`.
- Query data kategori menggunakan Eloquent dan pengiriman melalui `Inertia::render('categories/Index', [...])`.
- Halaman `resources/js/pages/categories/Index.svelte` yang menampilkan nama dan deskripsi kategori.
- Link navigasi ke halaman Kategori dari Dashboard.
- Analisis dan dokumentasi dua kesalahan debug lab secara sengaja.

---

## 2. Status Checklist Tugas Pertemuan 1 (8/8 Selesai)

- [x] **Setup proyek toko dengan starter kit Svelte dan PostgreSQL, push ke git.**
- [x] **Migration + model Category (name, description) dengan HasUuids dan $fillable.**
- [x] **CategorySeeder berisi minimal 3 kategori, dipanggil dari DatabaseSeeder; jalankan php artisan migrate --seed.**
- [x] **Route GET /categories bernama categories.index ke CategoryController@index.**
- [x] **Controller meng-query kategori dengan Eloquent dan mengirimnya lewat Inertia::render('categories/Index', [...]).**
- [x] **resources/js/pages/categories/Index.svelte menampilkan nama dan deskripsi dari props.**
- [x] **Link ke halaman Kategori dari dashboard.**
- [x] **Debug: buat sengaja 2 error dari lab, catat pesan, penyebab, dan perbaikannya di README.md.**

---

## 3. Versi Terpasang

- **PHP**: `8.3.36`
- **Laravel Framework**: `13.42.0`
- **Database Engine**: `PostgreSQL 16`
- **Inertia.js (Server - `inertiajs/inertia-laravel`)**: `v3.7.0`
- **Inertia.js (Client - `@inertiajs/svelte`)**: `v3.11.0`
- **Svelte**: `v5.57.3`
- **Node.js**: `v24.14.0`
- **npm**: `v11.12.1`

---

## 4. Cara Menjalankan

1. **Pasang dependensi PHP:**
   ```bash
   composer install
   ```

2. **Pasang dependensi Node.js:**
   ```bash
   npm install
   ```

3. **Konfigurasi database PostgreSQL pada `.env`:**
   ```ini
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=toko
   DB_USERNAME=postgres
   DB_PASSWORD=postgres
   ```

4. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi dan seeder:**
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan development server:**
   ```bash
   composer run dev
   ```
   *Atau jalankan server Laravel dan Vite secara terpisah:*
   ```bash
   php artisan serve
   npm run dev
   ```

7. Buka browser pada alamat [http://localhost:8000](http://localhost:8000).

---

## 5. Jalur Request Halaman (Lifecycle Request)

### A. Lifecycle `/categories`
1. **`public/index.php`**: Menerima request HTTP `GET /categories` dan menjalankan autoloader.
2. **`bootstrap/app.php`**: Mendaftarkan routing web dan middleware pipeline (Session, CSRF, `HandleInertiaRequests`).
3. **`routes/web.php`**: Mencocokkan request dengan rute `categories.index` yang memanggil `CategoryController@index`.
4. **`app/Http/Controllers/CategoryController.php`**: Method `index()` mengambil data kategori dari database menggunakan Eloquent (`Category::query()->orderBy('name')->get()`).
5. **`Inertia::render('categories/Index', ['categories' => $categories])`**: Mengirim payload data ke komponen frontend Inertia.
6. **`resources/js/pages/categories/Index.svelte`**: Menangkap data `categories` via runes Svelte 5 (`$props()`), merender kartu kategori (nama, deskripsi, dan UUID) ke dalam layout aplikasi.

---

## 6. Laporan Debugging: 2 Error Sengaja dari Lab

Bagian ini mendokumentasikan dua kesalahan yang sengaja disimulasikan dari skenario lab pengembangan, lengkap dengan pesan error, akar penyebab, dan langkah perbaikannya:

### Error 1: MassAssignmentException pada Model Category
- **Pesan Error:**
  ```text
  Illuminate\Database\Eloquent\MassAssignmentException: Add [name] to fillable property to allow mass assignment on [App\Models\Category].
  ```
- **Penyebab:**
  Terjadi saat menjalankan `Category::create(['name' => '...', 'description' => '...'])` di seeder atau controller sementara properti `$fillable` belum didefinisikan pada model `Category`. Secara bawaan, Eloquent menerapkan perlindungan mass-assignment untuk mencegah injeksi kolom yang tidak diizinkan.
- **Perbaikan:**
  Tambahkan definisi properti `$fillable` pada `app/Models/Category.php`:
  ```php
  protected $fillable = [
      'name',
      'description',
  ];
  ```

---

### Error 2: Driver PostgreSQL Belum Diaktifkan di PHP (`QueryException`)
- **Pesan Error:**
  ```text
  Illuminate\Database\QueryException: could not find driver (Connection: pgsql, SQL: select * from information_schema.tables where table_schema = public and table_name = migrations and table_type = 'BASE TABLE')
  ```
- **Penyebab:**
  Laravel disetel menggunakan driver `DB_CONNECTION=pgsql`, tetapi ekstensi `pdo_pgsql` dan `pgsql` pada konfigurasi runtime PHP (`php.ini`) masih dinonaktifkan (diberi tanda komentar `;extension=pdo_pgsql`). Akibatnya PHP PDO tidak mengenali driver PostgreSQL.
- **Perbaikan:**
  Buka berkas `php.ini` pada instalasi PHP aktif, hapus tanda titik koma (`;`) di depan ekstensi PDO PostgreSQL:
  ```ini
  extension=pdo_pgsql
  extension=pgsql
  ```
  Simpan berkas, lalu restart service atau terminal agar ekstensi termuat (verifikasi dengan perintah `php -m | grep pgsql`).
