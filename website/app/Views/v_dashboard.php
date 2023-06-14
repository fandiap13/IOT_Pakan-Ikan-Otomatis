<?= $this->extend('templates/template_user'); ?>

<?= $this->section('main'); ?>

<div class="col-lg-12">
  <div class="card card-primary card-outline">
    <div class="card-header">Profil User</div>
    <div class="card-body">

      <?php if (validation_errors()) : ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5><i class="icon fas fa-ban"></i> Error!</h5>
          <?= validation_list_errors() ?>
        </div>
      <?php endif; ?>

      <?= form_open('', ['class' => 'formsimpan']); ?>
      <div class="form-group">
        <label for="">E-mail</label>
        <input type="email" name="email" class="form-control" value="<?= old('email') ? old('email') : $userData['email']; ?>" autofocus>
      </div>
      <div class="form-group">
        <label for="">Nama</label>
        <input type="text" name="nama" class="form-control" value="<?= old('nama') ? old('nama') : $userData['nama']; ?>">
      </div>
      <div class="form-group row">
        <div class="col-lg-6">
          <label for="">Password</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="col-lg-6">
          <label for="">Retype Password</label>
          <input type="password" name="retype_password" class="form-control">
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Simpan Perubahan</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>

<?= $this->endSection('main'); ?>