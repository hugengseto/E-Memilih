<footer class="main-footer">
    <div class="container">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.18
        </div>
        <strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE</a>.</strong> All rights
        reserved.
    </div>
    <!-- /.container -->
</footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/assets/dist/js/demo.js"></script>
<!-- SweetAlert2 -->
<script src="/js/sweetalert2.all.min.js"></script>
<!-- ChartJS -->
<script src="/js/chart.js"></script>
<!-- cusom js -->
<script>
    // Cek apakah ada pesan kesalahan dalam sesi
    <?php if (session()->has('errors')) : ?>
        // Ambil pesan kesalahan dari sesi
        const errors = <?= json_encode(session('errors')); ?>;

        // Gabungkan pesan kesalahan menjadi satu string
        let errorMessage = '';
        for (const [key, value] of Object.entries(errors)) {
            errorMessage += `${value}\n`;
        }

        // Tampilkan SweetAlert dengan pesan kesalahan
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessage
        });
    <?php elseif (session()->has('status')) : ?>
        // Ambil pesan kesalahan dari sesi
        const status = <?= json_encode(session('status')); ?>;

        // Gabungkan pesan kesalahan menjadi satu string
        let successMessage = '';
        for (const [key, value] of Object.entries(status)) {
            successMessage += `${value}\n`;
        }

        // Tampilkan SweetAlert dengan pesan kesalahan
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: successMessage
        });
    <?php endif; ?>

    {
        // penangan waktu sisa
        function startCountdown(endTime, display) {
            const interval = setInterval(() => {
                const now = new Date();
                const timeRemaining = endTime - now;

                if (timeRemaining <= 0) {
                    clearInterval(interval);
                    display.textContent = "00:00:00"; // Waktu habis
                    return;
                }

                const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
                const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                display.textContent =
                    (hours < 10 ? "0" + hours : hours) + ":" +
                    (minutes < 10 ? "0" + minutes : minutes) + ":" +
                    (seconds < 10 ? "0" + seconds : seconds);
            }, 1000);
        }
        <?php if (!empty($media['batas_pemilihan'])) { ?>
            <?php if ($getStatus($media['mulai_pemilihan'], $media['batas_pemilihan']) == "Berlangsung") :
            ?>
                window.onload = function() {
                    const endTime = new Date("<?= $media['batas_pemilihan']; ?>");
                    const sisaWaktu = document.getElementById('sisa-waktu');
                    startCountdown(endTime, sisaWaktu);
                };
            <?php else : ?>
                window.onload = function() {
                    const sisaWaktu = document.getElementById('sisa-waktu');
                    sisaWaktu.textContent = "-- : -- : --"
                };
            <?php endif; ?>
        <?php } ?>

    }

    {
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
                ],
                hoverOffset: 4
            }]
        };

        const config = {
            type: 'pie',
            data: data,
        };

        new Chart(ctx, config);
    }
</script>
</body>

</html>