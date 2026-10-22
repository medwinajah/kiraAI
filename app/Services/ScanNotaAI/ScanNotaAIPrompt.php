<?php
namespace App\Services\ScanNotaAI;

class ScanNotaAIPrompt{
    
public function propmtAI(){
<<<'PROMPT'
Baca nota belanja pada gambar dengan teliti.

Aturan:
1. Setiap baris barang menjadi satu item tersendiri.
2. amount adalah total harga baris barang, bukan total seluruh nota.
3. Jangan masukkan subtotal, total pembayaran, pajak umum,
   uang tunai, atau kembalian sebagai barang.
4. Jangan mengarang nama barang, harga, maupun tanggal.
5. Gunakan null jika harga atau tanggal tidak terbaca.
6. Tanggal harus berformat YYYY-MM-DD.
7. Harga harus berupa angka dalam rupiah tanpa pemisah ribuan.
8. Jika tidak ada barang yang dikenali, gunakan items kosong.
PROMPT;
}
}