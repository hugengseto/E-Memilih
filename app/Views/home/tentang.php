<?= $this->extend('layouts/user/template_user'); ?>

<?= $this->section('content'); ?>

<!-- Full Width Column -->
<div class="content-wrapper">
    <div class="container">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Tentang
                <small>Dibuat oleh</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="col-md-4">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-black-active">
                        <h3 class="widget-user-username">Hugeng Seto Pranowo</h3>
                        <a href="https://github.com/hugengseto">Github <i class="fa fa-github"></i></a>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">***</h5>
                                    <span class="description-text">***</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">Instagram</h5>
                                    <span>@hugengseto</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">***</h5>
                                    <span class="description-text">***</span>
                                </div>
                                <!-- /.description-block -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <!-- /.col -->
        </section>
    </div>
</div>

<?= $this->endSection(); ?>