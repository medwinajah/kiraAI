@foreach ($Pengeluaran as $data)
<!-- Modal -->
<div class="modal fade" id="deleteusers{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form wire:submit="delete({{$data->id}})" class="modal-content">
      <div class="modal-header p-2  px-4 border">
        <h5 class="modal-title fw-5 fw-bold" id="staticBackdropLabel">Kamu yakin?</h5>
        <button type="button" class="btn-close small fw-semibold" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body fw-semibold text-center small mb-0">
        Yakin mau hapus data ini bro ? 
        <p> <span class="badge bg-warning shadow">{{ $data->label }} - {{ $data->jumlah_pengeluaran }}</span></p>
      </div>
      <div class="modal-footer border-0 mt-0 d-flex flex-nowrap gap-2 flex-row">
        <button type="button" class="btn btn-outline-secondary small rounded-3 fw-semibold w-50" data-bs-dismiss="modal" ><smal>Batal</smal></button>   
        <button type="submit" data-bs-dismiss="modal" class="btn btn-danger rounded-3 small fw-semibold w-50" ><smal>Hapus</smal></button>
      
      </div>
    </form>
  </div>
</div> 
@endforeach