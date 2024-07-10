<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 2.4.18
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights
    reserved.
</footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- date-range-picker -->
<script src="/assets/bower_components/moment/min/moment.min.js"></script>
<script src="/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- FastClick -->
<script src="/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/assets/dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="/assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="/assets/dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/assets/dist/js/demo.js"></script>
<!-- CK Editor -->
<script src="/assets/bower_components/ckeditor/ckeditor.js"></script>
<!-- ChartJS -->
<script src="/js/chart.js"></script>
<!-- Javascript -->
<script>
    let initialNamaWakil;
    let slug;
    <?php if (isset($wakil)) { ?>
        initialNamaWakil = <?= json_encode($wakil) ?>;
    <?php } else { ?>
        // Alternatif jika $wakil tidak didefinisikan
        initialNamaWakil = null;
    <?php } ?>

    <?php if (isset($media['slug'])) { ?>
        slug = <?= json_encode($media['slug']) ?>;
    <?php } else { ?>
        // Alternatif jika $wakil tidak didefinisikan
        slug = null;
    <?php } ?>
</script>


<script src="/js/script.js"></script>
<script>
    const ctx = document.getElementById('myChart');

    const data = {
        labels: [
            <?php if (!empty($hasil)) {
                echo $hasil['daftar_kandidat'];
            } ?>
        ],
        datasets: [{
            label: 'Perolehan suara (%)',
            data: [
                <?php if (!empty($hasil)) {
                    echo $hasil['perolehan_suara'];
                } ?>
            ],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)',
                'rgb(000, 000, 000)',
                'rgb(25, 205, 86)',
                'rgb(100, 000, 000)',
            ],
            hoverOffset: 4
        }]
    };

    const config = {
        type: 'pie',
        data: data,
    };

    new Chart(ctx, config);
</script>
</body>

</html>