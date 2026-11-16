# Toko — Aplikasi Produk & Order (Pertemuan 1)

## 1. Tentang Proyek

Proyek **Toko** adalah aplikasi Produk & Order berbasis web yang dibangun secara bertahap dari nol untuk mempelajari arsitektur modern full-stack menggunakan Laravel, Inertia.js, dan Svelte. Dokumen ini menandai penyelesaian **Pertemuan 1 dari 9**, yang berfokus pada inisialisasi tech stack, penyiapan lingkungan kerja, pemahaman siklus hidup request (lifecycle request) dari server hingga ke komponen frontend Svelte 5, serta verifikasi pengujian dasar tanpa menambahkan fitur bisnis terlebih dahulu.

---

## 2. Versi Terpasang

Berikut adalah rincian versi perangkat lunak dan pustaka yang terpasang pada proyek ini:

- **PHP**: `8.3.36`
- **Laravel Framework**: `13.42.0`
- **Inertia.js (Server - `inertiajs/inertia-laravel`)**: `v3.7.0`
- **Inertia.js (Client - `@inertiajs/svelte`)**: `v3.11.0`
- **Svelte**: `v5.57.3`
- **Node.js**: `v24.14.0`
- **npm**: `v11.12.1`

---

## 3. Cara Menjalankan

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal:

1. **Pasang dependensi PHP:**
   ```bash
   composer install
   ```

2. **Pasang dependensi Node.js:**
   ```bash
   npm install
   ```

3. **Konfigurasi environment:**
   Jika berkas `.env` belum ada, salin dari `.env.example`:
   ```bash
   cp .env.example .env
   ```

4. **Generate application key:**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan migrasi database (SQLite):**
   ```bash
   php artisan migrate
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

## 4. Jalur Request `/tentang` (Lifecycle Request)

Berikut adalah urutan berkas nyata yang dilewati saat sebuah request HTTP diarahkan ke `/tentang`, mulai dari web server hingga komponen Svelte ditampilkan di layar:

1. **`public/index.php`**  
   Titik masuk (*entry point*) pertama setiap request HTTP. Berkas ini mendefinisikan konstanta waktu `LARAVEL_START`, memeriksa mode maintenance, memuat autoloader Composer (`vendor/autoload.php`), menginisialisasi bootstrap framework melalui `bootstrap/app.php`, dan menangani request yang masuk menggunakan `$app->handleRequest(Request::capture())`.

2. **`bootstrap/app.php`**  
   Pusat konfigurasi aplikasi pada struktur modern Laravel. Mengatur berkas routing (`routes/web.php` dan `routes/console.php`), rute pemantauan sistem `/up`, serta mendaftarkan middleware pipeline untuk grup `web`.

3. **`app/Providers/AppServiceProvider.php`**  
   Service provider dasar aplikasi yang dieksekusi saat framework melakukan fase booting untuk menginisialisasi layanan internal.

4. **Middleware Grup `web` & `app/Http/Middleware/HandleInertiaRequests.php`**  
   Request melewati middleware grup `web` bawaan (pengelolaan cookie, session, enkripsi, dan verifikasi CSRF). Selanjutnya dieksekusi middleware kustom yang terdaftar:
   - `App\Http\Middleware\HandleAppearance`: mengatur preferensi tema (dark/light/system).
   - `App\Http\Middleware\HandleInertiaRequests`: mendefinisikan data bersama (*shared props*) yang selalu dikirim ke Inertia, seperti nama aplikasi (`name`), status autentikasi (`auth.user`), dan status sidebar (`sidebarOpen`).
   - `AddLinkHeadersForPreloadedAssets`: menambahkan header optimasi pemuatan aset.

5. **`routes/web.php`**  
   Router mencocokkan URI `GET /tentang` dengan route bernama `about` yang menunjuk ke invokable controller `AboutController::class`. Rute ini sengaja ditempatkan di luar grup `auth` agar dapat diakses oleh publik tanpa perlu autentikasi.

6. **`app/Http/Controllers/AboutController.php`**  
   Controller menjalankan fungsi invokable `__invoke()`. Di dalamnya, controller menyiapkan data props:
   - `appName`: diambil dari `config('app.name')`
   - `laravelVersion`: diambil dari `app()->version()`
   - `features`: array berisi ringkasan peran Laravel, Inertia, dan Svelte 5.

7. **`Inertia::render('About', [...])`**  
   Inertia membungkus nama komponen `'About'` beserta payload props ke dalam sebuah response Inertia (`Inertia\Response`).

8. **Pengiriman Respons HTTP:**
   - **Kunjungan Langsung (First Page Visit):** Laravel merender tampilan induk Blade `resources/views/app.blade.php`. Komponen Blade `<x-inertia::app />` menyematkan atribut `data-page` yang berisi data JSON (nama komponen, props, url, dan versi aset).
   - **Navigasi Client-side (`<Link>`):** Jika navigasi dilakukan melalui komponen `<Link>` dari dashboard, Inertia client menyertakan header `X-Inertia: true`. Laravel merespons dengan JSON murni berisi komponen dan props yang baru tanpa merender ulang HTML Blade.

9. **`resources/js/app.ts`**  
   Entry point JavaScript client. Menginisialisasi aplikasi menggunakan `createInertiaApp`, memuat berkas halaman Svelte dari direktori `resources/js/pages/`, menyetel layout default (`AppLayout`), serta mengaktifkan penanganan tema dan notifikasi flash toast.

10. **`resources/js/pages/About.svelte`**  
    Komponen frontend halaman tentang aplikasi. Menggunakan runes Svelte 5 (`$props()`) untuk menangkap `appName`, `laravelVersion`, dan `features`, memanfaatkan persistent layout breadcrumbs, dan merender informasi aplikasi beserta perulangan daftar fitur melalui blok `{#each features as feature}`.

---

## 5. Penyesuaian dari Panduan

Beberapa penyesuaian teknis dilakukan agar sesuai dengan kondisi lingkungan dan versi pustaka terbaru:

1. **Pembaruan PHP ke 8.3:**  
   Starter kit resmi Svelte (`laravel/svelte-starter-kit` dengan Laravel 13) mewajibkan versi PHP minimal `^8.3`. PHP lokal diperbarui ke versi `8.3.36` dengan ekstensi SQLite, cURL, MBString, Fileinfo, OpenSSL, dan ZIP aktif.
2. **Dependensi Frontend `@internationalized/date`:**  
   Ditambahkan paket `@internationalized/date` ke dalam `package.json` untuk memenuhi dependensi komponen kalender pada `bits-ui` bawaan starter kit.
3. **Penempatan Rute Bebas Autentikasi:**  
   Route `about` diletakkan di luar middleware grup `auth` pada `routes/web.php` agar dapat diakses oleh publik tanpa mewajibkan sesi login.
4. **Dukungan Routing Helper (Wayfinder):**  
   Proyek starter kit Svelte ini menggunakan `@laravel/vite-plugin-wayfinder` untuk tipe rute TypeScript otomatis. Link di `Dashboard.svelte` tetap menggunakan `<Link href="/tentang">` sesuai instruksi, didukung dengan alias route `about` di backend.

---

## 6. Berkas Screenshot DevTools (Panduan Manual)

Berdasarkan Bagian 7 dari panduan, pemilik proyek dapat melengkapi dua screenshot DevTools Network dan menyimpannya di folder `docs/screenshots/`:

1. `docs/screenshots/01-initial-visit-html.png`: Screenshot DevTools → Network saat pertama kali membuka `/tentang` (respons berformat HTML lengkap).
2. `docs/screenshots/02-inertia-link-json.png`: Screenshot DevTools → Network saat mengeklik tautan "Tentang aplikasi" dari Dashboard (respons berformat JSON dengan header `X-Inertia`).
