<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Detail Informasi Pemilihan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url('admin/media_pemilihan'); ?>"><i class="fa fa-floppy-o"></i> Media Pemilihan</a></li>
            <li class="active">Detail</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="row">
            <div class="col-md-3">

                <!-- Profile Image -->
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive" src="/img/<?= $media['poster']; ?>" alt="User profile picture">

                        <h3 class="profile-username text-center text-capitalize"><?= $media['judul_pemilihan']; ?></h3>

                        <p class="text-muted text-center"><?= format_datetime($media['mulai_pemilihan']); ?><br>s/d<br><?= format_datetime($media['batas_pemilihan']); ?>
                        </p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Calon Kandidat</b> <a class="pull-right"><?= $t_kandidat; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Jumlah Pemilih</b> <a class="pull-right"><?= $t_peserta; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Status Pemilihan</b> <a class="pull-right"><?= $status($media['mulai_pemilihan'], $media['batas_pemilihan']); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Kandidat Terpilih</b> <a class="pull-right"><?= (is_null($kandidat_terpilih)) ? "---" :  $kandidat_terpilih['nama_kandidat']; ?></a>
                            </li>
                        </ul>

                        <!-- Tombol Salin Link Pemilihan -->
                        <p style="text-align: center;">Link pemilihan untuk peserta.</p>
                        <a href="<?= base_url() . $media['slug']; ?>" data-toggle="tab" class="btn btn-primary btn-block" id="copy-link-btn">
                            <i class="fa fa-share" id="link"></i> <b>Salin Link</b>
                        </a>

                        <!-- Tempat untuk menampilkan pesan pop-up -->
                        <div id="popup-message" class="alert alert-warning" style="display:none;">
                            Link berhasil disalin!
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
            <div class="col-md-9">
                <?php if (session()->getFlashdata('pesan')) : ?>
                    <div class="alert <?= session()->getFlashdata('alert'); ?> alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?= session()->getFlashdata('pesan'); ?>
                    </div>
                <?php endif ?>
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#kandidat" data-toggle="tab">Kandidat</a></li>
                        <li><a href="#daftar-peserta" data-toggle="tab">Daftar Pemilih</a></li>
                        <li><a href="#hasil" data-toggle="tab">Hasil</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="kandidat">
                            <div class="tombol-crud">
                                <a href="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/tambah_kandidat'; ?>" class="btn btn-primary"><i class="fa fa-plus-square"></i> Tambah Kandidat</a>
                            </div>
                            <?php if (empty($kandidat)) : ?>
                                <p class="text-center">--- Belum ada data kandidat untuk pemilihan ---</p>
                            <?php else : ?>
                                <?php foreach ($kandidat as $row) :
                                ?>
                                    <div class="post">
                                        <div class="kandidat-block">
                                            <div class="nomor-urut"><span><?= $row['nomor_urut']; ?></span></div>
                                            <img class="img-bordered-sm" src="/img/kandidat/<?= $row['poster']; ?>" alt="kandidat image">
                                            <div class="kandidat-info">
                                                <?php
                                                $nama_kandidat = explode(',', $row['nama_kandidat']);

                                                foreach ($nama_kandidat as $key => $value) : ?>
                                                    <span class="kandidatname"><?= $value; ?></span><br>
                                                <?php endforeach ?>
                                                <div class="program">
                                                    <h4><strong>Visi</strong></h4>
                                                    <p>
                                                        <?= $row['visi']; ?>
                                                    </p>
                                                    <h4><strong>Misi</strong></h4>
                                                    <p>
                                                        <?= $row['misi']; ?>
                                                    </p>
                                                </div>
                                                <a href="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/edit_kandidat/' . $row['kandidat_id'] ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Ubah</a>
                                                <button class="btn btn-danger" data-toggle="modal" data-target="#hapus-kandidat<?= $row['kandidat_id'] ?>"><i class="fa fa-trash"></i> Hapus</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="daftar-peserta">
                            <div class="tombol-crud">
                                <a href="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/upload_csv'; ?>" class="btn btn-success"><i class="fa fa-upload"></i> Import Peserta (.csv)</a>
                                <a href="<?= base_url('/admin/export/peserta/') . $media['slug']; ?>" class="btn btn-success"><i class="fa fa-file"></i> Export Peserta (.csv)</a>
                            </div>

                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Daftar seluruh media pemilihan</h3>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="grup-tombol">
                                        <a href="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/tambah_peserta'; ?>" class="btn btn-primary"><i class="fa fa-plus-square"></i> Tambah Peserta</a>
                                    </div>
                                    <div class="responsive">
                                        <?php if (empty($peserta)) : ?>
                                            <p class="text-center">--- Belum ada data peserta untuk pemilihan ---</p>
                                        <?php else : ?>
                                            <table id="table-peserta" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Lengkap</th>
                                                        <th>Nomor Whatsapp</th>
                                                        <th>Jenis Kelamin</th>
                                                        <th>Tanggal Lahir</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($peserta as $row) : ?>
                                                        <tr>
                                                            <td><?= $no++; ?></td>
                                                            <td class="text-capitalize"><?= $row['nama_lengkap']; ?>
                                                            <td><?= $row['nomor_whatsapp']; ?>
                                                            <td><?= $row['jenis_kelamin']; ?></td>
                                                            <td><?= format_tanggal_lahir($row['tanggal_lahir']); ?></td>
                                                            <td>
                                                                <a href="<?= base_url('admin/media_pemilihan/') . $row['slug'] . '/edit_peserta/' . $row['peserta_id']; ?>" class="btn btn-warning"><i class="fa fa-edit"></i> Ubah</a>
                                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapus-peserta<?= $row['peserta_id'] ?>"><i class="fa fa-trash-o"></i> Hapus
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->

                            <!-- button clear -->
                            <button class="btn btn-default" data-toggle="modal" data-target="#hapus-all-peserta<?= $media['slug'] ?>"><i class="fa fa-trash"></i> Bersihkan data peserta</button>
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="hasil">
                            <div class="detail-hasil">
                                <table>
                                    <tr>
                                        <td>Jumlah Pemilih Terdaftar</td>
                                        <td> : </td>
                                        <td><?= $hasil['jumlah_pemilih'] ?> Orang</td>
                                    </tr>
                                    <tr>
                                        <td>Telah Memilih</td>
                                        <td> : </td>
                                        <td><?= $hasil['sudah_memilih'] ?> Orang</td>
                                    </tr>
                                    <tr>
                                        <td>Tidak Memilih</td>
                                        <td> : </td>
                                        <td><?= $hasil['tidak_memilih'] ?> Orang</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- CHART -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Persentase Perolehan Suara</h3>
                                </div>
                                <div class="box-body">
                                    <div class="chart">
                                        <canvas id="myChart"></canvas>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Modal Hapus Kandidat -->
<?php
foreach ($kandidat as $row) : ?>
    <div class=" modal modal-default fade" id="hapus-kandidat<?= $row['kandidat_id'] ?>">
        <form action="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/remove_kandidat/' . $row['kandidat_id']; ?>" method="post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Hapus Permanen</h4>
                    </div>
                    <div class="modal-body">
                        <?php
                        $kandidat = explode(',', $row['nama_kandidat']);
                        if (count($kandidat) > 1) : ?>
                            <p>Apakah anda yakin ingin menghapus data kandidat <b><?= $kandidat[0]; ?> & <?= $kandidat[1]; ?></b> ini?..</p>
                        <?php else : ?>
                            <p>Apakah anda yakin ingin menghapus data <b><?= $kandidat[0]; ?></b> ini?..</p>
                        <?php endif ?>
                        <strong>Perhatian! Data berikut akan ikut dihapus:</strong>
                        <ol>
                            <li>Seluruh hak suara yang diberikan oleh peserta untuk kandidat tersebut akan dihapus secara permanen pula!</li>
                        </ol>
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
<!-- end hapus kandidat -->

<!-- Modal Hapus Peserta -->
<?php
foreach ($peserta as $row) : ?>
    <div class="modal modal-default fade" id="hapus-peserta<?= $row['peserta_id'] ?>">
        <form action="<?= base_url('/admin/media_pemilihan/') . $row['slug'] . '/remove_peserta/' . $row['peserta_id']; ?>" method="post">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Hapus Permanen</h4>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus data <b><?= $row['nama_lengkap']; ?> - <?= $row['nomor_whatsapp']; ?></b> ini?..</p>
                        <strong>Hak suara yang diberikan juga akan terhapus!</strong>
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

<!-- model hapus seluruh peserta -->
<!-- Modal Hapus -->
<div class="modal modal-default fade" id="hapus-all-peserta<?= $media['slug'] ?>">
    <form action="<?= base_url('/admin/media_pemilihan/') . $media['slug'] . '/remove_all_peserta' ?>" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Bersihkan Data Peserta</h4>
                </div>
                <div class="modal-body">
                    <p>Apakah anda yakin ingin menghapus seluruh data peserta <b><?= ucwords($media['judul_pemilihan']); ?></b> ini secara permanen, data tidak bisa dikembalikan.</p>
                    <h5>PERHATIAN!</h5>
                    <ol>
                        <li>Bila telah dilakukan pemilihan ketua seluruh data pemilihan akan terhapus.</li>
                        <li>Tidak dapat mengembalikan data-data yang telah terhapus.</li>
                    </ol>

                    <p><b>Konfirmasi</b> dengan mengetikan dibawah ini (tidak termasuk kutip 2 ("")) <i><br>
                            "Hapus <?= ucwords($media['judul_pemilihan']); ?></i>"</p>
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
<!-- end hapus seluruh peserta -->

<?= $this->endSection(); ?>