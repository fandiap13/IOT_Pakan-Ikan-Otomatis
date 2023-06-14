<div class="modal fade" id="modaledit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Penjadwalan Pakan Ikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open(base_url("penjadwalan/update"), ['class' => 'formedit']); ?>
      <div class="modal-body">
        <input type="hidden" name="id" value="<?= $jadwal['id']; ?>">
        <div class="form-group">
          <label for="jam">Jam</label>
          <input type="time" name="jam" class="form-control" id="jamEdit" value="<?= $jadwal['jam']; ?>">
          <div class="invalid-feedback errorEditJam">
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary btnEdit"><i class="fa fa-save"></i> Simpan Perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>
  $("#modaledit").on('shown.bs.modal', function() {
    $('input[name=jam]').focus();
  });

  $(".formedit").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function() {
        $(".btnEdit").html(`<i class="fa fa-spin fa-spinner"></i>`);
      },
      complete: function() {
        $(".btnEdit").html(`<i class="fa fa-save"></i> Simpan Perubahan`);
      },
      success: function(response) {
        if (response.success) Swal.fire("Sukses", response.success, 'success').then(() => window.location.reload());
        if (response.error) Swal.fire("Error", response.error, 'error').then(() => window.location.reload());

        if (response.errors) {
          const errors = response.errors;
          if (errors.jam) {
            $("#jamEdit").addClass('is-invalid');
            $('.errorEditJam').html(errors.jam);
          } else {
            $("#jamEdit").removeClass('is-invalid');
            $('.errorEditJam').html("");
          }
        }
      },
      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
      }
    });
  });
</script>