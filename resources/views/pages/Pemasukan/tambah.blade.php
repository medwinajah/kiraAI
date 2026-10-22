@extends('Layouts.app')
@section('title', 'Pemasukan')
@section('link_css')
    @vite(['resources/css/MenuList/pemasukan.css'])
    @vite(['resources/css/MenuList/pemasukanTambah.css'])
@endsection

@section('content')
  
    <div class="container row rounded-3 mt-1 py-3 align-items-center w-100 m-auto">
        <section class="col-lg-8 col-12 border mx-lg-auto py-3 rounded-4 shadow">
            <section class="border-bottom mb-3">
                <a href="{{ route('pemasukan.index') }}" wire:navigate class="text-decoration-none">
                <button class="btn btn-outline-secondary small fw-semibold rounded-4 mb-3"><i class="fa-solid fa-backward"></i> Kembali</button></a>
            </section>
            <h4 class="text-center fw-bold py-3 bg-tambah shadow-sm border rounded-4 text-white mb-4">Tambah Catatan Pemasukan</h4>

            <div class="px-2">
                <form action="{{route('pandapatan.store') }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        {{-- Label Pemasukan --}}
                    <section class="mb-1 col-lg-12 col-sm-12">
                        <label for="label_pemasukan" class="form-label small fw-semibold">Label Pemasukan <sup class="text-danger fs-6">*</sup></label>
                        <div class=" border @error('label')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input type="text" class="form-control p-2 border-0 w-100 focus-ring focus-ring-light"  value="{{old("label") }}" name="label" placeholder="Label Pemasukan" id="label_pemasukan">

                        </div>
                        @error('label')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                     {{-- Kategori Pemasukan --}}
                    <section class="mb-1 col-lg-6 col-sm-12">
                        <label for="kategori_pemasukan" class="form-label small fw-semibold">Kategori Pemasukan <sup class="text-danger fs-6">*</sup></label>
                        <div class="border rounded-3 p-0 d-flex gap-2 @error('kategori')
                            border-danger
                        @enderror">
                        <span class="border-0 d-flex align-items-center p-2 ">
                             <i class="fa-solid fa-bookmark"></i>
                        </span>
                        <select name="kategori" id="kategori" class="form-select w-100 p-2 border-0">
                                <option value="" class="form-select">Pilih Kategori</option>
                                @forelse ($kategori_pemasukan as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori') == $kategori->id?'selected':'' }}>{{$kategori->nama_kategori}}</option>
                                @empty
                                <option value="">$Kategori Masih Kosong</option>
                                @endforelse
                           </select>
                        </div>
                         @error('kategori')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                     {{-- Jumlah Pemasukan --}}
                    @livewire('pemasukan.partials.formnumberlive')

                     {{-- Sumber Pemasukan --}}
                    <section class="mb-1 col-lg-6 col-sm-12">
                        <label for="sumber_pemasukan" class="form-label small fw-semibold">Sumber Pemasukan </label>
                        <div class=" border @error('sumber_pemasukan')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input type="text" class="form-control p-2 border-0 w-100 focus-ring focus-ring-light" name="sumber_pemasukan" placeholder="Contoh: Orang tua" id="sumber_pemasukan">
                        </div>
                        @error('sumber_pemasukan')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                    {{-- Tanggal Pemasukan --}}
                    <section class="mb-1 col-lg-6 col-sm-12">
                        <label for="tanggal_pemasukan" class="form-label small fw-semibold">Tanggal Pemasukan <sup class="text-danger fs-6">*</sup></label>
                        <div class=" border @error('tanggal_pemasukan')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <span class="border-0 d-flex gap-2 p-2">
                             <i class="fa-solid fa-bookmark"></i>         
                        </span>
                        <input type="date" name="tanggal_pemasukan" class="form-control p-2 border-0 w-100 focus-ring focus-ring-light"  id="tanggal_pemasukan">
                        </div>
                         @error('tanggal_pemasukan')
                            <span class="small text-danger">{{$message}}</span>
                        @enderror
                    </section>

                    {{-- Deskripsi Pemasukan --}}
                    <section class="mb-1 col-lg-12 col-sm-12">
                        <label for="deskripsi_pemasukan" class="form-label small fw-semibold">Deskripsi Pemasukan</label>
                        <div class=" border @error('deskripsi_pemasukan')
                            border-danger
                        @enderror rounded-3 p-0 d-flex align-items-center">
                        <textarea name="deskripsi_pemasukan" id="" cols="30" rows="2" placeholder="Catat deskripsi pemasukan" class="form-control"></textarea>
                        </div>
                        @error('deskripsi_pemasukan')
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
