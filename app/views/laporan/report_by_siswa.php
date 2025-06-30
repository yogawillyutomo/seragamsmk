<?php $this->view('templates/header', $data); ?>

<h3><?= $judul ?></h3>

<form class="row g-3 mb-4" method="get">
    <div class="col-md-3">
        <input type="text" name="keyword"
            value="<?= htmlspecialchars($filters['keyword']) ?>"
            class="form-control" placeholder="Cari Nama Siswa…">
    </div>
    <div class="col-md-3">
        <select name="jurusan" class="form-select">
            <option value="">— Semua Jurusan —</option>
            <?php foreach ($jurusans as $j): ?>
                <option value="<?= $j ?>" <?= $filters['jurusan'] === $j ? 'selected' : '' ?>>
                    <?= htmlspecialchars($j) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <input type="date" name="dari" value="<?= $filters['dari'] ?>" class="form-control">
    </div>
    <div class="col-md-2">
        <input type="date" name="sampai" value="<?= $filters['sampai'] ?>" class="form-control">
    </div>
    <div class="col-md-2 d-grid">
        <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
    </div>
</form>
<table class="table table-bordered" id="tblSiswa">
    <thead>
        <tr>
            <th>No</th>
            <th>Siswa</th>
            <th>Jurusan</th>
            <th>Total Beli</th>
            <th>Total Diambil</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = ($currentPage - 1) * $filters['limit'] + 1; ?>
        <?php foreach ($reports as $r): ?>
            <tr data-id="<?= $r['siswa_id'] ?>">
                <td><?= $no++ ?></td>
                <td class="nama"><?= htmlspecialchars($r['siswa']) ?></td>
                <td><?= htmlspecialchars($r['jurusan']) ?></td>
                <td><?= $r['total_beli'] ?></td>
                <td><?= $r['total_diambil'] ?></td>
                <td>
                    <button class="btn btn-sm btn-info btn-detail"
                        data-id="<?= $r['siswa_id'] ?>">
                        Detail
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($reports)): ?>
            <tr>
                <td colspan="6" class="text-center">Tidak ada data.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>


<?php
// Konfigurasi
$maxPages    = 5;
$totalPages  = max(1, $totalPages);
$currentPage = max(1, min($currentPage, $totalPages));

// Hitung rentang halaman yang akan ditampilkan
$half        = floor($maxPages / 2);
$start       = max(1, $currentPage - $half);
$end         = min($totalPages, $currentPage + $half);

// Jika di ujung bawah, geser rentang ke atas
if ($currentPage - $start < $half) {
    $end = min($totalPages, $start + $maxPages - 1);
}
// Jika di ujung atas, geser rentang ke bawah
if ($end - $currentPage < $half) {
    $start = max(1, $end - $maxPages + 1);
}
?>

<nav>
    <ul class="pagination justify-content-center">
        <!-- Prev -->
        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
            <a class="page-link"
                href="?<?= http_build_query(array_merge($filters, ['page' => $currentPage - 1])) ?>">
                &laquo;
            </a>
        </li>

        <!-- Halaman pertama & ellipsis awal -->
        <?php if ($start > 1): ?>
            <li class="page-item">
                <a class="page-link"
                    href="?<?= http_build_query(array_merge($filters, ['page' => 1])) ?>">
                    1
                </a>
            </li>
            <?php if ($start > 2): ?>
                <li class="page-item disabled"><span class="page-link">…</span></li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Halaman tengah -->
        <?php for ($p = $start; $p <= $end; $p++): ?>
            <li class="page-item <?= $p == $currentPage ? 'active' : '' ?>">
                <a class="page-link"
                    href="?<?= http_build_query(array_merge($filters, ['page' => $p])) ?>">
                    <?= $p ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Ellipsis akhir & Halaman terakhir -->
        <?php if ($end < $totalPages): ?>
            <?php if ($end < $totalPages - 1): ?>
                <li class="page-item disabled"><span class="page-link">…</span></li>
            <?php endif; ?>
            <li class="page-item">
                <a class="page-link"
                    href="?<?= http_build_query(array_merge($filters, ['page' => $totalPages])) ?>">
                    <?= $totalPages ?>
                </a>
            </li>
        <?php endif; ?>

        <!-- Next -->
        <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
            <a class="page-link"
                href="?<?= http_build_query(array_merge($filters, ['page' => $currentPage + 1])) ?>">
                &raquo;
            </a>
        </li>
    </ul>
</nav>


<div class="d-flex mb-3">
    <button id="btnExportPdf" class="btn btn-danger me-2">Export PDF</button>
    <button id="btnExportExcel" class="btn btn-success">Export Excel</button>
</div>


<!-- Modal -->
<div class="modal fade" id="modalDetailSiswa" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Detail Pembelian<br>
                    <small id="detailHeader"></small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Transaksi ID</th>
                            <th>Kode</th>
                            <th>Seragam</th>
                            <th>Ukuran</th>
                            <th>Hijab</th>
                            <th>Harga</th>
                            <th>Status Ambil</th>
                        </tr>
                    </thead>
                    <tbody id="detailSiswaBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('.btn-detail').click(function() {
        const sid = $(this).data('id');
        // Build header: dapatkan nama & jurusan dari baris tabel
        const row = $(this).closest('tr');
        const nama = row.find('td:nth-child(2)').text();
        const jur = row.find('td:nth-child(3)').text();
        $('#detailHeader').text(`${nama} — ${jur}`);

        $.getJSON('<?= BASEURL ?>/laporan/detailSiswa/' + sid, items => {
            let html = '';
            items.forEach(it => {

                const badgeClass = it.status_ambil === 'diambil' ?
                    'badge bg-primary' :
                    'badge bg-warning text-dark';

                html += `<tr>
        <td>${it.transaksi_id}</td>
        <td>${it.kode_transaksi}</td>
        <td>${it.seragam}</td>
        <td>${it.ukuran}</td>
        <td>${it.berhijab? 'Ya':'Tidak'}</td>
        <td>Rp ${Number(it.harga).toLocaleString()}</td>
        <td>
            <span class="${badgeClass}">
            ${it.status_ambil.charAt(0).toUpperCase() + it.status_ambil.slice(1)}
            </span>
        </td>
      </tr>`;
            });
            $('#detailSiswaBody').html(html);
            $('#modalDetailSiswa').modal('show');
        });
    });
</script>

<!-- jsPDF & AutoTable -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>




<script>
    // EXPORT PDF
    document.getElementById('btnExportPdf').addEventListener('click', async () => {
        const {
            jsPDF
        } = window.jspdf;
        const doc = new jsPDF({
            orientation: 'landscape'
        });
        doc.setFontSize(14);
        doc.text('Laporan Per Siswa (Detail Seragam)', 14, 16);

        const header = ['Siswa', 'Kode Transaksi', 'Seragam', 'Ukuran', 'Jenis', 'Harga', 'Status Ambil'];
        const body = [];

        // Counters untuk rekap
        let totalBeli = 0;
        let totalDiambil = 0;

        // Ambil data detail per siswa
        for (const tr of document.querySelectorAll('#tblSiswa tbody tr')) {
            const siswaId = tr.dataset.id;
            const siswaName = tr.cells[1].innerText.trim();

            const res = await fetch(`<?= BASEURL ?>/laporan/detailSiswa/${siswaId}`);
            if (!res.ok) continue;
            const details = await res.json();

            details.forEach(item => {
                const hargaFormatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(item.harga);

                body.push([
                    siswaName,
                    item.kode_transaksi,
                    item.seragam,
                    item.ukuran,
                    item.berhijab == 1 ? 'Berhijab' : 'Biasa',
                    hargaFormatted,
                    item.status_ambil
                ]);

                totalBeli++;
                if (item.status_ambil === 'diambil') totalDiambil++;
            });
        }

        // Render tabel
        doc.autoTable({
            head: [header],
            body,
            startY: 20,
            styles: {
                fontSize: 9
            },
            headStyles: {
                fillColor: [220, 220, 220]
            }
        });

        // Setelah tabel, ambil posisi Y terakhir
        const finalY = doc.lastAutoTable.finalY || 20;

        // Hitung Belum Ambil
        const totalBelum = totalBeli - totalDiambil;

        // Tulis rekap
        doc.setFontSize(12);
        const startX = 14;
        let posY = finalY + 10;
        doc.text(`Total Beli       : ${totalBeli}`, startX, posY);
        doc.text(`Total Diambil    : ${totalDiambil}`, startX, posY + 7);
        doc.text(`Total Belum Ambil: ${totalBelum}`, startX, posY + 14);

        // Simpan file
        doc.save(`Laporan_Siswa_Detail_${new Date().toISOString().slice(0,10)}.pdf`);
    });


    document.getElementById('btnExportExcel').addEventListener('click', async () => {
        // 1) Siapkan header dan counters
        const detailAoA = [
            ['Siswa', 'Kode Transaksi', 'Seragam', 'Ukuran', 'Jenis', 'Harga', 'Status Ambil']
        ];
        let totalDibeli = 0;
        let totalDiambil = 0;
        let totalBelum = 0;

        // 2) Loop baris siswa, fetch detail, isi detailAoA + counters
        for (const tr of document.querySelectorAll('#tblSiswa tbody tr')) {
            const siswaId = tr.dataset.id;
            const siswaName = tr.cells[1].innerText.trim();

            const res = await fetch(`<?= BASEURL ?>/laporan/detailSiswa/${siswaId}`);
            if (!res.ok) {
                console.error(`Gagal fetch ${siswaId}`);
                continue;
            }
            const details = await res.json();

            details.forEach(item => {
                detailAoA.push([
                    siswaName,
                    item.kode_transaksi,
                    item.seragam,
                    item.ukuran,
                    item.berhijab == 1 ? 'Berhijab' : 'Biasa',
                    Number(item.harga),
                    item.status_ambil
                ]);

                totalDibeli++;
                if (item.status_ambil === 'diambil') totalDiambil++;
                else totalBelum++;
            });
        }

        // 3) Build workbook & detail sheet
        const wb = XLSX.utils.book_new();
        const wsDetail = XLSX.utils.aoa_to_sheet(detailAoA);

        // Tambahkan formula total harga di sheet detail (opsional)
        const dataEndRow = detailAoA.length;
        const lastDetailRow = dataEndRow + 1;
        XLSX.utils.sheet_add_aoa(wsDetail, [
            ['', '', '', '', 'Total Harga:', {
                t: 'n',
                f: `SUM(F2:F${dataEndRow})`
            }]
        ], {
            origin: -1
        });
        // Format sel Total Harga
        const totalHargaCell = `F${lastDetailRow+1}`;
        if (wsDetail[totalHargaCell]) {
            wsDetail[totalHargaCell].z = '"Rp "#,##0';
        }

        XLSX.utils.book_append_sheet(wb, wsDetail, 'DetailSeragam');

        // 4) Buat sheet Rekap
        const rekapAoA = [
            ['Keterangan', 'Jumlah'],
            ['Total Dibeli', totalDibeli],
            ['Total Diambil', totalDiambil],
            ['Total Belum Ambil', totalBelum]
        ];
        const wsRekap = XLSX.utils.aoa_to_sheet(rekapAoA);

        // (opsional) format angka di kolom Jumlah sebagai number
        for (let R = 1; R <= 3; ++R) {
            const cell = wsRekap[`B${R+1}`];
            if (cell) cell.t = 'n';
        }

        XLSX.utils.book_append_sheet(wb, wsRekap, 'Rekap');

        // 5) Ekspor file
        XLSX.writeFile(
            wb,
            `Laporan_Siswa_Detail_Rekap_${new Date().toISOString().slice(0,10)}.xlsx`
        );
    });
</script>




<?php $this->view('templates/footer'); ?>