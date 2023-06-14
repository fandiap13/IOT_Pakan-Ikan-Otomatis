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

<div class="col-lg-4">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Panjang Wadah Pakan</h5>
    </div>
    <div class="card-body">
      <?= form_open("", ['class' => "form_panjang"]); ?>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="panjang_wadah">Panjang Wadah Pakan (cm)</label>
          <input type="number" step="0.01" class="form-control" name="panjang_wadah" value="<?= $sistem['panjang_wadah']; ?>" required autofocus>
        </div>
        <div class="form-group col-md-6">
          <label for="jarak_sensor">Jarak Wadah ke Sensor (cm)</label>
          <input type="number" step="0.01" class="form-control" value="<?= $sistem['jarak_sensor']; ?>" name="jarak_sensor" required>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-block btn-sm btn-primary"><i class="fa fa-save"></i> Simpan perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-header">
      <h5 class="card-title">Pilih jenis ikan</h5>
    </div>
    <div class="card-body">
      <?= form_open(base_url('setting/jenis_ikan'), ['class' => "form_jenis_ikan"]); ?>
      <div class="form-group">
        <select name="jenis_ikan" class="form-control">
          <?php foreach ($kategori_ikan as $key => $value) : ?>
            <option value="<?= $value['id']; ?>" <?= $sistem['id_kategori_ikan'] == $value['id'] ? "selected" : ""; ?>><?= $value['kategori']; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-block btn-sm btn-primary"><i class="fa fa-save"></i> Simpan perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>

  <div class="card mt-3">
    <div class="card-header">
      <h5 class="card-title">Durasi pemberian pakan (detik)</h5>
    </div>
    <div class="card-body">
      <?= form_open(base_url("setting/durasi_pakan"), ['class' => 'formdurasi']); ?>
      <div class="form-group">
        <input type="number" step="0.1" name="durasi_pakan" class="form-control" value="<?= $sistem['durasi_pakan']; ?>">
      </div>
      <div class="form-group">
        <button class="btn btn-sm btn-block btn-primary"><i class="fa fa-save"></i> Simpan perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>

</div>

<div class="col-lg-8">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title">Jenis Ikan</h5>

      <div class="card-tools">
        <button class="btn btn-primary tambahJenis"><i class="fa fa-plus"></i> Tambah Jenis Ikan</button>
      </div>
    </div>
    <div class="card-body">
      <table id="example2" class="table table-sm table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Jenis Ikan</th>
            <th>Suhu Min (°C)</th>
            <th>Suhu Max (°C)</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          foreach ($kategori_ikan as $key => $k) :
          ?>
            <tr>
              <td><?= $i++; ?>.</td>
              <td><?= $k['kategori']; ?></td>
              <td><?= $k['suhu_min']; ?></td>
              <td><?= $k['suhu_max']; ?></td>
              <td class="text-center">
                <button class="btn btn-sm btn-primary" title="Edit" onclick="edit('<?= $k['id']; ?>')"><i class="fa fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" title="Hapus" onclick="hapus('<?= $k['id']; ?>')"><i class="fa fa-trash-alt"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="modaltambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Tambah Jenis Ikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open(base_url("setting/tambah_kategori_ikan")); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="kategori">Jenis Ikan</label>
          <input type="text" name="kategori" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="suhu_min">Suhu Minimal</label>
          <input type="number" step="0.01" name="suhu_min" class="form-control" required>
        </div>
        <div class="form-group">
          <label for="suhu_max">Suhu Maksimal</label>
          <input type="number" step="0.01" name="suhu_max" class="form-control" required>
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

<div class="viewmodal" style="display: none;">
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

  function edit(id) {
    $.ajax({
      type: "post",
      url: "<?= base_url('setting/modaleditkategori'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $(".viewmodal").html(response.data).show();
          $("#modaledit").modal("show");
        }
      },
      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
      }
    });
  }

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: "Apakah anda yakin menghapus data ini",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: "batal",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= base_url('setting/hapus_kategori'); ?>",
          data: {
            id: id
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
            }
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            alert(err.Message);
          }
        });
      }
    })
  }

  $('.tambahJenis').click(function(e) {
    e.preventDefault();
    $('#modaltambah').modal('show');
  });

  $("#modaltambah").on('shown.bs.modal', function() {
    $('input[name=kategori]').focus();
  });

  $('.form_panjang').submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Yakin?',
      text: "Apakah anda ingin mengubah panjang wadah!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, ubah!',
      cancelButtonText: 'batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // menjalankan fungsi submit form tanpa memperhatikan event listener
        $(this).unbind('submit').submit();
      }
    });
  });

  $('.form_jenis_ikan').submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Yakin?',
      text: "Apakah anda ingin mengubah jenis ikan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, ubah!',
      cancelButtonText: 'batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // menjalankan fungsi submit form tanpa memperhatikan event listener
        $(this).unbind('submit').submit();
      }
    });
  });

  $(".formdurasi").submit(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Yakin?',
      text: "Apakah anda ingin mengubah durasi pakan!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, ubah!',
      cancelButtonText: 'batal'
    }).then((result) => {
      if (result.isConfirmed) {
        // menjalankan fungsi submit form tanpa memperhatikan event listener
        $(this).unbind('submit').submit();
      }
    });
  });
</script>

<?= $this->endSection('main'); ?>