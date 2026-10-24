# KIRA AI

KIRA AI (KiraAI) adalah aplikasi web untuk mengelola keuangan pribadi. Pengguna dapat mencatat pemasukan dan pengeluaran, mengelompokkannya berdasarkan kategori, memantau kondisi keuangan melalui dashboard, serta melihat laporan dalam berbagai periode.

Aplikasi juga dilengkapi asisten keuangan berbasis AI dan pemindaian nota belanja melalui kamera. Seluruh data transaksi dan kategori bersifat pribadi dan hanya dapat diakses oleh pemilik akun.

## Fitur Utama

- **Akun pengguna** — registrasi, login dengan opsi *remember me*, logout, dan pengelolaan profil beserta foto profil.
- **Dashboard** — ringkasan total pemasukan, pengeluaran, dan saldo, dilengkapi grafik keuangan.
- **Pencatatan pemasukan** — tambah dengan import file excel atau manual, ubah, hapus, pencarian, filter rentang tanggal, dan paginasi.
- **Pencatatan pengeluaran** — tambah dengan import file excel atau manual, ubah, hapus, pencarian, filter rentang tanggal, dan paginasi.
- **Impor transaksi Excel** — unduh template, unggah file, pratinjau dan validasi data, lalu konfirmasi penyimpanan. Tersedia untuk pemasukan dan pengeluaran.
- **Kategori** — pengelolaan kategori pemasukan dan pengeluaran yang dibuat sendiri oleh setiap pengguna.
- **Laporan pemasukan dan pengeluaran** — ringkasan, grafik, tabel transaksi, kategori teratas, filter periode (harian, mingguan, bulanan, tahunan, atau rentang tanggal), filter kategori, ekspor CSV yang kompatibel dengan Excel, dan halaman cetak untuk disimpan sebagai PDF.
- **Asisten keuangan AI** — chatbot yang menjawab pertanyaan berdasarkan ringkasan data keuangan pengguna.
- **Pemindaian nota** — membaca nota belanja melalui kamera, menampilkan hasilnya sebagai daftar transaksi, lalu menyimpannya sebagai pengeluaran.

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Antarmuka | Livewire 4, Bootstrap 5.3, Chart.js, Vite 7 |
| Database | SQLite (bawaan), mendukung MySQL/MariaDB |
| AI | Prism PHP dengan provider Groq |
| Impor/Ekspor | Laravel Excel (Maatwebsite) |

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer 2
- Node.js 20.19+ atau 22.12+ dan npm
- SQLite (bawaan PHP) atau MySQL/MariaDB
- Groq API key untuk mengaktifkan fitur AI

## Instalasi

Jalankan perintah berikut pada direktori project:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Pada Windows, penyalinan file `.env` dapat dilakukan dengan perintah:

```powershell
copy .env.example .env
```

Buat berkas database SQLite:

```bash
# Linux/macOS
touch database/database.sqlite

# Windows PowerShell
New-Item -ItemType File -Force database\database.sqlite
```

Lanjutkan dengan migrasi database, pemasangan dependensi frontend, dan pembuatan tautan penyimpanan:

```bash
php artisan migrate
npm install
npm run build
php artisan storage:link
```

## Konfigurasi Environment

Sesuaikan variabel berikut pada file `.env`:

- `APP_NAME` — nama aplikasi, misalnya `KIRA AI`.
- `APP_URL` — alamat aplikasi, misalnya `http://127.0.0.1:8000`.
- `DB_CONNECTION` — gunakan `sqlite` (bawaan) atau `mysql` beserta konfigurasi koneksi lainnya.
- `GROQ_API_KEY` — kunci API Groq untuk fitur asisten AI dan pemindaian nota.
- `GROQ_MODEL` dan `GROQ_VISION_MODEL` — model Groq yang digunakan (opsional, sudah memiliki nilai bawaan).

Fitur AI hanya dapat digunakan setelah `GROQ_API_KEY` diisi. Fitur kamera pada pemindaian nota memerlukan akses melalui `localhost` atau koneksi HTTPS.

## Menjalankan Aplikasi

Untuk menjalankan seluruh proses pengembangan sekaligus (server, antrian, log, dan Vite):

```bash
composer dev
```

Atau jalankan server dan Vite secara terpisah:

```bash
php artisan serve
npm run dev
```

Untuk lingkungan produksi, bangun aset terlebih dahulu:

```bash
npm run build
php artisan optimize
```

Aplikasi dapat diakses pada `http://127.0.0.1:8000`. Halaman awal akan mengarahkan ke halaman login; buat akun baru melalui halaman `/register`.

## Lisensi

MIT, sesuai deklarasi pada `composer.json`. Berkas `LICENSE` terpisah belum tersedia.

## Pengembang

Medwin Alfredo Silalahi
