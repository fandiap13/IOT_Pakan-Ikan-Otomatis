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
  <div class="card">
    <div class="card-header">
      <button class="btn btn-primary" onclick="window.location.reload();"><i class="fas fa-sync-alt"></i> Refresh</button>

      <div class="card-tools">
        <button class="btn btn-primary tambah"><i class="fa fa-plus"></i> Tambah Jadwal</button>
      </div>
    </div>
    <div class="card-body">
      <table id="example2" class="table table-sm table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Jam</th>
            <th>Terakhir Aktif</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          foreach ($jadwal as $key => $value) : ?>
            <tr>
              <td><?= $no++; ?>.</td>
              <td><?= date('H:i', strtotime($value['jam'])); ?></td>
              <td class="terakhir_aktif"><?= $value['terakhir_aktif'] != null ? date('d F Y', strtotime($value['terakhir_aktif'])) : "Belum aktif"; ?></td>
              <td class="text-center">
                <button class="btn btn-sm btn-primary" onclick="edit('<?= $value['id']; ?>');"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" onclick="hapus('<?= $value['id']; ?>');"><i class="fa fa-trash-alt"></i> Hapus</button>
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
        <h4 class="modal-title">Tambah Penjadwalan Pakan Ikan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open(base_url("penjadwalan/tambah_penjadwalan"), ['class' => 'formsimpan']); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="jam">Jam</label>
          <input type="time" name="jam" class="form-control" id="jam">
          <div class="invalid-feedback errorJam">
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
      url: "<?= base_url('penjadwalan/modaleditjadwal'); ?>",
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

  function terakhir_aktif(tanggal) {
    const tgl = convertDate(tanggal, "date");
    $(".terakhir_aktif").html(tgl);
  }

  function hapus(id) {
    Swal.fire({
      title: 'Hapus?',
      text: "Apakah anda yakin menghapus jadwal ini",
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
          url: "<?= base_url('penjadwalan/hapus'); ?>",
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
    });
  }

  $(".tutuptambah").click(function(e) {
    e.preventDefault();
    $("#jam").val("").removeClass('is-invalid');
    $('.errorJam').html("");
  });

  $('.tambah').click(function(e) {
    e.preventDefault();
    $('#modaltambah').modal('show');
  });

  $("#modaltambah").on('shown.bs.modal', function() {
    $('input[name=kategori]').focus();
  });

  $(".formsimpan").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function() {
        $(".btnSimpan").html(`<i class="fa fa-spin fa-spinner"></i>`);
      },
      complete: function() {
        $(".btnSimpan").html(`<i class="fa fa-save"></i> Simpan Perubahan`);
      },
      success: function(response) {
        if (response.success) {
          Swal.fire("Sukses", response.success, 'success').then(() => window.location.reload());
          // $('modaltambah').modal('hide');
          // $(':input', '.formsimpan')
          //   .not(':button, :submit, :reset, :hidden')
          //   .val('')
          //   .removeAttr('checked')
          //   .removeAttr('selected');
        }

        if (response.errors) {
          const errors = response.errors;
          if (errors.jam) {
            $("#jam").addClass('is-invalid');
            $('.errorJam').html(errors.jam);
          } else {
            $("#jam").removeClass('is-invalid');
            $('.errorJam').html("");
          }
        }
      },
      error: function(request, status, error) {
        alert(request.responseText);
      }
    });
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
</script>

<?= $this->endSection('main'); ?>