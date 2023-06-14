<?php

$uri = service('uri');
$url1 = $uri->getSegment(1);

try {
  $url2 = !empty($uri->getSegment(2)) ? $uri->getSegment(2) : null;
} catch (\Throwable $th) {
  $url2 = null;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AIKAN | <?= $title; ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Sweetalert -->
  <script src="<?= base_url(); ?>/package/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="<?= base_url(); ?>/package/dist/sweetalert2.min.css">

</head>


<body class="hold-transition sidebar-mini">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="<?= base_url(); ?>/#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <button type="button" class="btn btn-danger keluar"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="<?= base_url(); ?>" class="brand-link">
        <img src="<?= base_url(); ?>/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">AIKAN</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image text-white h4">
            <i class="fa fa-user nav-icon ml-1"></i>
          </div>
          <div class="info">
            <a href="<?= base_url(); ?>/#" class="d-block"><?= session('LoginUser')['nama']; ?></a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="<?= site_url('dashboard'); ?>" class="nav-link <?= $url1 == 'dashboard' || $url1 == "" ? "active" : ""; ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-header">USERS</li>
            <li class="nav-item">
              <a href="<?= site_url('users'); ?>" class="nav-link <?= $url1 == 'users' ? "active" : ""; ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>
                  Data User
                </p>
              </a>
            </li>

            <li class="nav-header">CONTROL</li>

            <li class="nav-item">
              <a href="<?= site_url('monitoring'); ?>" class="nav-link <?= $url1 == 'monitoring' ? "active" : ""; ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Monitoring
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= site_url('penjadwalan'); ?>" class="nav-link <?= $url1 == 'penjadwalan' ? "active" : ""; ?>">
                <i class="nav-icon fas fa-clock"></i>
                <p>
                  Penjadwalan Pakan
                </p>
              </a>
            </li>

            <li class="nav-header">DATA</li>

            <li class="nav-item">
              <a href="<?= site_url('pakan'); ?>" class="nav-link <?= $url1 == 'pakan' ? "active" : ""; ?>">
                <i class=" nav-icon fas fa-fish"></i>
                <p>
                  Data Pemberian Pakan
                </p>
              </a>
            </li>
            <li class="nav-item <?= $url1 == 'sensor' ? "menu-open" : ""; ?>">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                  Data Sensor
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                  <a href="<?= site_url('sensor/sensor_jarak'); ?>" class="nav-link <?= $url2 == 'sensor_jarak' ? "active" : ""; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sensor Jarak</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= site_url('sensor/sensor_suhu'); ?>" class="nav-link <?= $url2 == 'sensor_suhu' ? "active" : ""; ?>">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sensor Suhu</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('setting'); ?>" class="nav-link <?= $url1 == 'setting' ? "active" : ""; ?>">
                <i class=" nav-icon fas fa-cogs"></i>
                <p>
                  Setting
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="nav-link keluar">
                <i class="nav-icon fa fa-sign-out-alt"></i>
                <p>
                  Logout
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $title; ?> <?= $subtitle ? "| " . $subtitle : ""; ?> </h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href=""><?= $title; ?></a></li>
                <?php if ($subtitle !== "") : ?>
                  <li class="breadcrumb-item active"><?= $subtitle; ?></li>
                <?php endif; ?>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">

            <?= $this->renderSection('main'); ?>

          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
      <div class="p-3">
        <h5>Title</h5>
        <p>Sidebar content</p>
      </div>
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">

      <!-- Default to the left -->
      <strong>Copyright &copy; <?= date('Y'); ?> <a href="<?= base_url(); ?>">AIKAN</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>

  <script>
    function convertDate(tanggal, aksi = null) {
      const date = new Date(tanggal);
      let options;
      switch (aksi) {
        case "date":
          options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
          };
          break;
        case "datetime":
          options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric'
          };
          break;
        default:
          options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
          };
          break;
      }
      // return date.toLocaleDateString('id-ID', options);
      return date.toLocaleString('id-ID', options);
    }

    $(document).ready(function() {
      var msg = "<?= session()->getFlashdata('msg'); ?>";
      if (msg) {
        let pesan = msg.split("#");
        Swal.fire({
          position: 'top-end',
          toast: true,
          icon: pesan[0],
          title: pesan[1],
          showConfirmButton: false,
          timer: 4000
        });
      }

      $(".keluar").click(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Apakah anda yakin?',
          text: "Keluar dari halaman ini?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, keluar!',
          cancelButtonText: 'batal',
        }).then((result) => {
          if (result.isConfirmed) {
            window.location = "<?= base_url('logout'); ?>";
          }
        })
      });
    });
  </script>
</body>

</html>