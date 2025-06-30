<?php $this->view('templates/header', $data); ?>

<!-- … navbar sudah ada digital clock Anda … … -->

<div class="container mt-5">


    <?php if ($_SESSION['role'] === 'kasir'):

        $mine = $kasirData[array_search($_SESSION['nama'], array_column($kasirData, 'kasir'))] ?? null;
        $myPend = $mine['total_pendapatan'] ?? 0;
        $myTrans = $mine['total_transaksi']   ?? 0;
    ?>
        <div class="row justify-content-center mb-5">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card text-white mx-auto bg-primary mb-5 text-center">
                    <div class="card-header fs-4">
                        Total Pendapatan Anda
                    </div>
                    <div class="card-body">
                        <h1 class="card-title display-3 mb-3">
                            Rp <?= number_format((float)$myPend, 0, ',', '.') ?>
                        </h1>
                        <small class="fs-5">Transaksi: <?= $myTrans ?></small>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <?php if ($_SESSION['role'] === 'admin'): ?>
        <div class="row mb-3">
            <div class="col text-center">
                <h5 class="mt-4">Pendapatan per Kasir</h5>
            </div>
        </div>
        <div class="row">
            <?php foreach ($kasirData as $k): ?>
                <?php
                // Abaikan jika user ini role-nya "gudang"
                if (($k['role'] ?? null) === 'gudang') {
                    continue;
                }
                $pendapatan    = $k['total_pendapatan'] ?? 0;
                $totalTransaksi = $k['total_transaksi']   ?? 0;
                ?>
                <div class="col-md-4 mb-3">
                    <div class="card">
                        <div class="card-header"><?= htmlspecialchars($k['kasir']) ?></div>
                        <div class="card-body">
                            <h5 class="card-title mb-0">
                                Rp <?= number_format((float)$pendapatan, 0, ',', '.') ?>
                            </h5>
                            <small class="text-muted">
                                Transaksi: <?= $totalTransaksi ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


    <div class="row mb-3">
        <div class="col text-center">
            <h5>Penjualan per Jurusan</h5>
        </div>
    </div>
    <div class="chart-container" style="height:400px;">
        <canvas id="chartJurusan"></canvas>
    </div>



    <?php if (in_array($_SESSION['role'], ['admin', 'gudang'])): ?>
        <div class="row mb-3">
            <div class="col text-center">
                <h5 class="mt-4">Rekap Pengambilan</h5>
            </div>
        </div>
        <div class="chart-container mb-5" style="height:400px;">
            <canvas id="chartKasir"></canvas>
        </div>
    <?php endif; ?>


</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // data jurusan
        const jurusanData = <?= json_encode($jurusanData) ?>;

        const labels = jurusanData.map(r => r.jurusan);
        const totalSiswa = jurusanData.map(r => parseInt(r.total_siswa, 10));
        const totalLunas = jurusanData.map(r => parseInt(r.total_lunas, 10));
        const belumBeli = totalSiswa.map((ts, i) => ts - totalLunas[i]);

        new Chart(
            document.getElementById('chartJurusan').getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                            label: 'Sudah Beli',
                            data: totalLunas,
                            backgroundColor: '#36A2EB'
                        },
                        {
                            label: 'Belum Beli',
                            data: belumBeli,
                            backgroundColor: '#FF6384'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );

        <?php if ($_SESSION['role'] === 'gudang' || $_SESSION['role'] === 'admin'): ?>
            // pie chart pengambilan
            new Chart(
                document.getElementById('chartKasir').getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: ['Diambil', 'Belum Diambil'],
                        datasets: [{
                            data: [<?= $taken ?>, <?= $notTaken ?>],
                            backgroundColor: ['#4BC0C0', '#FF6384']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                }
            );
        <?php endif; ?>
    });
</script>

<?php $this->view('templates/footer'); ?>