<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Media Pemilihan
            <small>Daftar forum pemilihan ada disini.</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active"><i class="fa fa-floppy-o"></i> Media Pemilihan</li>
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
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Daftar seluruh media pemilihan</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="grup-tombol">
                    <a href="<?= base_url('/admin/media_pemilihan/tambah'); ?>" class="btn btn-primary"><i class="fa fa-plus-square"></i> Tambah Media Pemilihan</a>
                </div>
                <div class="responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Pemilihan</th>
                                <th>Poster</th>
                                <th>Mulai Pemilihan</th>
                                <th>Batas Pemilihan</th>
                                <th>Kata Kunci</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($media as $key => $value) :
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td class="text-capitalize"><?= $value['judul_pemilihan']; ?></td>
                                    <td><img src="/img/<?= $value['poster']; ?>" alt="<?= $value['judul_pemilihan']; ?>" class="poster-media-pemilihan"></td>
                                    <td><?= format_datetime($value['mulai_pemilihan']); ?></td>
                                    <td><?= format_datetime($value['batas_pemilihan']); ?></td>
                                    <td><span class="badge"><?= $value['kata_kunci']; ?></span></td>
                                    <td>
                                        <a href="<?= base_url('admin/media_pemilihan/detail/') . $value['slug']; ?>" class="btn btn-info"><i class="fa fa-info-circle"></i> Detail</a><br>
                                        <a href="<?= base_url('admin/media_pemilihan/edit/') . $value['slug']; ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Ubah</a><br>
                                        <button class="btn btn-danger" data-toggle="modal" data-target="#removeMedia<?= $value['media_id'] ?>"><i class="fa fa-trash-o"></i> Hapus</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Hapus Media Pemilihan -->
<?php
foreach ($media as $row) : ?>
    <div class="modal modal-default fade" id="removeMedia<?= $row['media_id'] ?>">
        <form action="<?= base_url('/admin/media_pemilihan/remove_media/') . $row['media_id'] ?>" method="post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Hapus Permanen</h4>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data <b><?= $row['judul_pemilihan']; ?></b> ini?..</p>
                        <strong>Data yang akan dihapus permanen:</strong>
                        <ol>
                            <li>Data kandidat</li>
                            <li>Data peserta</li>
                            <li>Data hak suara atau hasil perhitungan suara</li>
                        </ol>

                        <p><b>Konfirmasi</b> dengan mengetikan dibawah ini (tidak termasuk kutip 2 ("")) <i><br>
                                "Hapus <?= ucwords($row['judul_pemilihan']); ?></i>"</p>
                        <input type="text" class="form-control" name="konfirmasi" placeholder="konfirmasi disini" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash-o"></i> Hapus</button>
                    </div>
        </form>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<?php endforeach ?>

<?= $this->endSection(); ?>