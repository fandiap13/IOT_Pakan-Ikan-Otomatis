<div class="modal fade" id="modaledit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Jenis Ikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open(base_url("setting/edit_kategori_ikan"), ['class' => 'form_edit_kategori_ikan']); ?>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $kategori['id']; ?>">
        <div class="form-group">
          <label for="kategori">Jenis Ikan</label>
          <input type="text" name="kategori" class="form-control" value="<?= $kategori['kategori']; ?>" required>
        </div>
        <div class="form-group">
          <label for="suhu_min">Suhu Minimal</label>
          <input type="number" step="0.01" name="suhu_min" class="form-control" value="<?= $kategori['suhu_min']; ?>" required>
        </div>
        <div class="form-group">
          <label for="suhu_max">Suhu Maksimal</label>
          <input type="number" step="0.01" name="suhu_max" class="form-control" value="<?= $kategori['suhu_max']; ?>" required>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>
  $("#modaledit").on('shown.bs.modal', function() {
    $('input[name=kategori]').focus();
  });

  $(".form_edit_kategori_ikan").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      success: function(response) {
        if (response.success) Swal.fire("Sukses", response.success, 'success').then(() => window.location.reload());
        if (response.error) Swal.fire("Error", response.error, 'error').then(() => window.location.reload());
      },
      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
      }
    });
  });
</script>