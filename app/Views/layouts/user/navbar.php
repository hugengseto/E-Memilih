<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="<?= base_url(); ?>" class="navbar-brand">E-<b>Memilih</b></a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <?php if (!empty($media) && !empty($media['slug'])) { ?>
                        <li class="<?= (uri_string() == $media['slug']) ? 'active' : ''; ?>"><a href="<?= base_url() ?>">Utama</a></li>
                    <?php } else { ?>
                        <li class="<?= (uri_string() == '') ? 'active' : ''; ?>"><a href="<?= base_url() ?>">Utama</a></li>
                    <?php } ?>
                    <li class="<?= (uri_string() == 'tentang') ? 'active' : ''; ?>"><a href="<?= base_url('/tentang'); ?>">Tentang</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>