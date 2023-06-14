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
      <button class="btn btn-primary refresh"><i class="fas fa-sync-alt"></i> Refresh</button>
    </div>
    <div class="card-body">

      <div class="form-group">
        <label for="tanggal">Tanggal</label>
        <input type="date" class="form-control" id="tanggal" value="<?= date('Y-m-d'); ?>">
      </div>

      <div class="my-3 text-center font-weight-bold">
        <h4>Pemberian pakan pada <span id="tanggal_pakan" class="text-primary"></span></h4>
      </div>

      <table id="table" class="table table-sm table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Presentase Pakan</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  function tanggal_pakan() {
    const tanggal = convertDate($('#tanggal').val(), 'date');
    $('#tanggal_pakan').html(tanggal);
  }

  $(document).ready(function() {
    tanggal_pakan();

    table = $('#table').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: "<?= base_url('/pakan/listData'); ?>",
        data: function(d) {
          d.tanggal = $('#tanggal').val();
        }
      },
      order: [],
      columns: [{
          data: 'no'
        },
        {
          data: 'tanggal'
        },
        {
          data: 'jam'
        },
        {
          data: 'presentase_pakan',
          orderable: false
        },
      ]
    });

    $('#tanggal').change(function(event) {
      tanggal_pakan();
      table.ajax.reload();
    });

    $('.refresh').click(function(e) {
      e.preventDefault();
      table.ajax.reload();
    });
  });
</script>
<?= $this->endSection('main'); ?>