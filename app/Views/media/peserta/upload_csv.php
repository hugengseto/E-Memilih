<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Upload csv
            <small>Lakukan import peserta disini.</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('admin/media_pemilihan'); ?>"><i class="fa fa-floppy-o"></i> Media Pemilihan</a></li>
            <li><a href="<?= base_url('admin/media_pemilihan/detail/') . $media['slug']; ?>">Detail</a></li>
            <li class="active">Upload csv</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php if (session()->getFlashdata('message')) : ?>
            <div class="alert <?= session()->getFlashdata('alert'); ?> alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?= session()->getFlashdata('message'); ?>
            </div>
        <?php endif ?>
        <a href="<?= base_url('admin/media_pemilihan/detail/') . $media['slug']; ?>" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> Kembali</a>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Petunjuk Upload</h3>
                <ol>
                    <li>Download Format File dibawah ini.</li>
                    <a href="<?= base_url('/admin/download/format_peserta_pemilihan.csv'); ?>" class="btn btn-primary">
                        <i class="fa fa-download"></i> Download File
                    </a>
                    <li>Lalu lakukan pengisian pada file .csv tersebut.</li>
                    <ul>
                        <li>pastikan nomor whatsapp <b>diawali dengan 08</b> bukan +62</li>
                        <li>pastikan tanggal lahir dengan format <b>tahun-bulan-tanggal (YYYY-MM-DD)</b></li>
                    </ul>
                    <li>Setelah itu baru lakukan upload disini(pilih file).</li>
                    <li>Tekan tombol preview</li>
                    <li>Terakhir, tekan tombol import untuk melakukan import kedatabase</li>
                    <br>
                    <p class="text-danger">*Perlu diingat Nomor Whatsapp yang sudah terdaftar pada pemilihan ini tidak akan bisa ditambahkan kembali. Namun bila Nomor Whatsapp sudah ada didalam file csvnya tidak akan menjadi masalah proses import masih bisa dijalankan, nantinya hanya nomor whatsapp yang belum ditambahkan saja yang akan disimpan. ENJOY :)!</p>
                </ol>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <form id="uploadForm" action="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/import_peserta'; ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="fileCsv">File</label>
                        <input type="file" id="fileCsv" name="fileCsv">
                        <p class="help-block">Hanya format .csv yang diizinkan.</p>
                    </div>
                    <button type="button" id="previewButton" class="btn btn-info">Preview</button>
                </form>

                <div id="preview-data-peserta">
                    <!-- Placeholder for displaying preview data -->
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?= $this->endSection(); ?>