<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\Tabungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $jumlah_pemasukan = $this->jumlahPemasukan();
        $jumlah_pengeluaran = $this->jumlahPengeluaran();
        $jumlah_tabungan = $this->jumlahTabungan();
        $saldo_akhir = $this->jumlaSaldoAkhir();
        return view('pages.Dashboard.index',
        [
            'jumlah_pemasukan'=>$jumlah_pemasukan,
            'jumlah_pengeluaran'=>$jumlah_pengeluaran,
            'jumlah_tabungan'=>$jumlah_tabungan,
            'saldo_akhir'=>$saldo_akhir,
        ]);
    }

    public function jumlahPemasukan(){
        return Pemasukan::where('id_users',Auth::user()->id)->sum('jumlah_pemasukan');
    }

    public function jumlahPengeluaran(){
        return Pengeluaran::where('id_users',Auth::user()->id)->sum('jumlah_pengeluaran');
    }

    public function jumlahTabungan(){
        return Tabungan::where('id_users',Auth::user()->id)->sum('total_tabungan');
    }

    public function jumlaSaldoAkhir(){
        $total_pemasukan =  $this->jumlahPemasukan();
        $total_pengeluaran =  $this->jumlahPengeluaran();
        $total_tabungan =  $this->jumlahTabungan();

        // Hitung Totalo Saldo Akhir
        $saldo_akhir = (int) $total_pemasukan-($total_pengeluaran + $total_tabungan);

        return $saldo_akhir;
    }

}
