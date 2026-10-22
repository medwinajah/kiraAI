# Instruksi AI Agent — README.md Profesional

## 1. Tujuan

Buat atau perbarui file `README.md` pada direktori utama project agar menjadi dokumentasi repository yang profesional, ringkas, dan mudah dipahami oleh developer lain.

README harus menjelaskan gambaran project dan cara menjalankannya tanpa memuat detail implementasi yang tidak diperlukan.

## 2. Pahami Project Terlebih Dahulu

Sebelum membuat README:

1. Periksa gambaran umum dan struktur utama project.
2. Baca file konfigurasi penting, seperti `composer.json`, `package.json`, dan `.env.example` jika tersedia.
3. Identifikasi teknologi utama dan fitur yang benar-benar sudah diimplementasikan.
4. Pastikan petunjuk instalasi sesuai dengan konfigurasi project yang sebenarnya.

Gunakan informasi dari repository sebagai sumber utama. Jangan mengarang fitur, versi teknologi, atau informasi lainnya.

## 3. Struktur README

Gunakan bagian-bagian berikut sesuai kebutuhan project:

1. **Nama Project** — nama aplikasi yang sebenarnya.
2. **Deskripsi** — penjelasan singkat mengenai tujuan dan fungsi aplikasi.
3. **Fitur Utama** — daftar fitur penting yang benar-benar tersedia.
4. **Teknologi yang Digunakan** — framework, bahasa pemrograman, database, dan teknologi pendukung utama.
5. **Preview Aplikasi** — tampilkan screenshot jika tersedia.
6. **Persyaratan dan Instalasi** — kebutuhan sistem serta langkah-langkah menjalankan project.
7. **Konfigurasi Environment** — petunjuk konfigurasi `.env` dan layanan eksternal jika diperlukan.
8. **Cara Menjalankan Aplikasi** — perintah penting untuk menjalankan project.
9. **Informasi Tambahan** — lisensi atau informasi pengembang hanya jika tersedia dan relevan.

Bagian yang tidak diperlukan boleh dihilangkan. Sesuaikan struktur README dengan ukuran dan kompleksitas project.

## 4. Batas Kedalaman Dokumentasi

README harus informatif, tetapi tidak perlu menjadi dokumentasi teknis yang sangat terperinci.

- Jangan mencantumkan seluruh struktur folder atau daftar setiap file dalam project.
- Jangan menguraikan semua nama class, controller, model, route, komponen, atau fungsi program.
- Jangan menjelaskan skema database, seluruh tabel, kolom, relasi, atau migration secara terperinci.
- Jangan memasukkan kode sumber lengkap maupun menjelaskan implementasi setiap fitur.
- Jangan membuat bagian arsitektur, API, pemecahan masalah, roadmap, atau kontribusi jika tidak benar-benar diperlukan.
- Jelaskan fitur dari sudut pandang fungsi dan manfaatnya bagi pengguna, bukan dari detail kode internal.
- Utamakan informasi yang membantu developer memahami project dan menjalankannya.

Penjelasan teknis hanya perlu ditambahkan apabila penting untuk instalasi, konfigurasi, atau pemahaman dasar aplikasi.

## 5. Standar Penulisan

- Gunakan bahasa Indonesia yang jelas, profesional, dan konsisten.
- Gunakan Markdown dengan heading yang teratur dan code block untuk perintah terminal.
- Buat deskripsi dan daftar fitur secara singkat, tanpa pengulangan.
- Pastikan instruksi instalasi dapat diikuti berdasarkan kondisi project sebenarnya.
- Jangan menambahkan badge, statistik, tautan demo, screenshot, atau klaim yang tidak dapat diverifikasi.
- Jangan memanjangkan README hanya untuk memenuhi jumlah bagian tertentu.

Targetkan README yang dapat dipahami developer baru dalam beberapa menit.

## 6. Keamanan

- Jangan pernah memasukkan API key, token, password, secret aplikasi, atau kredensial asli ke README.
- Gunakan `.env.example` sebagai referensi konfigurasi jika tersedia.
- Jelaskan nama variabel environment yang dibutuhkan tanpa menampilkan nilai rahasianya.
- Jangan mengunggah atau menyalin isi `.env` ke dalam dokumentasi.

## 7. Batasan Perubahan

Tugas ini hanya untuk membuat atau memperbarui file `README.md` di direktori utama project.

- Jangan mengubah kode aplikasi maupun konfigurasi project.
- Jangan menginstal package baru atau mengubah database.
- Jangan membuat file dokumentasi tambahan.
- Jangan mengubah implementasi fitur yang sudah ada.

File project lainnya boleh dibaca untuk memahami aplikasi, tetapi tidak boleh diubah.

## 8. Pemeriksaan Akhir

Sebelum menyelesaikan tugas:

1. Pastikan README ringkas, rapi, dan mudah dibaca.
2. Pastikan deskripsi, fitur, teknologi, dan instruksi instalasi sesuai dengan project.
3. Pastikan tidak ada informasi rahasia atau klaim yang dibuat-buat.
4. Pastikan hanya file `README.md` yang diubah.

Setelah selesai, berikan ringkasan singkat mengenai README yang dibuat.

**Prioritas utama:** buat README yang profesional, informatif, dan secukupnya. Dokumentasikan hal-hal yang penting bagi developer, bukan seluruh isi dan implementasi internal project.