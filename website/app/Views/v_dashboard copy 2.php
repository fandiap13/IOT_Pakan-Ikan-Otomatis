<?= $this->extend('templates/template_user'); ?>

<?= $this->section('main'); ?>

<style>
  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
    background-color: white;
    border-radius: 30px;
    border: 1px solid #ccc;
    display: flex;
    align-items: center;
  }

  .switch-checked {
    background-color: #ccc;
  }

  /* Hide default HTML checkbox */
  .switch input {
    opacity: 0;
    width: 100%;
    height: 100%;
  }

  .slider {
    position: absolute;
    width: 26px;
    height: 26px;
    left: 4px;
    background-color: #2196F3;
    transition: .4s;
    border-radius: 50%;
  }

  .slider-change {
    transform: translateX(24px);
  }
</style>

<div class="col-lg-4 text-sm">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <div class="card-title">
        <h5 class="m-0">
          <i class="fas fa-laptop-house"></i> Control Akuarium
        </h5>
      </div>
    </div>
    <div class="card-body">
      <ul class="list-group">
        <li class="list-group-item bg-primary">Pemberian Pakan</li>
        <li class="list-group-item">
          <input type="hidden" id="id_control">
          <div class="d-flex justify-content-between align-items-center mt-2">
            <label for="beri_pakan">Beri Pakan</label>
            <label class="switch">
              <input type="checkbox" id="beri_pakan">
              <span class="slider"></span>
            </label>
          </div>
        </li>
        <li class="list-group-item">
          <b>Status pemberian pakan : </b><br>
          <div class="status_pemberian_pakan"></div>
        </li>
        <li class="list-group-item">
          <b>Terakhir kali pemberian pakan : </b><br>
          <div class="tgl_terbaru_pemberian_pakan"></div>
        </li>
      </ul>

      <ul class="list-group mt-3">
        <li class="list-group-item bg-primary">Data Ikan</li>
        <li class="list-group-item">
          <b>Jenis Ikan : </b><br>
          <div class="jenis_ikan"></div>
        </li>
        <li class="list-group-item">
          <b>Suhu Ideal : </b><br>
          <div><span class="suhu_min"></span>°C Sampai <span class="suhu_max"></span>°C </div>
        </li>
      </ul>

      <ul class="list-group mt-3">
        <li class="list-group-item bg-primary">Penghangat Akuarium : <span class="penghangat_akuarium_control"></span>
        </li>
      </ul>

      <ul class="list-group mt-3">
        <li class="list-group-item bg-primary">Alarm Pakan Habis : <span class="alarm_pakan"></span>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="col-lg-8 text-sm">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <div class="card-title">
        <h5 class="m-0">
          <i class="fas fa-desktop"></i> Monitoring Akuarium
        </h5>
      </div>
      <!-- <div class="card-tools">
        <button type="button" class="btn btn-primary" onclick="window.location.reload();"><i class="fas fa-sync-alt"></i> Refresh</button>
      </div> -->
    </div>
    <div class="card-body">
      <div class="col-lg-12">
        <div class="card card-success card-outline">
          <div class="card-header">
            <div class="card-title">
              <i class="fas fa-thermometer-full"></i> Status suhu saat ini
            </div>
            <div class="card-tools">
              <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="#status-suhu-chart" data-toggle="tab">Status</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#grafik-suhu-chart" data-toggle="tab">Grafik</a>
                </li>
              </ul>
              <!-- <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button> -->
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content p-0">
              <div class="chart tab-pane active" id="status-suhu-chart">
                <div class="row col-12">
                  <div class="col-lg-4 text-center">
                    <div id="suhu_akuarium"></div>
                  </div>
                  <div class="col-lg-8">
                    <div class="card">
                      <div class="card-header bg-success">
                        <h5 class="card-title"><i class="fas fa-info-circle"></i> Detail</h5>
                      </div>
                      <div class="card-body">
                        <p class="card-text">
                        <ol class="fa-ul">
                          <li><span class="fa-li"><i class="fas fa-calendar-alt"></i></span> <b>Tanggal : </b><span class="tgl_suhu"></span></li>
                          <li><span class="fa-li"><i class="fas fa-clock"></i></span> <b>Jam : </b><span class="jam_suhu"></span></li>
                          <li><span class="fa-li"><i class="fas fa-thermometer-three-quarters"></i></span> <b>Status suhu : </b><span class="status_suhu"></span></li>
                          <li><span class="fa-li"><i class="fab fa-hotjar"></i></span> <b>Penghangat akuarium : </b><span class="penghangat_akuarium"></span></li>
                        </ol>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="chart tab-pane" id="grafik-suhu-chart">
                <h5>Suhu rata - rata akuarium</h5>

                <div class="form-group">
                  <input type="month" name="cari_rata2_suhu" id="cari_rata2_suhu" class="form-control" value="<?= date("Y-m"); ?>">
                </div>

                <div id="cart-suhu" style="width: 100%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-12">
        <div class="card card-danger card-outline">
          <div class="card-header">
            <div class="card-title">
              <i class="fas fa-fish"></i> Status pakan saat ini
            </div>
            <div class="card-tools">
              <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                  <a class="nav-link active" href="#status-pakan-chart" data-toggle="tab">Status</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#grafik-pakan-chart" data-toggle="tab">Grafik</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="card-body">
            <div class="tab-content p-0">
              <div class="chart tab-pane active" id="status-pakan-chart">
                <div class="row col-12">
                  <div class="col-lg-4 text-center">
                    <div id="presentase_pakan" style="width: 100%;"></div>
                  </div>
                  <div class="col-lg-8">
                    <div class="card">
                      <div class="card-header bg-danger">
                        <h5 class="card-title"><i class="fas fa-info-circle"></i> Detail</h5>
                      </div>
                      <div class="card-body">
                        <p class="card-text">
                        <ol class="fa-ul">
                          <li><span class="fa-li"><i class="fas fa-calendar-alt"></i></span> <b>Tanggal : </b><span class="tgl_pakan"></span></li>
                          <li><span class="fa-li"><i class="fas fa-clock"></i></span> <b>Jam : </b><span class="jam_pakan"></span></li>
                          <li><span class="fa-li"><i class="fas fa-thermometer-three-quarters"></i></span> <b>Status pakan : </b><span class="status_pakan"></span></li>
                          <li><span class="fa-li"><i class="fas fa-bell"></i></i></span> <b>Alarm Pakan Habis : </b><span class="status_buzzer"></span></li>
                        </ol>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="chart tab-pane" id="grafik-pakan-chart">
                <h5>Rata - rata presentase pakan</h5>

                <div class="form-group">
                  <input type="month" name="cari_rata2_pakan" id="cari_rata2_pakan" class="form-control" value="<?= date("Y-m"); ?>">
                </div>


                <div id="cart-pakan" style="width: 100%;"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  var suhu_akuarium = new ApexCharts(document.querySelector("#suhu_akuarium"), {
    colors: ['#28a745'],
    chart: {
      height: 250,
      type: 'radialBar',
    },
    series: [0],
    labels: ['Suhu'],
    plotOptions: {
      radialBar: {
        dataLabels: {
          value: {
            formatter: function(val) {
              return `${val}°C`;
            },
          }
        }
      },
    },
  });
  var presentase_pakan = new ApexCharts(document.querySelector("#presentase_pakan"), {
    colors: ['#dc3545'],
    chart: {
      height: 250,
      type: 'radialBar',
    },
    series: [0],
    labels: ['Persentase '],
  });
  var grafik_suhu = new ApexCharts(document.querySelector("#cart-suhu"), {
    series: [{
      name: "Suhu",
      data: [0]
    }],
    chart: {
      height: 500,
      type: 'line',
      toolbar: {
        show: true // fungsi untuk pengaturan grafik
      },
    },
    dataLabels: {
      enabled: false,
      formatter: function(val) {
        return val + " ℃";
      },
    },
    title: {
      text: 'Suhu akuarium',
      align: 'left'
    },
    colors: ['#28a745'],
    stroke: {
      curve: 'straight',
      width: 0.5,
    },
    grid: {
      row: {
        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    markers: {
      size: 1,
    },
    xaxis: {
      title: {
        text: 'Tanggal'
      },
      categories: [''],
    },
    yaxis: {
      labels: {
        formatter: function(value) {
          return value + ' ℃';
        }
      },
      title: {
        text: "Suhu (℃)"
      }
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
    }
  });
  var grafik_pakan = new ApexCharts(document.querySelector("#cart-pakan"), {
    series: [{
      name: "Persentase Pakan",
      data: [0]
    }],
    chart: {
      height: 500,
      type: 'line',
      toolbar: {
        show: true // fungsi untuk pengaturan grafik
      },
    },
    dataLabels: {
      enabled: false,
      formatter: function(val) {
        return val + " %";
      },
    },
    title: {
      text: 'Grafik sensor jarak',
      align: 'left'
    },
    colors: ['#dc3545'],
    stroke: {
      curve: 'straight',
      width: 0.5,
    },
    grid: {
      row: {
        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    markers: {
      size: 1,
    },
    xaxis: {
      title: {
        text: 'Tanggal'
      },
      categories: [''],
    },
    yaxis: {
      labels: {
        formatter: function(value) {
          return value + ' %';
        }
      },
      title: {
        text: "Persentase (%)"
      }
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
    }
  });

  suhu_akuarium.render();
  presentase_pakan.render();
  grafik_suhu.render();
  grafik_pakan.render();

  function status_slider(status) {
    if (status.toLowerCase() == "on") {
      $('#beri_pakan').attr('checked', true);
      $('#beri_pakan').attr('disabled', true);
      $('#beri_pakan').parent('.switch').addClass('switch-checked').find(".slider").addClass('slider-change');
    } else {
      $('#beri_pakan').removeAttr('disabled');
      $('#beri_pakan').removeAttr('checked');
      $('#beri_pakan').parent('.switch').removeClass('switch-checked').find(".slider").removeClass('slider-change');
    }
  }

  // mengambil data kontrol
  function getDataKontrol() {
    $.ajax({
      url: "<?= base_url("kontrol/getDataKontrol"); ?>",
      dataType: "json",
      success: function(response) {
        if (response.data) {
          let data = response.data;
          // console.log(data);
          $('#id_control').val(data.id);
          $('.status_pemberian_pakan').html(data.status_pemberian_pakan);
          $('.tgl_terbaru_pemberian_pakan').html(convertDate(response.tgl_terbaru_pemberian_pakan, 'datetime'));
          $('.penghangat_akuarium_control').html(data.penghangat_akuarium);
          $('.alarm_pakan').html(data.buzzer);

          if (data.servo == "ON") {
            status_slider("ON");
          } else {
            status_slider("OFF");
          }

          $('#beri_pakan').val(data.servo);
        }

        if (response.error) Swal.fire("Error", response.error, 'error').then(() => window.location.reload());
      },
      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
      }
    });
  }
  // mengambil data sensor jarak dan suhu
  function getDataSensor() {
    $.ajax({
      url: "<?= base_url("sensor/data_sensor"); ?>",
      dataType: "json",
      success: function(response) {
        if (response.data) {
          let data_suhu = response.data.suhu;
          let data_pakan = response.data.pakan;
          let data_ikan = response.data.ikan;

          suhu_akuarium.updateSeries([data_suhu.suhu_akuarium]);
          presentase_pakan.updateSeries([data_pakan.presentase_pakan]);

          // suhu
          $('.tgl_suhu').html(data_suhu.tanggal);
          $('.jam_suhu').html(data_suhu.jam);
          $('.status_suhu').html(data_suhu.status_suhu);
          $('.penghangat_akuarium').html(data_suhu.penghangat_akuarium);

          // pakan
          $('.tgl_pakan').html(data_pakan.tanggal);
          $('.jam_pakan').html(data_pakan.jam);
          $('.status_pakan').html(data_pakan.status_pakan);
          $('.status_buzzer').html(data_pakan.status_buzzer);

          // ikan
          $('.jenis_ikan').html(data_ikan.kategori);
          $('.suhu_min').html(data_ikan.suhu_min);
          $('.suhu_max').html(data_ikan.suhu_max);
        }
        if (response.error) Swal.fire("Error", response.error, 'error').then(() => window.location.reload());
      },
      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
      }
    });
  }
  // mengambil data grafik sensor
  function getDataGrafik() {
    $.ajax({
      type: "post",
      url: "<?= base_url('sensor/data_grafik'); ?>",
      data: {
        bulan_suhu: $("#cari_rata2_suhu").val(),
        bulan_pakan: $('#cari_rata2_pakan').val()
      },
      dataType: "json",
      success: function(response) {
        // console.log(response.data);
        if (response.data) {
          let data = response.data;
          grafik_suhu.updateOptions({
            xaxis: {
              categories: data.grafik_suhu.tanggal
            },
            series: [{
              name: "Suhu",
              data: data.grafik_suhu.data_sensor,
            }]
          });
          grafik_pakan.updateOptions({
            xaxis: {
              categories: data.grafik_pakan.tanggal
            },
            series: [{
              name: "Persentase Pakan",
              data: data.grafik_pakan.data_sensor,
            }]
          });
        }
      },
      error: function(xhr, status, error) {
        var err = eval("(" + xhr.responseText + ")");
        alert(err.Message);
      }
    });
  }

  // inisialisasi interval
  var intervalnya;

  $(document).ready(function() {
    getDataKontrol();
    getDataSensor();
    getDataGrafik();

    // dijalankan tiap 2 detik
    intervalnya = setInterval(() => {
      getDataKontrol();
      getDataSensor();
      getDataGrafik();
    }, 2000);

    $("#cari_rata2_suhu").change(function(e) {
      e.preventDefault();
      getDataGrafik();
    });

    $("#cari_rata2_pakan").change(function(e) {
      e.preventDefault();
      getDataGrafik();
    });

    $('#beri_pakan').click(function(e) {
      // hapus interval
      clearInterval(intervalnya);

      Swal.fire({
        title: 'Apakah anda yakin?',
        text: "Anda mengaktifkan pemberian pakan !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, lakukan!',
        cancelButtonText: 'tutup'
      }).then((result) => {
        if (result.isConfirmed) {
          if ($('#beri_pakan').is(':checked')) {
            // ubah status slider switch
            $('#beri_pakan').attr('disabled', true);
            $('#beri_pakan').parent('.switch').addClass('switch-checked').find(".slider").addClass('slider-change');

            $.ajax({
              type: "post",
              url: "<?= base_url('kontrol/beri_pakan'); ?>",
              data: {
                id_control: $("#id_control").val(),
              },
              dataType: "json",
              success: function(response) {
                // console.log(response);
                if (response.error) {
                  Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
                }
                // jalankan kembali interval jika sudah selesai
                intervalnya = setInterval(() => {
                  getDataKontrol();
                  getDataSensor();
                  getDataGrafik();
                }, 2000);
              },
              error: function(xhr, status, error) {
                var err = eval("(" + xhr.responseText + ")");
                alert(err.Message);
              }
            });
          } else {
            $('#beri_pakan').removeAttr('disabled');
            $('#beri_pakan').parent('.switch').removeClass('switch-checked').find(".slider").removeClass('slider-change');
          }
        } else {
          window.location.reload();
        }
      })
    });

  });
</script>

<?= $this->endSection('main'); ?>