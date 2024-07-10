<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Peserta
            <small>Ubah data peserta</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('admin/media_pemilihan'); ?>"><i class="fa fa-floppy-o"></i> Media Pemilihan</a></li>
            <li><a href="<?= base_url('admin/media_pemilihan/detail/') . $media['slug']; ?>">Detail</a></li>
            <li class="active">Ubah Peserta</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row tambah">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Formulir</h3>
                        <h5>Peserta <b><?= ucwords($media['judul_pemilihan']); ?></b></h5>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="/admin/media_pemilihan/aksi_edit/<?= $media['slug']; ?>/peserta/<?= $peserta['peserta_id']; ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="box-body <?= session('errors') && isset(session('errors')['tanggal_lahir']) ? 'has-error' : ''; ?>">
                            <!-- Nama Peserta -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['nama_lengkap']) ? 'has-error' : ''; ?>">
                                <label for="nama_lengkap">*Nama Peserta</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap', $peserta['nama_lengkap']); ?>" placeholder="Masukkan Nama Lengkap">

                                <?php
                                if (session('errors') && isset(session('errors')['nama_lengkap'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['nama_lengkap']); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['tanggal_lahir']) ? 'has-error' : ''; ?>">
                                <label>*Tanggal Lahir:</label>

                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="datepicker" name="tanggal_lahir" value="<?= old('tanggal_lahir', format_tanggal_default_plugin($peserta['tanggal_lahir'])); ?>">
                                </div>
                                <?php if (session('errors') && isset(session('errors')['tanggal_lahir'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['tanggal_lahir']); ?></span>
                                <?php endif; ?>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <!-- Nomor Whatsapp -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['nomor_whatsapp']) ? 'has-error' : ''; ?>">
                                <label for="nomor_whatsapp">*Nomor Whatsapp</label>
                                <input type="number" class="form-control" id="nomor_whatsapp" name="nomor_whatsapp" value="<?= old('nomor_whatsapp', $peserta['nomor_whatsapp']); ?>" placeholder="Masukkan Nomor Whatsapp">

                                <?php if (session('errors') && isset(session('errors')['nomor_whatsapp'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['nomor_whatsapp']); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- select -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['jenis_kelamin']) ? 'has-error' : ''; ?>">
                                <label for="jenis_kelamin">*Jenis Kelamin</label>
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    <option <?= empty($peserta['jenis_kelamin']) ? "selected" : "" ?>> --- Pilih ---</option>
                                    <option <?= $peserta['jenis_kelamin'] == 'Laki-Laki' ? "selected" : "" ?> value="Laki-Laki">Laki-Laki</option>
                                    <option <?= $peserta['jenis_kelamin'] == 'Perempuan' ? "selected" : "" ?> value="Perempuan">Perempuan</option>
                                </select>

                                <?php if (session('errors') && isset(session('errors')['jenis_kelamin'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['jenis_kelamin']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="<?= base_url('/admin/media_pemilihan/detail/') . $media['slug']; ?>" class="btn btn-default">Batal</a>
                            <button type="submit" class="btn btn-primary pull-right">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            <!--/.col (left) -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?= $this->endSection(); ?>