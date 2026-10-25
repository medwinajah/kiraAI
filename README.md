<p align="center">
  <img src="public/img/LogoKiraAI.jpeg" alt="Logo KIRA AI" width="130">
</p>

<h1 align="center">KIRA AI</h1>

<p align="center">
  <strong>Asisten keuangan berbasis AI untuk UMKM</strong><br>
  Catat pemasukan, kendalikan pengeluaran, dan pahami arus kas usaha dalam satu aplikasi.
</p>

---

## Tentang KIRA AI

KIRA AI adalah aplikasi web pencatatan keuangan yang dirancang untuk membantu pelaku UMKM mengelola pemasukan dan pengeluaran usaha secara rapi, cepat, dan mudah dipahami. Pencatatan dapat dilakukan secara manual, melalui impor file Excel, atau dengan memindai nota belanja menggunakan kamera.

Selain sebagai buku kas digital, KIRA AI membantu pengguna membaca kondisi keuangan melalui dashboard interaktif, laporan per periode, analisis kategori, serta asisten keuangan berbasis AI yang menjawab pertanyaan berdasarkan data transaksi pengguna.

Pendekatan pencatatan yang sama juga dapat digunakan untuk kebutuhan keuangan pribadi, sehingga KIRA AI cocok untuk pelaku usaha, pekerja lepas, maupun pengguna yang ingin lebih tertib mengelola keuangannya.

## Keunggulan

- **Pencatatan yang fleksibel** — input manual, impor Excel, atau scan nota langsung dari halaman pengeluaran.
- **Asisten AI yang kontekstual** — pertanyaan dijawab berdasarkan ringkasan pemasukan dan pengeluaran pengguna, bukan jawaban umum.
- **Analisis yang mudah dibaca** — dashboard dan laporan menyajikan total, grafik, kategori teratas, dan transaksi terbaru.
- **Laporan siap digunakan** — unduh CSV yang kompatibel dengan Excel atau cetak laporan untuk disimpan sebagai PDF.
- **Data terpisah per akun** — setiap transaksi terhubung ke akun pengguna yang sedang login.

## Fitur Utama

### Pencatatan Pemasukan

- Menambah pemasukan dengan kategori, nama transaksi, nominal, sumber pemasukan, tanggal, dan deskripsi.
- Menampilkan daftar pemasukan dengan pencarian berdasarkan nama atau nominal.
- Filter berdasarkan rentang tanggal, reset filter, dan paginasi.
- Mengubah dan menghapus data pemasukan.

### Pencatatan Pengeluaran

- Menambah pengeluaran dengan kategori, nama transaksi, nominal, tanggal, dan deskripsi.
- Menampilkan daftar pengeluaran dengan pencarian berdasarkan nama, nominal, atau kategori.
- Filter berdasarkan rentang tanggal, reset filter, dan paginasi.
- Mengubah dan menghapus data pengeluaran.

### Impor Transaksi Excel

- Mengunduh template Excel yang otomatis menyesuaikan kategori milik akun pengguna.
- Mengunggah file `XLSX`, `XLS`, atau `CSV` dengan ukuran maksimal 5 MB.
- Menampilkan pratinjau dan validasi setiap baris sebelum data disimpan.
- Menandai baris yang bermasalah, misalnya tanggal tidak valid, nominal terlalu kecil, atau kategori tidak ditemukan.
- Menyimpan seluruh data sekaligus; jika terjadi kesalahan, tidak ada data yang tersimpan sebagian.
- Membatalkan pratinjau tanpa mengubah data transaksi.

### Kategori

- Kategori pemasukan dan pengeluaran dikelola secara terpisah.
- Setiap kategori memiliki nama dan deskripsi.
- Kategori dapat ditambah, diubah, dan dihapus.
- Kategori yang sudah dibuat langsung tersedia pada formulir transaksi, filter laporan, dan template impor.

### Dashboard Interaktif

- Ringkasan total pemasukan, total pengeluaran, dan saldo akhir.
- Sapaan pengguna serta tanggal hari ini.
- Grafik perbandingan pemasukan dan pengeluaran.
- Filter periode: harian, mingguan, bulanan, tahunan, atau rentang tanggal khusus.
- Grafik kategori pemasukan dan pengeluaran teratas.
- Daftar transaksi terbaru dari kedua jenis transaksi.

### Laporan Pemasukan dan Pengeluaran

- Ringkasan total, jumlah transaksi, rata-rata, nilai terbesar, kategori teratas, dan transaksi terakhir.
- Filter periode dan kategori, serta pencarian pada tabel laporan.
- Grafik dan tabel transaksi sesuai filter yang dipilih.
- Ekspor CSV yang dapat dibuka langsung di Microsoft Excel.
- Halaman cetak untuk menyimpan laporan sebagai PDF melalui browser.

### Asisten Keuangan AI

- Widget **Tanya AI** yang dapat dibuka dari halaman mana pun setelah login.
- Menjawab pertanyaan tentang pemasukan, pengeluaran, saldo, dan kebiasaan keuangan pengguna.
- Menggunakan ringkasan data keuangan pengguna sebagai konteks jawaban.
- Mendukung jawaban dalam format Markdown, termasuk daftar dan tabel.
- Memiliki aturan agar AI tidak mengarang data, tidak meminta informasi rahasia, dan tidak mengklaim dapat mengubah transaksi.
- Riwayat percakapan disimpan selama sesi berlangsung.

### Scan Nota dengan AI

- Mengambil foto nota belanja melalui kamera perangkat.
- Membaca nama toko, tanggal nota, dan rincian barang menggunakan model AI vision.
- Menampilkan hasil pembacaan sebagai daftar item yang dapat diperiksa dan diubah.
- Setiap item dapat dilengkapi kategori, tanggal, dan deskripsi sebelum disimpan.
- Menyimpan seluruh item sebagai transaksi pengeluaran dalam satu proses.

### Akun dan Profil

- Registrasi akun dengan nama, email, jenis kelamin, foto profil opsional, dan kata sandi.
- Login dengan opsi *remember me*, serta logout yang aman.
- Pengelolaan profil: nama, email, jenis kelamin, dan foto profil.
- Foto profil dapat diganti kapan saja; foto lama akan dihapus setelah foto baru berhasil disimpan.

## Alur Penggunaan

1. Buat akun melalui halaman registrasi, lalu masuk ke aplikasi.
2. Siapkan kategori pemasukan dan pengeluaran sesuai kebutuhan usaha.
3. Catat transaksi secara manual, impor dari Excel, atau scan nota belanja.
4. Pantau ringkasan dan grafik pada dashboard.
5. Buka laporan untuk melihat detail per periode, lalu ekspor atau cetak sesuai kebutuhan.

## Teknologi yang Digunakan

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Antarmuka | Blade, Livewire 4, Bootstrap 5.3 |
| Grafik | Chart.js 4 |
| Build Tool | Vite 7, npm, Tailwind CSS 4 |
| Database | SQLite (bawaan), MySQL/MariaDB |
| Impor/Ekspor | Laravel Excel (Maatwebsite) |
| AI | Prism PHP dengan provider Groq |
| Notifikasi | SweetAlert |

## Persyaratan

- PHP 8.2 atau lebih baru
- Composer 2
- Node.js 20.19+ atau 22.12+ dan npm
- SQLite (bawaan PHP) atau MySQL/MariaDB
- Groq API key untuk mengaktifkan asisten AI dan scan nota

## Instalasi

Jalankan perintah berikut pada direktori project.

### 1. Pasang dependensi backend

```bash
composer install
```

### 2. Siapkan file environment

```bash
cp .env.example .env
php artisan key:generate
```

Pada Windows, penyalinan file dapat dilakukan dengan:

```powershell
copy .env.example .env
```

### 3. Siapkan database

Untuk SQLite, buat file database terlebih dahulu:

```bash
# Linux/macOS
touch database/database.sqlite
```

```powershell
# Windows PowerShell
New-Item -ItemType File -Force database\database.sqlite
```

Lalu jalankan migrasi:

```bash
php artisan migrate
```

Jika menggunakan MySQL/MariaDB, sesuaikan konfigurasi database pada `.env` terlebih dahulu.

### 4. Pasang dependensi frontend dan bangun aset

```bash
npm install
npm run build
```

### 5. Buat tautan penyimpanan

```bash
php artisan storage:link
```

Tautan ini diperlukan agar foto profil yang diunggah dapat ditampilkan.

## Konfigurasi Environment

Sesuaikan variabel berikut pada file `.env`:

| Variabel | Keterangan |
| --- | --- |
| `APP_NAME` | Nama aplikasi, misalnya `KIRA AI`. |
| `APP_URL` | Alamat aplikasi, misalnya `http://127.0.0.1:8000`. |
| `DB_CONNECTION` | `sqlite` (bawaan) atau `mysql`. |
| `DB_DATABASE` | Lokasi file SQLite atau nama database MySQL/MariaDB. |
| `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` | Konfigurasi koneksi jika menggunakan MySQL/MariaDB. |
| `GROQ_API_KEY` | Kunci API Groq untuk asisten AI dan scan nota. |
| `GROQ_MODEL` | Model Groq untuk asisten keuangan. |
| `GROQ_VISION_MODEL` | Model Groq vision untuk membaca nota. |
| `PRISM_REQUEST_TIMEOUT` | Batas waktu permintaan ke provider AI (opsional). |

Catatan:

- Fitur AI hanya aktif setelah `GROQ_API_KEY` diisi dengan kunci yang valid.
- Nilai `GROQ_MODEL` dan `GROQ_VISION_MODEL` perlu disesuaikan dengan model yang tersedia pada akun Groq.
- Fitur kamera pada scan nota memerlukan akses melalui `localhost` atau koneksi HTTPS.

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

Aplikasi dapat diakses pada `http://127.0.0.1:8000`. Halaman awal akan mengarahkan ke halaman login; buat akun baru melalui halaman `/register`.

Untuk lingkungan produksi, bangun aset terlebih dahulu:

```bash
npm run build
php artisan optimize
```

Pastikan `APP_ENV=production` dan `APP_DEBUG=false` pada `.env` produksi.

## Format Impor Excel

Template dapat diunduh langsung dari halaman pemasukan atau pengeluaran. Gunakan template terbaru agar nama kolom sesuai.

| Jenis | Urutan Kolom |
| --- | --- |
| Pemasukan | `tanggal`, `label`, `jumlah`, `kategori`, `sumber_pemasukan`, `deskripsi` |
| Pengeluaran | `tanggal`, `label`, `jumlah`, `kategori`, `deskripsi` |

Ketentuan data:

- Tanggal disarankan menggunakan format `YYYY-MM-DD`.
- Nominal minimal Rp1.000 dan boleh menggunakan pemisah ribuan.
- Nama kategori harus tersedia pada akun pengguna.
- Data dimulai dari baris kedua; baris pertama digunakan sebagai header.

## Struktur Project

```text
app/
├── Http/Controllers/    # Autentikasi, dashboard, transaksi, kategori, laporan, profil
├── Livewire/            # Komponen interaktif dashboard, transaksi, scan nota, dan chatbot
├── Models/              # Model data aplikasi
├── Services/            # Layanan AI dan konteks keuangan
├── Imports/ dan Exports/ # Pemrosesan template dan impor Excel
resources/
├── views/               # Halaman Blade dan komponen Livewire
├── css/                 # Styling antarmuka
└── js/                  # Skrip dashboard, transaksi, dan scan nota
routes/web.php           # Definisi rute aplikasi
database/migrations/     # Struktur database
```

## Keamanan dan File Rahasia

Beberapa file tidak boleh diunggah, dibagikan, atau dimasukkan ke repository:

- `.env` dan seluruh variannya, kecuali `.env.example`.
- `auth.json`, private key, dan file kredensial lainnya.
- File unggahan pengguna di direktori `storage/`.
- File build dan dependensi seperti `node_modules`, `vendor`, dan `public/build`.

Ketentuan keamanan yang diterapkan:

- `.env` sudah dikecualikan melalui `.gitignore`; repository hanya menyediakan `.env.example` sebagai contoh konfigurasi.
- Kunci API disimpan di sisi server dan tidak ditampilkan pada antarmuka.
- Kata sandi pengguna disimpan dalam bentuk hash.
- Sesi diperbarui setelah login dan dihapus saat logout.
- Data transaksi difilter berdasarkan akun pengguna yang sedang login.
- Impor Excel disimpan dalam satu transaksi database agar tidak terjadi penyimpanan sebagian.
- File unggahan dibatasi berdasarkan jenis dan ukuran.

Jika `.env` atau kunci API tidak sengaja terekspos, segera ganti kunci tersebut melalui penyedia layanan yang digunakan.

## Lisensi

MIT, sesuai deklarasi pada `composer.json`. 
## Pengembang

Medwin Alfredo Silalahi
