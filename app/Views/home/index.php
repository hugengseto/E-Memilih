<?= $this->extend('layouts/user/template_user'); ?>

<?= $this->section('content'); ?>

<!-- Full Width Column -->
<div class="content-wrapper">
    <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Hak Suara
                <small>"Suara kita berharga, jangan sia-siakan hak pilihmu!"</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?= base_url(); ?>"><i class="fa fa-dashboard"></i> Utama</a></li>
                <li class="active">Hak Suara</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-sm-8">
                    <!-- mulai fungsi menentukan status -->
                    <?php foreach ($media as $row) : ?>
                        <div class="card">
                            <div class="card-img">
                                <img src="/img/<?= $row['poster']; ?>" alt="Product Image">
                            </div>
                            <div class="card-info">
                                <a href="<?= base_url('/') . $row['slug']; ?>" class="card-title h2"><?= ucwords($row['judul_pemilihan']); ?></a>
                                <p class="card-time status-badge <?= $getStatus($row['mulai_pemilihan'], $row['batas_pemilihan']); ?>">
                                    <?= $getStatus($row['mulai_pemilihan'], $row['batas_pemilihan']); ?>
                                </p>
                                <p class="card-description">Pelaksanaan <?= format_datetime($row['mulai_pemilihan']) . ' s/d ' . format_datetime($row['batas_pemilihan']); ?></p>
                                <p class="card-description">Kata Kunci: <?= $row['kata_kunci']; ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-sm-4">
                    <!-- Horizontal Form -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Sudah tahukah kamu?</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <p class="text-justify">Hak suara atau hak pilih adalah hak yang dimiliki oleh penduduk untuk memilih. Dalam masyarakat yang menerapkan prinsip demokrasi, penduduk yang mencapai usia pemilihan dibolehkan ikut memilih dalam pemilihan umum.</p>

                            <p class="text-justify">
                                Menurut Mahkamah dalam pendapatnya, hak memilih adalah hak yang dijamin dalam konstitusi sebagaimana dinyatakan dalam <b>Putusan MK Nomor 011-017/PUU-I/2003</b> yang menyebutkan, “Menimbang, bahwa hak konstitusional warga negara untuk memilih dan dipilih (right to vote and right to be candidate) adalah hak yang dijamin oleh konstitusi, undang-undang, maupun konvensi internasional, maka pembatasan, penyimpangan, peniadaan dan penghapusan akan hak dimaksud merupakan pelanggaran terhadap hak asasi warga negara.”
                            </p>
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
<!-- /.content-wrapper -->

<?= $this->endSection(); ?>