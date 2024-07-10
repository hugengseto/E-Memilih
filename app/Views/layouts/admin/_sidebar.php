<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/assets/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= session('username'); ?></p>
                <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> -->
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU UTAMA</li>
            <li class="<?= uri_string() == 'admin/dashboard' ? 'active' : ''; ?>"><a href="<?= base_url('/admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <?php if (!isset($media)) { ?>
                <li class="<?= (uri_string() == 'admin/media_pemilihan' || uri_string() == 'admin/media_pemilihan/tambah') ? 'active' : ''; ?>"><a href="<?= base_url('/admin/media_pemilihan'); ?>"><i class="fa  fa-floppy-o"></i> <span>Media Pemilihan</span></a></li>
                <?php } else {
                if (empty($media['slug'])) { ?>
                    <li class="<?= (uri_string() == 'admin/media_pemilihan' || uri_string() == 'admin/media_pemilihan/tambah') ? 'active' : ''; ?>"><a href="<?= base_url('/admin/media_pemilihan'); ?>"><i class="fa  fa-floppy-o"></i> <span>Media Pemilihan</span></a></li>
                <?php } else { ?>
                    <li class="<?= (uri_string() == 'admin/media_pemilihan' || uri_string() == 'admin/media_pemilihan/tambah' || uri_string() == 'admin/media_pemilihan/edit/' . $media['slug'] || 'admin/media_pemilihan/detail/' . $media['slug']) ? 'active' : ''; ?>"><a href="<?= base_url('/admin/media_pemilihan'); ?>"><i class="fa  fa-floppy-o"></i> <span>Media Pemilihan</span></a></li>
                <?php } ?>
            <?php } ?>

            <li class="header">PENGATURAN</li>
            <li class="<?= uri_string() == 'admin/profil' ? 'active' : ''; ?>"><a href="<?= base_url('/admin/profil'); ?>"><i class="fa fa-user"></i> <span>Profil</span></a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>