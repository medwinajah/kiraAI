# KIRA AI

Aplikasi web pengelolaan keuangan pribadi untuk mencatat pemasukan, pengeluaran, dan kategori, serta memantau kondisi keuangan melalui dashboard dan laporan. Aplikasi juga menyediakan asisten keuangan AI dan pembacaan nota belanja melalui kamera.

## Fitur Utama

- **Akun pengguna** — registrasi, login (dengan *remember me*), logout, dan halaman profil beserta foto profil.
- **Pemasukan dan pengeluaran** — tambah, ubah, hapus, pencarian, filter rentang tanggal, dan paginasi.
- **Kategori** — kategori pemasukan dan pengeluaran yang dibuat sendiri oleh setiap pengguna.
- **Dashboard** — ringkasan total pemasukan, pengeluaran, dan saldo akhir, dilengkapi grafik perbandingan serta kategori teratas.
- **Laporan Pemasukan** — ringkasan dan grafik per periode (harian, mingguan, bulanan, tahunan, atau rentang tanggal), filter kategori, ekspor CSV/Excel, dan halaman cetak untuk disimpan sebagai PDF.
- **Laporan Pengeluaran** — struktur yang sama dengan Laporan Pemasukan, memakai kategori pengeluaran.
- **Asisten AI** — chatbot keuangan yang menjawab berdasarkan data pengguna, dan pemindaian nota belanja melalui kamera yang hasilnya dapat langsung disimpan sebagai transaksi.

Aplikasi bersifat *multi-user*: data transaksi dan kategori hanya dapat diakses oleh pemiliknya.

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12, Livewire 4 |
| Frontend | Bootstrap 5.3.8, Chart.js, Vite 7, CSS kustom |
| Database | SQLite (bawaan), mendukung MySQL/MariaDB |
| AI | Prism PHP dengan provider Groq |

## Preview Aplikasi

Belum ada screenshot pada repository ini.

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer 2
- Node.js 20+ dan npm
- SQLite (bawaan PHP) atau MySQL/MariaDB

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate

# Buat berkas database (Windows: New-Item -ItemType File database\database.sqlite)
touch database/database.sqlite

php artisan migrate
npm install
npm run build
php artisan storage:link
```


## Menjalankan Aplikasi

Menjalankan seluruh proses pengembangan sekaligus (server, antrian, log, dan Vite):

```bash
composer dev
```

Atau jalankan secara terpisah:

```bash
php artisan serve
npm run dev
```

Untuk lingkungan produksi, bangun aset terlebih dahulu:

```bash
npm run build
php artisan optimize
```

Aplikasi dapat diakses pada `http://127.0.0.1:8000`. Halaman pertama akan mengarahkan ke halaman login; buat akun melalui `/register`.

Pengguna dapat mencoba fitur AI setelah mengisi kredensial Groq pada `.env`. Fitur kamera pada pemindaian nota hanya berjalan pada `localhost` atau koneksi HTTPS.

goriSeeder`. Ganti kata sandi akun hasil seeder sebelum digunakan pada lingkungan nyata.

## Lisensi

MIT, sesuai deklarasi pada `composer.json`. Berkas `LICENSE` terpisah belum tersedia.

## Pengembang

Medwin Alfredo Silalahi — `medwinalfredo@gmail.com`
