<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Media Pemilihan
            <small>Perbarui data</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('admin/media_pemilihan'); ?>"><i class="fa fa-floppy-o"></i> Media Pemilihan</a></li>
            <li class="active">Ubah</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row tambah">
            <!-- left column -->
            <div class="col-md-8">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulir</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="/admin/media_pemilihan/aksi_edit/<?= $media['slug']; ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="box-body">
                            <!-- judul pemilihan -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['judul_pemilihan']) ? 'has-error' : ''; ?>">
                                <label for="judul_pemilihan">*Judul Pemilihan</label>
                                <input type="text" class="form-control" id="judul_pemilihan" name="judul_pemilihan" value="<?= old('judul_pemilihan') ? old('judul_pemilihan') : $media['judul_pemilihan']; ?>" placeholder="Masukkan judul pemilihan">

                                <?php if (session('errors') && isset(session('errors')['judul_pemilihan'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['judul_pemilihan']); ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- Date and time range -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['pelaksanaan']) ? 'has-error' : ''; ?>">
                                <label>*Waktu Pelaksanaan</label>

                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="pelaksanaan" name="pelaksanaan" value="<?= old('pelaksanaan') ? old('pelaksanaan') : $pelaksanaan; ?>">
                                </div>
                                <!-- /.input group -->
                                <p class="help-block">Mohon masukkan tanggal dan waktu berlansungnya pemilihan (mulai dan berkahir).</p>
                                <?php if (session('errors') && isset(session('errors')['pelaksanaan'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['pelaksanaan']); ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- input file -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['poster']) ? 'has-error' : ''; ?>">
                                <label for="poster">Poster Pemilihan</label>
                                <input type="file" id="poster" name="poster" accept="image/*">
                                <input type="text" name="posterLama" value="<?= $media['poster']; ?>" hidden>

                                <p class="help-block">(opsional) Ukuran file maksimal 1 Mb dan berformat JPEG/JPG/PNG.</p>
                                <?php if (session('errors') && isset(session('errors')['poster'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['poster']); ?></span>
                                <?php endif; ?>

                                <!-- Tempat untuk pratinjau gambar -->
                                <img id="poster-preview" src="/img/<?= $media['poster']; ?>" alt="Pratinjau Poster">
                            </div>

                            <!-- input kata kunci -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['kata_kunci']) ? 'has-error' : ''; ?>">
                                <label for="kata_kunci">*Kata Kunci</label>
                                <input type="text" class="form-control" id="kata_kunci" name="kata_kunci" value="<?= old('kata_kunci') ? old('kata_kunci') : $media['kata_kunci']; ?>" placeholder="Masukkan judul pemilihan">

                                <?php if (session('errors') && isset(session('errors')['kata_kunci'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['kata_kunci']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <a href="<?= base_url('/admin/media_pemilihan'); ?>" class="btn btn-default">Batal</a>
                            <button type="submit" class="btn btn-warning pull-right">Perbarui</button>
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