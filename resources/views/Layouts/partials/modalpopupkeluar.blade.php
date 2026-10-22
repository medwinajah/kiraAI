<!-- Modal -->
<div class="modal fade" id="popupkeluar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="popupkeluarLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form action="{{ route('logout_sistem') }}"
    method="POST"
    class="modal-content rounded-4">
    @csrf
    @method('DELETE')
      <div class="modal-header bg-success">
        <h1 class="modal-title fs-5 text-white fw-bold" id="popupkeluarLabel">Kamu Yakin ?</h1>
        <button type="button" class="btn-close border" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="fw-bold fs-4 mb-1">Hallo <span class="text-success">{{Auth::user()->name}}</span></p>
        Apakah kamu yakin ingin keluar dari sistem ? 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">Batal <i class="fa-solid fa-xmark"></i></button>
        <button type="submit" class="btn btn-danger fw-bold">Keluar <i class="fa-solid fa-right-to-bracket"></i></button>
      </div>
    </form>
  </div>
</div>