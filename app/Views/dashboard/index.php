    <?= $this->extend('layouts/admin/template_admin'); ?>

    <?= $this->section('content'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Dashboard
                <small>Version 2.0</small>
            </h1>
            <ol class="breadcrumb">
                <li class="<?= uri_string() == 'admin/dashboard' ? 'active' : ''; ?>"><i class="fa fa-dashboard"></i> Dashboard</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-android-laptop"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Media Pemilihan</span>
                            <span class="info-box-number"><?= count($media); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="ion ion-ios-person"></i> <i class="ion ion-ios-person"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Kandidat</span>
                            <span class="info-box-number"><?= count($kandidat); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">Partisipasi</span>
                            <span class="info-box-number"><?= number_format(count($peserta), 0, '.', '.'); ?></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-sm-8">
                    <!-- mulai fungsi menentukan status -->
                    <?php foreach ($media as $row) : ?>
                        <?php if ($getStatus($row['mulai_pemilihan'], $row['batas_pemilihan']) == "Berlangsung" || $getStatus($row['mulai_pemilihan'], $row['batas_pemilihan']) == "Mendatang") : ?>
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
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?= $this->endSection(); ?>