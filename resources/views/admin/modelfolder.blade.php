{{-- MODAL SUBFOLDER --}}
<div class="modal fade" id="modalSubfolder">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.simpansubfolder',$folder->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Subfolder</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="text" name="nama_folder" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button class="btn btn-primary">Tambah</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- MODAL FILE --}}
<div class="modal fade" id="modalFile">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('admin.simpanfile',$folder->id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Upload File</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="text" name="nama_file" class="form-control mb-2" required>
          <input type="file" name="file" class="form-control" required>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button class="btn btn-success">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>
