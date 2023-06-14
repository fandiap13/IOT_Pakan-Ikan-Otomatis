<?= $this->extend('templates/template_user'); ?>

<?= $this->section('main'); ?>
<!-- jQuery Knob -->
<script src="<?= base_url(); ?>/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- Sparkline -->
<script src="<?= base_url(); ?>/plugins/sparklines/sparkline.js"></script>

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

<div class="col-lg-4">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h5 class="m-0">
        <i class="fas fa-laptop-house"></i> Control Akuarium
      </h5>
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

<div class="col-lg-8">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h5 class="m-0">
        <i class="fas fa-desktop"></i> Monitoring Akuarium
      </h5>
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
              <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="row col-12">
              <div class="col-lg-4 text-center">
                <input type="text" value="0" data-skin="tron" data-thickness="0.2" data-width="150" data-height="150" data-fgColor="#28a745" data-readonly="true" id="suhu_akuarium">
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
              <button type="button" class="btn btn-danger btn-sm" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="row col-12">
              <div class="col-lg-4 text-center">
                <input type="text" value="0" data-skin="tron" data-thickness="0.2" data-width="150" data-height="150" data-fgColor="#dc3545" data-readonly="true" id="presentase_pakan">
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
        </div>
      </div>
    </div>
  </div>
</div>


<script>
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
          $('.penghangat_akuarium_control').html(data.penghangat_akuarium);
          $('.alarm_pakan').html(data.buzzer);

          if (data.servo == "ON") {
            $('#beri_pakan').attr('checked', true);
            $('#beri_pakan').attr('disabled', true);
            $('#beri_pakan').parent('.switch').addClass('switch-checked').find(".slider").addClass('slider-change');
          } else {
            $('#beri_pakan').removeAttr('disabled');
            $('#beri_pakan').removeAttr('checked');
            $('#beri_pakan').parent('.switch').removeClass('switch-checked').find(".slider").removeClass('slider-change');
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
          // console.log(response.data);
          let data_suhu = response.data.suhu;
          let data_pakan = response.data.pakan;
          let data_ikan = response.data.ikan;
          $('#suhu_akuarium').val(data_suhu.suhu_akuarium).trigger('change').trigger('format');
          $('#presentase_pakan').val(data_pakan.presentase_pakan).trigger('change').trigger('format');

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

  function settingKnob() {
    // "tron" case

    // setting font
    // $(this.i).css('transform', 'rotate(180deg)').css('font-size', '10pt');
    $(this.i).css('font-size', '20px');

    if (this.$.data('skin') == 'tron') {

      var a = this.angle(this.cv) // Angle
        ,
        sa = this.startAngle // Previous start angle
        ,
        sat = this.startAngle // Start angle
        ,
        ea // Previous end angle
        ,
        eat = sat + a // End angle
        ,
        r = true

      this.g.lineWidth = this.lineWidth

      this.o.cursor &&
        (sat = eat - 0.3) &&
        (eat = eat + 0.3)

      if (this.o.displayPrevious) {
        ea = this.startAngle + this.angle(this.value)
        this.o.cursor &&
          (sa = ea - 0.3) &&
          (ea = ea + 0.3)
        this.g.beginPath()
        this.g.strokeStyle = this.previousColor
        this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false)
        this.g.stroke()
      }

      this.g.beginPath()
      this.g.strokeStyle = r ? this.o.fgColor : this.fgColor
      this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false)
      this.g.stroke()

      this.g.lineWidth = 2
      this.g.beginPath()
      this.g.strokeStyle = this.o.fgColor
      this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false)
      this.g.stroke()

      return false;
    }
  }

  // inisialisasi interval
  var intervalnya;

  $(document).ready(function() {

    $('#suhu_akuarium').knob({
      change: settingKnob,
      format: function(value) {
        return `${value} °C`;
      },
      draw: settingKnob,
    });

    $('#presentase_pakan').knob({
      'min': 0,
      'max': 150, // Set to 200 as a max
      step: 1,
      change: settingKnob,
      format: function(value) {
        return `${value} %`;
      },
      draw: settingKnob,
    });

    getDataKontrol();
    getDataSensor();

    // dijalankan tiap 1 detik
    intervalnya = setInterval(() => {
      getDataKontrol();
      getDataSensor();
    }, 1000);

    $('#beri_pakan').click(function(e) {
      // e.preventDefault();
      clearInterval(intervalnya);

      if ($('#beri_pakan').is(':checked')) {

        // ubah status slider switch
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
            }, 1000);
          },
          error: function(xhr, status, error) {
            var err = eval("(" + xhr.responseText + ")");
            alert(err.Message);
          }
        });
      } else {
        $('#beri_pakan').parent('.switch').removeClass('switch-checked').find(".slider").removeClass('slider-change');
      }
    });

  });
</script>

<?= $this->endSection('main'); ?>