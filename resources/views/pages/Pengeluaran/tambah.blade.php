@extends('Layouts.app')
@section('title', 'Pengeluaran')
@section('link_css')
    @vite(['resources/css/MenuList/pengeluaran.css'])
     @vite(['resources/css/MenuList/pengeluaranTambah.css'])
@endsection

@section('content')
  
    <div class="container row rounded-3 mt-1 py-3 align-items-center w-100 m-auto">
        <section class="col-lg-8 col-12 border mx-lg-auto py-3 rounded-4 shadow">
            <section class="border-bottom mb-3">
                <a href="{{ route('pengeluaran.index') }}" wire:navigate class="text-decoration-none">
                <button class="btn btn-outline-secondary small fw-semibold rounded-4 mb-3"><i class="fa-solid fa-backward"></i> Kembali</button></a>
            </section>
            <h4 class="text-center fw-bold py-3 bg-tambah shadow-sm border rounded-4 text-white mb-4">Tambah Catatan pengeluaran</h4>

            <div class="px-2">
                <form action="{{route('pengeluaran.store') }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        {{-- Label pengeluaran --}}
                    <section class="mb-1 col-lg-12 col-sm-12">
                        <label for="label_pengeluaran" class="form-label small fw-semibold">Label pengeluaran <sup class="text-danger fs-6">*</sup></label>
                        <div class=" border @error('label')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input type="text" class="form-control p-2 border-0 w-100 focus-ring focus-ring-light"  value="{{old("label") }}" name="label" placeholder="Label pengeluaran" id="label_pengeluaran">

                        </div>
                        @error('label')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                     {{-- Kategori pengeluaran --}}
                    <section class="mb-1 col-lg-6 col-sm-12">
                        <label for="kategori_pengeluaran" class="form-label small fw-semibold">Kategori pengeluaran <sup class="text-danger fs-6">*</sup></label>
                        <div class="border rounded-3 p-0 d-flex gap-2 @error('kategori')
                            border-danger
                        @enderror">
                        <span class="border-0 d-flex align-items-center p-2 ">
                             <i class="fa-solid fa-bookmark"></i>
                        </span>
                        <select name="kategori" id="kategori" class="form-select w-100 p-2 border-0">
                                <option value="" class="form-select">Pilih Kategori</option>
                                @forelse ($kategori_pengeluaran as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori') == $kategori->id?'selected':'' }}>{{$kategori->nama_ketegori}}</option>
                                @empty
                                <option value="" disabled>Kategori Masih Kosong</option>
                                @endforelse
                           </select>
                        </div>
                         @error('kategori')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                     {{-- Jumlah pengeluaran --}}
                    @livewire('pengeluaran.partials.formnumber')

                     {{-- Sumber pengeluaran
                    <section class="mb-1 col-lg-6 col-sm-12">
                        <label for="sumber_pengeluaran" class="form-label small fw-semibold">Sumber pengeluaran </label>
                        <div class=" border @error('sumber_pengeluaran')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input type="text" class="form-control p-2 border-0 w-100 focus-ring focus-ring-light" name="sumber_pengeluaran" placeholder="Contoh: Orang tua" id="sumber_pengeluaran">
                        </div>
                        @error('sumber_pengeluaran')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section> --}}

                    {{-- Tanggal pengeluaran --}}
                    <section class="mb-1 col-lg-6 col-sm-12">
                        <label for="tanggal_pengeluaran" class="form-label small fw-semibold">Tanggal pengeluaran <sup class="text-danger fs-6">*</sup></label>
                        <div class=" border @error('tanggal_pengeluaran')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input type="date" value="{{ now()->format('Y-m-d') }}" name="tanggal_pengeluaran" class="form-control p-2 border-0 w-100 focus-ring focus-ring-light"  id="tanggal_pengeluaran">
                        </div>
                         @error('tanggal_pengeluaran')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                    {{-- Deskripsi pengeluaran --}}
                    <section class="mb-1 col-lg-12 col-sm-12">
                        <label for="deskripsi_pengeluaran" class="form-label small fw-semibold">Deskripsi pengeluaran</label>
                        <div class=" border @error('deskripsi_pengeluaran')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <textarea name="deskripsi_pengeluaran" id="" cols="30" rows="2" placeholder="Catat deskripsi pengeluaran" class="form-control"></textarea>
                        </div>
                        @error('deskripsi_pengeluaran')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                    {{-- Button Aksi --}}
                    <section class="mb-1 col-lg-12 col-sm-12 mt-3">
                        <div class="d-flex justify-content-between align-items-center gap-2"> 
                            <button class="btn btn-outline-danger rounded w-50 py-2 rounded-4 fw-semibold" type="reset"> <i class="fa-solid fa-rotate-left"></i> Batal</button>
                             <button type="submit" class="btn btn-tambah rounded w-50 py-2 rounded-4 fw-semibold text-white" type="reset"> Tambah <i class="fa-solid fa-plus-circle fs-5"></i> </button>
                        </div>
                    </section>
                    </div>
                </form>
            </div>
        </section>
    </div>
    
@endsection
