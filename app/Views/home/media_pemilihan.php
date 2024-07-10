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
                <li class="active"><?= ucwords($media['judul_pemilihan']); ?></li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row keterangan">
                <div class="col-md-5">
                    <div class="box">
                        <div class="box-body" id="keterangan">
                            <h5>Keterangan</h5>
                            <table>
                                <tr>
                                    <td>Pelaksanaan</td>
                                    <td>:</td>
                                    <td>
                                        <?= format_datetime($media['mulai_pemilihan']) . '</br>' . format_datetime($media['batas_pemilihan']); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Pemilihan</td>
                                    <td>:</td>
                                    <td>
                                        <?= ($getStatus($media['mulai_pemilihan'], $media['batas_pemilihan'])); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sisa Waktu</td>
                                    <td>:</td>
                                    <td id="sisa-waktu">Loading...</td>
                                </tr>
                                <?php if (($getStatus($media['mulai_pemilihan'], $media['batas_pemilihan'])) == "Selesai") : ?>
                                    <tr class="text-center">
                                        <td colspan="3"><button data-toggle="modal" data-target="#hasil<?= $media['slug'] ?>" class="btn btn-info">Lihat Hasil <i class="fa fa-check-square-o"></i></button></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                <?php endif ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Calon Kandidat</h3>
            <div class="row">
                <?php if (empty($kandidat)) : ?>
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body text-center">
                                <p> === Data calon kandidat pada pemilihan ini belum tersedia ====</p>
                            </div>
                        </div>
                    </div>
                    <?php
                else :
                    foreach ($kandidat as $row) : ?>
                        <div class="col-md-4">
                            <!-- Box Comment -->
                            <div class="box box-widget">
                                <div class="box-header with-border">
                                    <div class="kandidat-block">
                                        <div class="nomor-urut"><span><?= $row['nomor_urut']; ?></span></div>
                                        <?php
                                        // berfungsi untuk menangani bila kandidat terdapat 2 nama calon kandidat
                                        $namaKandidat = explode(',', $row['nama_kandidat']); ?>

                                        <span class="kandidat-name"><?= (count($namaKandidat) == 1) ? '<a href="#">' . $namaKandidat[0] . '</a>' : '<a href="#">' . $namaKandidat[0] . '</a><br><a href="#">' . $namaKandidat[1] . '</a>'; ?></span>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="konten-gambar">
                                        <img class="img-responsive kandidat-img" src="img/kandidat/<?= $row['poster']; ?>" alt="Photo">
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <!-- /.box-footer -->
                                <div class="box-footer">
                                    <div class="visi text-center">
                                        <h4>Visi</h4>
                                        <span><?= $row['visi'] ?></span>
                                    </div>
                                    <div class="misi">
                                        <h4 class="text-center">Misi</h4>
                                        <span><?= $row['misi'] ?></span>
                                    </div>
                                    <div class="text-center">
                                        <?php if ($getStatus($media['mulai_pemilihan'], $media['batas_pemilihan']) == "Berlangsung") :
                                        ?>
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#vote<?= $row['kandidat_id'] ?>"> Vote <i class="fa fa-pencil"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <!-- /.box-footer -->
                            </div>
                            <!-- /.box -->
                        </div>
                    <?php endforeach ?>
                <?php endif; ?>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.container -->
</div>
<!-- /.content-wrapper -->

<!-- modal vote -->

<?php
foreach ($kandidat as $row) : ?>
    <div class="modal fade" id="vote<?= $row['kandidat_id']; ?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Minta Kode OTP</h4>
                </div>
                <div class="modal-body">
                    <p>Perhatian!</p>
                    <ol>
                        <li>Kami harap anda telah membaca visi misi kandidat ini.</li>
                        <li>Pastikan sebelum melakukan minta kode otp, Whatsapp anda aktif.</li>
                        <li>Setalah menekan minta kode otp mohon tidak menutup halaman, karena setelahnya anda harus memasukkan kode otp yang diterima untuk melanjutkan penyerahan hak suara anda.</li>
                    </ol>

                    <?php
                    $namaKandidat = explode(',', $row['nama_kandidat']);
                    ?>

                    <p>Yang akan dipilih <b><?= (count($namaKandidat)  == 1) ? $namaKandidat[0] : $namaKandidat[0] . ' & ' . $namaKandidat[1]; ?></b></p>

                    <!-- form nomor whatsapp -->
                    <form action="<?= base_url('/') . $media['slug'] . '/' . $row['kandidat_id'] . '/mintaKodeOtp'; ?>" method="post">
                        <input type="number" class="form-control" name="nomor_whatsapp" placeholder="cnth: 081233445566">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Minta <i class="fa fa-send-o"></i></button>
                </div>
                </form>
                <!-- end form nomor whatsapp -->
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
<?php endforeach; ?>

<!-- modal hasil -->
<div class="modal fade" id="hasil<?= $media['slug']; ?>">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Hasil Pemilihan</h4>
            </div>
            <div class="modal-body">
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
                <div class="chart">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">OK</button>
            </div>
            </form>
            <!-- end form nomor whatsapp -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- end modal hasil -->
<?= $this->endSection(); ?>