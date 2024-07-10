<?= $this->extend('layouts/admin/template_admin'); ?>

<?= $this->section('content'); ?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Profil
        </h1>
        <ol class="breadcrumb">
            <li class="<?= uri_string() == 'admin/profil' ? 'active' : ''; ?>"><i class="fa fa-user"></i> Profil</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row profil">
            <div class="col-md-6">
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="alert <?= session()->getFlashdata('alert'); ?> alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <?= session()->getFlashdata('message'); ?>
                    </div>
                <?php endif ?>
                <!-- About Me Box -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Pengaturan <i class="fa fa-gear"></i></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="<?= base_url('/admin/update/profil/') . session('username'); ?>" method="post">
                            <strong><span style="color: red;">* </span><i class="fa fa-user margin-r-5"></i> Username</strong>
                            <div class="form-group <?= session('errors') && isset(session('errors')['username']) ? 'has-error' : ''; ?>">
                                <input type="text" class="form-control" name="username" id="username" value="<?= old($user['full_name']) ? old($user['full_name']) : $user['full_name']; ?>">

                                <?php if (session('errors') && isset(session('errors')['username'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['username']); ?></span>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <strong><span style="color: red;">* </span><i class="fa fa-envelope margin-r-5"></i> Email</strong>
                            <div class="form-group <?= session('errors') && isset(session('errors')['email']) ? 'has-error' : ''; ?>">
                                <input type="text" class="form-control" name="email" id="email" value="<?= old($user['email']) ? old($user['email']) : $user['email']; ?>">

                                <?php if (session('errors') && isset(session('errors')['email'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['email']); ?></span>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <strong><i class="fa fa-key margin-r-5"></i> Change Password</strong>

                            <input type="text" class="form-control" name="newPassword" id="newPassword" placeholder="New Password" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirm New Password">

                            <hr>

                            <strong><span style="color: red;">* </span><i class="fa fa-key margin-r-5"></i> Confirm Old Password</strong>


                            <div class="form-group <?= session('errors') && isset(session('errors')['oldPassword']) ? 'has-error' : ''; ?>">
                                <input type="password" class="form-control" name="oldPassword" id="oldPassword" placeholder="Password lama untuk melanjutkan proses update">

                                <?php if (session('errors') && isset(session('errors')['oldPassword'])) : ?>
                                    <span class="help-block"><?= esc(session('errors')['oldPassword']); ?></span>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<?= $this->endSection(); ?>