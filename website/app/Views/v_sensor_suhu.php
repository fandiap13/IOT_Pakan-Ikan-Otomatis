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
      <table id="sensor_suhu" class="table table-sm table-bordered table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Data Sensor (°C)</th>
            <th>Status Suhu</th>
            <th>Penghangat</th>
          </tr>
        </thead>
        <tbody>

        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    var table = $('#sensor_suhu').DataTable({
      processing: true,
      serverSide: true,
      ajax: '<?= base_url('sensor/list_sensor_suhu'); ?>',
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
          data: 'data_sensor',
          orderable: false
        },
        {
          data: 'status_suhu',
          orderable: false
        },
        {
          data: 'penghangat_akuarium',
          orderable: false
        },
      ]
    });

    $('.refresh').click(function(e) {
      e.preventDefault();
      table.ajax.reload();
    });

    setInterval(() => {
      table.ajax.reload();
    }, 10000);
  });
</script>

<?= $this->endSection('main'); ?>