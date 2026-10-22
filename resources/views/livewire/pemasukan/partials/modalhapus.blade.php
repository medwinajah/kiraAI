@foreach ($pemasukan as $data)
<!-- Modal -->
<div class="modal fade" id="deleteusers{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form wire:submit="delete({{$data->id}})" class="modal-content rounded-4">
      <div class="modal-header rounded-top-4 p-2 px-4 border bg-success">
        <h5 class="modal-title fw-5 fw-bold text-white" id="staticBackdropLabel">Kamu yakin?</h5>
        <button type="button" class="btn-close small fw-semibold" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body fw-semibold text-center small mb-0">
        <p class="fw-bold fs-5 mb-0">Yakin mau hapus data ini bro ? </p>
        <p class="fs-5"> <span class="badge bg-success shadow">{{ $data->label }} - {{ $data->jumlah_pemasukan }}</span></p>
      </div>
      <div class="modal-footer border-0 mt-0 d-flex flex-nowrap gap-2 flex-row">
        <button type="button" class="btn btn-outline-secondary small rounded-3 fw-semibold w-50" data-bs-dismiss="modal" ><smal>Batal</smal> <i class="fa-solid fa-xmark"></i></button>   
        <button type="submit" data-bs-dismiss="modal" class="btn btn-danger rounded-3 small fw-semibold w-50" ><smal>Hapus</smal> <i class="fa-solid fa-trash"></i></button>
      
      </div>
    </form>
  </div> 
</div> 
@endforeach