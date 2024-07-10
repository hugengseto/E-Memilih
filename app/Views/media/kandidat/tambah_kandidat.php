<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Kandidat Baru
            <small>Tambahkan data kandidat</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('admin/media_pemilihan'); ?>"><i class="fa fa-floppy-o"></i> Media Pemilihan</a></li>
            <li><a href="<?= base_url('admin/media_pemilihan/detail/') . $media['slug']; ?>">Detail</a></li>
            <li class="active">Tambah Kandidat</li>
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
                        <h5>Kandidat <b><?= ucwords($media['judul_pemilihan']); ?></b></h5>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form action="/admin/media_pemilihan/aksi_tambah/kandidat/<?= $media['slug']; ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="box-body">
                            <!-- Nomor Urut -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['nomor_urut']) ? 'has-error' : ''; ?>">
                                <label for="nomor_urut">*Nomor Urut</label>
                                <input type="number" class="form-control" id="nomor_urut" name="nomor_urut" value="<?= old('nomor_urut'); ?>" placeholder="Masukkan Nomor Urut Kandidat">

                                <?php if (session('errors') && isset(session('errors')['nomor_urut'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['nomor_urut']); ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- Nama kandidat -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['nama_kandidat']) ? 'has-error' : ''; ?>">
                                <label for="nama_kandidat">*Nama Kandidat (Ketua/Pemimpin)</label>
                                <input type="text" class="form-control" id="nama_kandidat" name="nama_kandidat" value="<?= old('nama_kandidat'); ?>" placeholder="Masukkan Nama Ketua">

                                <?php if (session('errors') && isset(session('errors')['nama_kandidat'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['nama_kandidat']); ?></span>
                                <?php endif; ?>
                            </div>

                            <!--  Start wakil kandidat-->
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="wakil_kandidat"> Checklist ini untuk menambahkan <b>Wakil</b> <i>Ketua/Pemimpin</i> kandidat
                                </label>
                            </div>

                            <div id="form_wakil_kandidat">
                                <!-- Nama wakil kandidat -->
                                <div class="form-group <?= session('errors') && isset(session('errors')['nama_wakil_kandidat']) ? 'has-error' : ''; ?>">
                                    <label for="nama_wakil_kandidat">Nama Wakil Kandidat (Ketua/Pemimpin)</label>
                                    <input type="text" class="form-control" id="nama_wakil_kandidat" name="nama_wakil_kandidat" value="<?= old('nama_wakil_kandidat'); ?>" placeholder="Masukkan Nama Wakil Ketua">

                                    <?php if (session('errors') && isset(session('errors')['nama_wakil_kandidat'])) : ?>
                                        <span class="help-block"><?= esc(session('errors')['nama_wakil_kandidat']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- End wakil kandidat -->

                            <!-- input file -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['poster']) ? 'has-error' : ''; ?>">
                                <label for="poster">Poster Kandidat</label>
                                <input type="file" id="poster" name="poster" accept="image/*">

                                <p class="help-block">(opsional) Ukuran file maksimal 1 Mb dan berformat JPEG/JPG/PNG.</p>
                                <?php if (session('errors') && isset(session('errors')['poster'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['poster']); ?></span>
                                <?php endif; ?>

                                <!-- Tempat untuk pratinjau gambar -->
                                <img id="poster-preview" src="/img/kandidat/default-photo-kandidat.jpg" alt="Pratinjau Poster">
                            </div>

                            <!-- visi -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['visi']) ? 'has-error' : ''; ?>">
                                <label>*Visi</label>

                                <div class="box  <?= session('errors') && isset(session('errors')['visi']) ? 'box-danger' : 'box-info'; ?>">
                                    <div class="box-body pad">
                                        <textarea id="visi" name="visi" rows="10" cols="80">
                                            <?= old("visi"); ?>
                                        </textarea>
                                    </div>
                                </div>

                                <?php if (session('errors') && isset(session('errors')['visi'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['visi']); ?></span>
                                <?php endif; ?>
                            </div>
                            <!-- misi -->
                            <div class="form-group <?= session('errors') && isset(session('errors')['misi']) ? 'has-error' : ''; ?>">
                                <label>*Misi</label>

                                <div class="box <?= session('errors') && isset(session('errors')['misi']) ? 'box-danger' : 'box-info'; ?>">
                                    <div class="box-body pad">
                                        <textarea id="misi" name="misi" rows="10" cols="80">
                                            <?= old("misi"); ?>
                                        </textarea>
                                    </div>
                                </div>

                                <?php if (session('errors') && isset(session('errors')['misi'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['misi']); ?></span>
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