<?= $this->extend('layouts/user/template_user'); ?>

<?= $this->section('content'); ?>

<!-- Full Width Column -->
<div class="content-wrapper">
    <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Konfrimasi Hak Pilih
                <small>"Suara kita berharga, jangan sia-siakan hak pilihmu!"</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= base_url(); ?>"><i class="fa fa-dashboard"></i> Utama</a></li>
                <li>Hak Suara</li>
                <li class="active">Konfrimasi OTP</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row tambah">
                <div class="col-md-3">
                    <div class="box box-info">
                        <div class="box-body">
                            <h4 style="font-weight: bold;">Identitas Pemilih</h4>
                            <p><?= $peserta['nama_lengkap']; ?><br>( <?= $peserta['nomor_whatsapp']; ?> )</p>
                        </div>
                    </div>
                </div>
                <!-- left column -->
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="box box-primary">
                        <!-- /.box-header -->
                        <!-- form start -->
                        <form action="<?= base_url('/') . $detail['slug'] . '/hak_pilih_id/' . $detail['hak_pilih_id']; ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field(); ?>

                            <div class="kandidat-block">
                                <div class="nomor-urut"><span><?= $detail['nomor_urut']; ?></span></div>
                                <img class="img-bordered-sm" src="/img/kandidat/<?= $detail['poster_kandidat']; ?>" alt="kandidat image">
                                <div class="kandidat-info">
                                    <?php
                                    $nama_kandidat = explode(',', $detail['nama_kandidat']);

                                    foreach ($nama_kandidat as $key => $value) : ?>
                                        <span class="kandidatname"><?= $value; ?></span><br>
                                    <?php endforeach ?>
                                    <div class="program">
                                        <h4><strong>Visi</strong></h4>
                                        <p>
                                            <?= $detail['visi']; ?>
                                        </p>
                                        <h4><strong>Misi</strong></h4>
                                        <p>
                                            <?= $detail['misi']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="box-body">
                                <!-- judul pemilihan -->
                                <div class="form-group <?= session('errors') && isset(session('errors')['kodeOtp']) ? 'has-error' : ''; ?>">
                                    <label for="kodeOtp">*Kode OTP</label>
                                    <input type="text" class="form-control" id="kodeOtp" name="kodeOtp" value="<?= old('kodeOtp'); ?>" placeholder="Masukkan kode OTP" maxlength="6">

                                    <?php if (session('errors') && isset(session('errors')['kodeOtp'])) : ?>
                                        <span class="help-block"><?= esc(session('errors')['kodeOtp']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                                <a href="<?= base_url('/') . $detail['slug']; ?>" class="btn btn-default">Kembali</a>
                                <button type="submit" class="btn btn-primary pull-right">Serahkan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.box -->
                </div>
                <!--/.col (left) -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
<!-- /.content-wrapper -->

<?= $this->endSection(); ?>