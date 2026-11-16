# Panduan Gemini — Pertemuan 1: Tech Stack & Lifecycle Request

Dokumen ini adalah instruksi untuk AI (Gemini) yang membangun proyek **toko** (aplikasi Produk & Order) dari nol. Ini baru pertemuan 1 dari 9; pertemuan berikutnya akan datang sebagai dokumen terpisah dan melanjutkan proyek yang sama.

---

## 1. Peran dan aturan kerja

- Kamu yang menulis dan menjalankan semua kode. Pemilik proyek (peserta kursus) yang mereview.
- Kerjakan **hanya pertemuan 1**. Jangan membuat model, migration, service, atau fitur dari pertemuan lain.
- Bahasa: komentar kode, teks UI, pesan commit, dan README memakai **Bahasa Indonesia**. Nama class, fungsi, dan variabel tetap Bahasa Inggris.
- Ikuti **versi apa pun yang terpasang** saat `laravel new` dijalankan. Jangan memaksa versi tertentu. Laporkan versi PHP, Laravel, Inertia, dan Svelte yang akhirnya terpasang.
- Jika perintah atau API di dokumen ini tidak cocok dengan versi terpasang (misalnya nama flag installer berbeda), sesuaikan seperlunya, lalu **catat penyesuaian itu di README**. Jangan diam-diam mengubah rencana.
- Setiap langkah penting dikerjakan satu commit, pesan commit singkat dalam Bahasa Indonesia.

## 2. Keputusan proyek (berlaku untuk seluruh kursus)

Keputusan ini sudah final dan akan dipakai di dokumen pertemuan berikutnya. Untuk pertemuan 1 cukup diingat, tidak perlu diimplementasikan.

| Topik | Keputusan |
|---|---|
| Database | SQLite |
| Testing | Pest |
| Primary key tabel baru | UUID v7 (`HasUuids`); tabel `users` tetap bigint bawaan |
| Harga/uang | Versi aman: simpan sebagai integer (rupiah) atau gunakan bcmath, bukan float |
| Kolom `is_admin` | Ditambahkan di **pertemuan 4**, bukan 5 |
| Slug kategori | Diisi factory/seeder di pertemuan 2, diambil alih `CategoryObserver` di pertemuan 5 |
| Props daftar produk di pertemuan 3 | Paginator mentah dulu; diganti Resource di pertemuan 4 |
| Props Inertia (pertemuan 6) | Semua jenis dipakai: `defer`, `optional`, `always`, `merge`, `once` |
| Rate limit API | Satu definisi saja, tidak boleh ganda |
| `device_name` saat login API | Wajib diisi |
| Order web (pertemuan 9) | Hanya yang ada di modul; tidak perlu menambah kode di luar itu |

## 3. Tujuan pertemuan 1

Proyek berjalan di laptop, dan jalur satu request (URL sampai komponen Svelte) bisa dijelaskan. Belum ada fitur bisnis.

## 4. Prasyarat

Periksa dulu, laporkan jika ada yang kurang:

```bash
php -v          # minimal 8.3
composer -V
node -v
npm -v
git --version
```

Pasang installer jika belum ada:

```bash
composer global require laravel/installer
```

Pastikan direktori global Composer ada di `PATH` agar perintah `laravel` dikenali.

## 5. Langkah kerja

### Langkah 1 — Buat proyek

```bash
laravel new toko --svelte
```

- Jika installer bertanya, pilih: **SQLite** untuk database, **Pest** untuk testing, dan inisialisasi **git** (jika ditanya).
- Jika installer mendukung flag untuk pilihan di atas (misalnya `--pest`, `--git`, `--database=sqlite`), boleh dipakai. Jika flag tidak dikenali, jalankan secara interaktif.
- Masuk ke folder proyek dan pastikan jalan:

```bash
cd toko
composer run dev
```

Buka alamat yang ditampilkan di terminal dan pastikan halaman awal tampil. Hentikan server setelah dicek.

**Commit:** `Inisialisasi proyek toko dari starter kit Svelte`

### Langkah 2 — Controller "Tentang"

```bash
php artisan make:controller AboutController --invokable
```

Isi controller:

- Method `__invoke` mengembalikan `Inertia::render('About', [...])`.
- Props yang dikirim:

| Prop | Isi |
|---|---|
| `appName` | `config('app.name')` |
| `laravelVersion` | `app()->version()` |
| `features` | array berisi 3 teks bebas (Bahasa Indonesia), misalnya tentang Laravel, Inertia, dan Svelte |

Contoh bentuk (sesuaikan teks):

```php
namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('About', [
            'appName'        => config('app.name'),
            'laravelVersion' => app()->version(),
            'features'       => [
                'Laravel mengurus routing, validasi, dan database.',
                'Inertia menghubungkan controller ke komponen halaman.',
                'Svelte 5 menampilkan UI dari props.',
            ],
        ]);
    }
}
```

### Langkah 3 — Route

Di `routes/web.php`, tambahkan route `GET /tentang` bernama `about` yang menunjuk ke `AboutController`.

Pertimbangan penempatan: modul tidak menyebut route ini wajib login. Letakkan di luar grup `auth` supaya bisa dibuka tanpa login, kecuali starter kit mengharuskan lain. Catat pilihanmu di README.

Verifikasi:

```bash
php artisan route:list
```

Harus ada baris dengan nama `about`.

**Commit:** `Tambah AboutController dan route /tentang`

### Langkah 4 — Halaman Svelte

Buat `resources/js/pages/About.svelte`.

- Nama file harus cocok dengan string `'About'` di `Inertia::render`. Jika starter kit memakai folder atau kapitalisasi berbeda untuk halaman yang sudah ada (misalnya `Dashboard.svelte`), ikuti konvensi itu.
- Terima props dengan runes Svelte 5:

```svelte
<script lang="ts">
  let { appName, laravelVersion, features }: {
    appName: string;
    laravelVersion: string;
    features: string[];
  } = $props();
</script>
```

- Tampilkan ketiga props: nama aplikasi, versi Laravel, dan daftar fitur (pakai `{#each features as feature}`).
- Gunakan layout yang sama dengan halaman lain di starter kit agar tampilannya konsisten. Cek bagaimana `Dashboard.svelte` memakai layout, lalu tiru.
- Tidak perlu styling berlebihan; ikuti komponen dan kelas Tailwind yang sudah ada.

### Langkah 5 — Link dari dashboard

Di halaman dashboard bawaan starter kit, tambahkan:

```svelte
<script lang="ts">
  import { Link } from '@inertiajs/svelte';
</script>

<Link href="/tentang">Tentang aplikasi</Link>
```

Jika starter kit menyediakan helper route (misalnya Wayfinder) dan itu pola yang dipakai di halaman lain, boleh memakainya, selama hasilnya tetap mengarah ke `/tentang`. Catat di README jika memakai helper.

**Commit:** `Tambah halaman About dan link dari dashboard`

### Langkah 6 — README

Ganti atau lengkapi `README.md` di root proyek dengan bagian berikut (Bahasa Indonesia):

1. **Tentang proyek**: satu paragraf, aplikasi Produk & Order untuk belajar Laravel + Inertia + Svelte.
2. **Versi terpasang**: PHP, Laravel, Inertia (server dan client), Svelte, Node.
3. **Cara menjalankan**: `composer install`, `npm install`, salin `.env.example` ke `.env` jika perlu, `php artisan key:generate`, `php artisan migrate`, `composer run dev`.
4. **Jalur request `/tentang`**: urutan file yang dilewati, dari `public/index.php` sampai `About.svelte`. Isi dengan **file nyata di proyek ini** (periksa langsung, jangan menyalin dari teori). Kerangka yang diharapkan:

   1. `public/index.php`, pintu masuk, memuat autoloader dan aplikasi
   2. `bootstrap/app.php`, konfigurasi routing dan middleware
   3. `app/Providers/AppServiceProvider.php`, boot aplikasi
   4. Middleware grup `web` (session, CSRF) lalu `app/Http/Middleware/HandleInertiaRequests.php` (shared props)
   5. `routes/web.php`, mencocokkan `GET /tentang` ke route `about`
   6. `app/Http/Controllers/AboutController.php`, menjalankan `__invoke`
   7. `Inertia::render('About', [...])`, membuat nama komponen + props
   8. Respons: HTML penuh (kunjungan pertama) atau JSON dengan header `X-Inertia` (saat klik `Link`)
   9. Entry point JS di `resources/js/` yang memasang aplikasi Inertia
   10. `resources/js/pages/About.svelte`, menerima props lewat `$props()`

   Koreksi urutan atau nama file jika proyek nyata berbeda.
5. **Penyesuaian dari panduan**: daftar hal yang kamu ubah karena perbedaan versi atau konvensi starter kit (kosongkan jika tidak ada).

**Commit:** `Tambah README dengan jalur request /tentang`

## 6. Verifikasi (jalankan semuanya)

```bash
php artisan route:list --name=about
php artisan test
```

Dan cek manual lewat browser:

- [ ] `/tentang` menampilkan nama aplikasi, versi Laravel, dan 3 fitur.
- [ ] Klik link dari dashboard ke halaman Tentang **tidak** memicu reload penuh (tab tidak berkedip, state layout tetap).
- [ ] `php artisan route:list` menampilkan route `about`.
- [ ] `php artisan test` tidak menunjukkan kegagalan baru akibat perubahanmu.

Laporkan hasilnya satu per satu.

## 7. Bagian yang dikerjakan manual oleh pemilik proyek

Gemini tidak dapat melakukan ini, jadi **jangan dikarang**:

1. Dua screenshot DevTools → Network:
   - kunjungan pertama ke `/tentang` (respons HTML berisi data halaman),
   - klik `Link` dari dashboard (respons JSON dengan header `X-Inertia`).
2. Membuat repository git remote dan melakukan push.
3. Membuat branch `pertemuan-1` jika belum (Gemini boleh menyiapkan perintahnya).

Siapkan folder `docs/screenshots/` agar pemilik proyek tinggal menaruh dua gambar di sana, dan tambahkan referensinya di README.

## 8. Kriteria selesai

- `php artisan route:list` menampilkan route `about`.
- Berpindah ke halaman Tentang dari dashboard tidak memicu reload penuh.
- README memuat urutan file request `/tentang` yang sesuai dengan proyek nyata.
- Semua perubahan ada di branch `pertemuan-1` dengan commit yang rapi.

## 9. Format laporan akhir dari Gemini

Setelah selesai, balas dengan:

1. Versi yang terpasang (PHP, Laravel, Inertia, Svelte).
2. Daftar file yang dibuat atau diubah.
3. Hasil verifikasi pada bagian 6.
4. Penyesuaian dari panduan ini, jika ada.
5. Hal yang masih harus dikerjakan manual (bagian 7).

## 10. Yang tidak boleh dilakukan

- Jangan membuat model, migration, atau seeder (itu pertemuan 2).
- Jangan membuat service, Form Request, atau Resource (pertemuan 3 dan 4).
- Jangan mengubah file bawaan starter kit selain yang disebut di atas (dashboard untuk link, `routes/web.php`, README).
- Jangan mengarang screenshot atau hasil perintah yang tidak benar-benar dijalankan.
