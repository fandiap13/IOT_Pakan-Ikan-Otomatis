<?= $this->extend('templates/template_user'); ?>

<?= $this->section('main'); ?>

<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<script src="<?= base_url(); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>


<div class="col-lg-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <button class="btn btn-primary" onclick="window.location.reload();"><i class="fas fa-sync-alt"></i> Refresh</button>

      <div class="card-tools">
        <button class="btn btn-primary tambah"><i class="fa fa-plus"></i> Tambah User</button>
      </div>

    </div>
    <div class="card-body">
      <table id="example2" class="table table-sm table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>E-mail</th>
            <th>Nama</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          foreach ($users as $key => $u) :
          ?>
            <tr>
              <td><?= $i++; ?>.</td>
              <td><?= $u['email']; ?></td>
              <td><?= $u['nama']; ?></td>
              <td class="text-center">
                <button class="btn btn-sm btn-primary" title="Edit" onclick="edit('<?= $u['id']; ?>', '<?= $u['email']; ?>', '<?= $u['nama']; ?>')"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" title="Hapus" onclick="hapus('<?= $u['id']; ?>')"><i class="fa fa-trash-alt"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="modaluser">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Penjadwalan Pakan Ikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open("", ['class' => 'formsimpan']); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="email">E-mail</label>
          <input type="text" name="email" class="form-control" id="email">
          <div class="invalid-feedback erroremail">
          </div>
        </div>
        <div class="form-group">
          <label for="nama">Nama</label>
          <input type="text" name="nama" class="form-control" id="nama">
          <div class="invalid-feedback errornama">
          </div>
        </div>
        <div class="form-group row">
          <div class="col-md-6">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" id="password">
            <div class="invalid-feedback errorpassword">
            </div>
          </div>
          <div class="col-md-6">
            <label for="retype_password">Retype Password</label>
            <input type="password" name="retype_password" class="form-control" id="retype_password">
            <div class="invalid-feedback errorretype_password">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default tutuptambah" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary btnSimpan"><i class="fa fa-save"></i> Simpan Perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script>
  $(function() {
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });

  function clearValidationError() {
    $("#email").removeClass("is-invalid");
    $(".erroremail").html("");
    $("#password").removeClass("is-invalid");
    $(".errorpassword").html("");
    $("#retype_password").removeClass("is-invalid");
    $(".errorretype_password").html("");
  }

  function edit(id, email, nama) {
    clearValidationError();
    $("#modaluser").modal("show");
    $("#email").val(email);
    $("#nama").val(nama);
    $("#password").val("");
    $("#retype_password").val("");
    $(".btnSimpan").removeAttr("disabled").html(`<i class="fa fa-save"></i> Simpan Perubahan`);
    $(".formsimpan").attr("action", "<?= base_url("users/edit/"); ?>" + id);
  }

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: "Apakah anda yakin menghapus user ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'batal',
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = `<?= base_url('users/hapus/'); ?>${id}`;
      }
    });
  }

  $(".tambah").click(function(e) {
    e.preventDefault();
    clearValidationError();
    $("#modaluser").modal("show");
    $("#email").val("");
    $("#nama").val("");
    $("#password").val("");
    $("#retype_password").val("");
    $(".btnSimpan").removeAttr("disabled").html(`<i class="fa fa-save"></i> Simpan Perubahan`);
    $(".formsimpan").attr("action", "<?= base_url("users/tambah"); ?>");
  });

  $(".formsimpan").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "POST",
      url: $(this).attr("action"),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function() {
        $(".btnSimpan").attr("disabled", true).html(`<i class="fa fa-spin fa-spinner"></i>`);
      },
      complete: function() {
        $(".btnSimpan").removeAttr("disabled").html(`<i class="fa fa-save"></i> Simpan Perubahan`);
      },
      success: function(response) {
        // console.log(response);
        if (response.success) {
          Swal.fire("Sukses", response.success, 'success').then(() => window.location.reload());
        }

        if (response.error) {
          Swal.fire("Error", response.error, 'error').then(() => window.location.reload());
        }

        if (response.errors) {
          if (response.errors.email) {
            $("#email").addClass("is-invalid");
            $(".erroremail").html(response.errors.email);
          } else {
            $("#email").removeClass("is-invalid");
            $(".erroremail").html("");
          }
          if (response.errors.nama) {
            $("#nama").addClass("is-invalid");
            $(".errornama").html(response.errors.nama);
          } else {
            $("#nama").removeClass("is-invalid");
            $(".errornama").html("");
          }
          if (response.errors.password) {
            $("#password").addClass("is-invalid");
            $(".errorpassword").html(response.errors.password);
          } else {
            $("#password").removeClass("is-invalid");
            $(".errorpassword").html("");
          }
          if (response.errors.retype_password) {
            $("#retype_password").addClass("is-invalid");
            $(".errorretype_password").html(response.errors.retype_password);
          } else {
            $("#retype_password").removeClass("is-invalid");
            $(".errorretype_password").html("");
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

<?= $this->endSection('main'); ?>