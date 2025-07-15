<?php $this->view('templates/header', $data); ?>


<!-- Tambahkan di <head> -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- Tambahkan sebelum </body> -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<style>
    .pagination {
        flex-wrap: wrap;
    }

    .pagination .page-item {
        margin: 0 2px;
    }
</style>

<div class="container mt-4">
    <h2>Laporan</h2>

    <form method="GET" class="row g-3 mb-4">
        <?php if ($data['role'] === 'admin'): ?>
            <div class="col-md-3">
                <label>Kasir</label>
                <select name="kasir[]" class="form-control select2" multiple>
                    <?php foreach ($data['list_kasir'] as $k): ?>
                        <option value="<?= $k['id'] ?>"
                            <?= in_array($k['id'], $data['filters']['kasir'], true) ? 'selected' : '' ?>>
                            <?= $k['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="col-md-3">
            <label>Jurusan</label>
            <select name="jurusan[]" class="form-control select2" multiple>
                <?php foreach ($data['list_jurusan'] as $j): ?>
                    <option value="<?= $j['jurusan'] ?>"
                        <?= in_array($j['jurusan'], $data['filters']['jurusan'], true) ? 'selected' : '' ?>>
                        <?= $j['jurusan'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <label>Status</label>
            <select name="status[]" class="form-select select2" multiple>
                <option value="lunas" <?= in_array('lunas', $data['filters']['status']) ? 'selected' : '' ?>>Lunas</option>
                <option value="diambil" <?= in_array('diambil', $data['filters']['status']) ? 'selected' : '' ?>>Diambil</option>
                <option value="batal" <?= in_array('batal', $data['filters']['status']) ? 'selected' : '' ?>>Batal</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="dari">Dari Tanggal</label>
            <input type="date" id="dari" name="dari" class="form-control"
                value="<?= htmlspecialchars($data['filters']['dari']) ?>">
        </div>

        <div class="col-md-2">
            <label for="sampai">Sampai Tanggal</label>
            <input type="date" id="sampai" name="sampai" class="form-control"
                value="<?= htmlspecialchars($data['filters']['sampai']) ?>">
        </div>

        <div class="col-md-2">
            <label>Keyword</label>
            <input type="text" name="keyword" class="form-control"
                value="<?= htmlspecialchars($data['filters']['keyword']) ?>"
                placeholder="Cari kode / nama..." />
        </div>

        <div class="col-md-1">
            <label>&nbsp;</label>
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="col-12 mb-3">
        <!-- <div class="text-center mt-2"> -->
        <button onclick="exportPDF()" class="btn btn-danger" id="exportPDF">
            <i class="fa fa-file-pdf"></i> Export PDF
        </button>

        <!-- <button onclick="exportExcel()" class="btn btn-success">
            <i class="fa fa-file-excel"></i> Export ke Excel
        </button> -->
        <button onclick="exportExcelMulti()" class="btn btn-success">
            <i class="fa fa-file-excel"></i> Export Excel
        </button>
        <!-- </div> -->
    </div>


    <div id="laporanTable">
        <?php if ($data['role'] === 'admin' || $data['role'] === 'kasir'): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Siswa</th>
                        <th>Jurusan</th>
                        <th>Metode</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = ($data['currentPage'] - 1) * $data['filters']['limit'] + 1; ?>
                    <?php foreach ($data['laporan'] as $row):
                        // di dalam loop fetch $row
                        $status = $row['status'];          // 'lunas', 'diambil' atau 'batal'
                        switch ($status) {
                            case 'lunas':
                                $badgeClass = 'badge bg-success';
                                break;
                            case 'diambil':
                                $badgeClass = 'badge bg-primary';
                                break;
                            case 'batal':
                                $badgeClass = 'badge bg-danger';
                                break;
                            default:
                                $badgeClass = 'badge bg-secondary';
                                break;
                        }
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['kode_transaksi']) ?></td>
                            <td><?= $row['tanggal_transaksi'] ?></td>
                            <td><?= htmlspecialchars($row['namakasir']) ?></td>
                            <td><?= htmlspecialchars($row['siswa']) ?></td>
                            <td><?= htmlspecialchars($row['jurusan']) ?></td>
                            <td><?= htmlspecialchars($row['metode_pembayaran']) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td><span class="<?= $badgeClass ?>">
                                    <?= ucfirst($status) ?>
                                </span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>



            <?php if ($data['role'] === 'admin' || $data['role'] === 'kasir'): ?>
                <?php
                $sum = $data['summary'];
                ?>
                <div class="alert alert-success mt-4">
                    <p><strong>Total Pendapatan:</strong> Rp <?= number_format($sum['total_pendapatan'], 0, ',', '.'); ?></p>
                    <div class="alert alert-info" style="display: none;">
                        <p><b>Total Lunas:</b> Rp <?= number_format($sum['total_lunas'],   0, ',', '.'); ?></p>
                        <p><b>Total Diambil:</b> Rp <?= number_format($sum['total_diambil'], 0, ',', '.'); ?></p>
                        <p><b>Total Batal:</b> Rp <?= number_format($sum['total_batal'],   0, ',', '.'); ?></p>
                    </div>
                </div>
            <?php endif; ?>


        <?php elseif ($data['role'] === 'gudang'): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Seragam</th>
                        <th>Total Terambil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['laporan'] as $row): ?>
                        <tr>
                            <td><?= $row['nama_seragam']; ?></td>
                            <td><?= $row['total_terambil']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <nav>
            <ul class="pagination justify-content-center">
                <!-- Prev -->
                <li class="page-item <?= $data['currentPage'] == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $data['currentPage'] - 1])) ?>">&laquo; Prev</a>
                </li>

                <?php
                $cp    = $data['currentPage'];
                $total = $data['totalPages'];
                $window = 4; // jumlah halaman sebelum & sesudah current

                $start = max(1, $cp - $window);
                $end   = min($total, $cp + $window);

                // Jika start > 1, tampilkan halaman 1 dan ellipsis
                if ($start > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => 1])) . '">1</a></li>';
                    if ($start > 2) {
                        echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                    }
                }

                // Loop halaman dari $start sampai $end
                for ($p = $start; $p <= $end; $p++) {
                    $active = $p == $cp ? 'active' : '';
                    echo '<li class="page-item ' . $active . '">';
                    echo '<a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $p])) . '">' . $p . '</a>';
                    echo '</li>';
                }

                // Jika end < total, tampilkan ellipsis dan halaman terakhir
                if ($end < $total) {
                    if ($end < $total - 1) {
                        echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                    }
                    echo '<li class="page-item"><a class="page-link" href="?' . http_build_query(array_merge($_GET, ['page' => $total])) . '">' . $total . '</a></li>';
                }
                ?>

                <!-- Next -->
                <li class="page-item <?= $cp == $total ? 'disabled' : '' ?>">
                    <a class="page-link" href="?<?= http_build_query(array_merge($_GET, ['page' => $cp + 1])) ?>">Next &raquo;</a>
                </li>
            </ul>
        </nav>


    </div>


</div>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Pilih --",
            allowClear: true,
            width: '100%'
        });
    });
</script>
<script>
    const BASE = "<?= BASEURL ?>";
    const API_BASE = `${BASE}/laporan/getAllLaporan`;

    // helper to always parse JSON (and log if it isn’t)
    async function fetchJson(url) {
        const res = await fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const text = await res.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error("⚠️ Expected JSON but got:", url, "\n", text);
            throw e;
        }
    }

    async function fetchAllLaporan() {
        // Ambil kembali semua parameter filter dari URL
        const qs = new URLSearchParams(window.location.search);
        ['url', 'page', 'limit', 'offset'].forEach(k => qs.delete(k));

        // Panggil endpoint langsung
        const res = await fetch(`${API_BASE}?${qs.toString()}`, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const {
            data
        } = await res.json();
        return data;
    }

    async function exportExcel() {
        try {
            const data = await fetchAllLaporan();
            const numbered = data.map((it, i) => ({
                No: i + 1,
                Kode: it.kode_transaksi,
                Tanggal: it.tanggal_transaksi,
                Kasir: it.namakasir,
                Siswa: it.siswa,
                Metode: it.metode_pembayaran,
                'Total Harga': `Rp ${new Intl.NumberFormat('id-ID').format(it.total_harga)}`,
                Status: it.status.charAt(0).toUpperCase() + it.status.slice(1),
            }));
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.json_to_sheet(numbered);
            XLSX.utils.book_append_sheet(wb, ws, "Transaksi");
            XLSX.writeFile(wb, `Laporan-Seragam-${new Date().toISOString().slice(0,10)}.xlsx`);
        } catch (e) {
            Swal.fire('Error', 'Gagal export Excel: ' + e.message, 'error');
        }
    }

    async function exportExcelMulti() {
        try {
            const data = await fetchAllLaporan();

            // 1) Sheet Transaksi
            const numbered = data.map((it, i) => ({
                No: i + 1,
                Kode: it.kode_transaksi,
                Tanggal: it.tanggal_transaksi,
                Kasir: it.namakasir,
                Siswa: it.siswa,
                Metode: it.metode_pembayaran,
                'Total Harga': `Rp ${new Intl.NumberFormat('id-ID').format(it.total_harga)}`,
                Status: it.status.charAt(0).toUpperCase() + it.status.slice(1),
            }));
            const tanggal = new Date().toLocaleDateString('id-ID');
            const wb = XLSX.utils.book_new();

            const ws1 = XLSX.utils.json_to_sheet(numbered, {
                origin: 'A3'
            });
            XLSX.utils.sheet_add_aoa(ws1, [
                ['Laporan Penjualan Bahan Seragam Sekolah']
            ], {
                origin: 'A1'
            });
            XLSX.utils.sheet_add_aoa(ws1, [
                [`Tanggal Export: ${tanggal}`]
            ], {
                origin: 'A2'
            });
            ws1['!merges'] = [{
                    s: {
                        r: 0,
                        c: 0
                    },
                    e: {
                        r: 0,
                        c: 6
                    }
                },
                {
                    s: {
                        r: 1,
                        c: 0
                    },
                    e: {
                        r: 1,
                        c: 6
                    }
                }
            ];
            XLSX.utils.book_append_sheet(wb, ws1, 'Transaksi');

            // 2) Hitung total & count per status
            const stats = data.reduce((acc, r) => {
                const key = r.status;
                acc[key] = acc[key] || {
                    count: 0,
                    sum: 0
                };
                acc[key].count++;
                acc[key].sum += Number(r.total_harga);
                return acc;
            }, {});
            const lunasSum = stats.lunas?.sum || 0;
            const diambilSum = stats.diambil?.sum || 0;
            const batalSum = stats.batal?.sum || 0;
            const totalAll = lunasSum + diambilSum + batalSum;
            const pendapatan = lunasSum + diambilSum;

            // 3) Sheet Rekap
            const rekapAoA = [
                ['Rekap Pendapatan & Jumlah Transaksi'],
                [`Tanggal Export: ${tanggal}`],
                [],
                ['Status', 'Count', 'Total (Rp)'],
                ['Lunas', stats.lunas?.count || 0, lunasSum],
                ['Diambil', stats.diambil?.count || 0, diambilSum],
                ['Batal', stats.batal?.count || 0, batalSum],
                [],
                ['Total Pendapatan (Lunas+Diambil)', '', pendapatan],
                ['Grand Total', '', totalAll]
            ];
            const ws2 = XLSX.utils.aoa_to_sheet(rekapAoA);
            ws2['!merges'] = [{
                s: {
                    r: 0,
                    c: 0
                },
                e: {
                    r: 0,
                    c: 2
                }
            }];
            XLSX.utils.book_append_sheet(wb, ws2, 'Rekap');

            // 4) Simpan file
            XLSX.writeFile(wb, `Laporan-Seragam-${tanggal}.xlsx`);

        } catch (e) {
            Swal.fire('Error', 'Gagal export Excel: ' + e.message, 'error');
        }
    }




    async function exportPDF() {
        try {
            const data = await fetchAllLaporan();

            // Hitung counts & sums per status
            const statuses = {
                lunas: [],
                diambil: [],
                batal: []
            };
            data.forEach(it => statuses[it.status]?.push(parseFloat(it.total_harga) || 0));

            const lunasCount = statuses.lunas.length;
            const diambilCount = statuses.diambil.length;
            const batalCount = statuses.batal.length;
            const lunasSum = statuses.lunas.reduce((a, b) => a + b, 0);
            const diambilSum = statuses.diambil.reduce((a, b) => a + b, 0);
            const batalSum = statuses.batal.reduce((a, b) => a + b, 0);
            const totalHarga = data.reduce((sum, it) => sum + parseFloat(it.total_harga), 0);
            const pendapatan = lunasSum + diambilSum;

            // 3) Build elemen table
            const tbl = document.createElement('table');
            tbl.style.borderCollapse = 'collapse';
            tbl.style.width = '100%';
            tbl.style.marginTop = '20px';

            // Header
            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            ['No', 'Kode', 'Tanggal', 'Kasir', 'Siswa', 'Metode', 'Total', 'Status']
            .forEach(text => {
                const th = document.createElement('th');
                th.innerText = text;
                th.style.border = '1px solid #000';
                th.style.padding = '6px';
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            tbl.appendChild(thead);

            // Body
            const tbody = document.createElement('tbody');
            data.forEach((it, i) => {
                const tr = document.createElement('tr');
                const cells = [
                    i + 1,
                    it.kode_transaksi,
                    it.tanggal_transaksi,
                    it.namakasir,
                    it.siswa,
                    it.metode_pembayaran,
                    `Rp ${new Intl.NumberFormat('id-ID').format(it.total_harga)}`,
                    it.status.charAt(0).toUpperCase() + it.status.slice(1)
                ];
                cells.forEach(txt => {
                    const td = document.createElement('td');
                    td.innerText = txt;
                    td.style.border = '1px solid #000';
                    td.style.padding = '6px';
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });
            tbl.appendChild(tbody);


            // Buat div rekapan
            const recap = document.createElement('div');
            recap.style.marginTop = '20px';
            recap.innerHTML = `
                <p><strong>Total Lunas:</strong> ${lunasCount} (Rp ${new Intl.NumberFormat('id-ID').format(lunasSum)})</p>
                <p><strong>Total Diambil:</strong> ${diambilCount} (Rp ${new Intl.NumberFormat('id-ID').format(diambilSum)})</p>
                <p><strong>Total Batal:</strong> ${batalCount} (Rp ${new Intl.NumberFormat('id-ID').format(batalSum)})</p>
                <p><strong>Total Harga Semua:</strong> Rp ${new Intl.NumberFormat('id-ID').format(totalHarga)}</p>
                <p><strong>Pendapatan (Lunas+Diambil):</strong> Rp ${new Intl.NumberFormat('id-ID').format(pendapatan)}</p>
            `;

            // 5) Wrap semuanya, sembunyikan dari view user
            const wrap = document.createElement('div');
            wrap.style.position = 'fixed';
            wrap.style.top = '-9999px';
            wrap.style.left = '-9999px';
            wrap.style.padding = '20px';
            wrap.style.background = '#fff';
            wrap.append(
                Object.assign(document.createElement('h3'), {
                    innerText: 'Laporan Pembayaran Seragam'
                }),
                Object.assign(document.createElement('p'), {
                    innerText: `Tanggal: ${new Date().toLocaleString('id-ID')}`
                }),
                tbl,
                recap
            );
            document.body.appendChild(wrap);

            // 6) Capture & generate PDF dengan margin 10 mm
            const canvas = await html2canvas(wrap, {
                scale: 2,
                useCORS: true
            });
            const img = canvas.toDataURL('image/png');
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');

            const pageW = pdf.internal.pageSize.getWidth();
            const margin = 10; // mm
            const imgW = pageW - margin * 2;
            const imgH = canvas.height * (imgW / canvas.width);

            pdf.addImage(img, 'PNG', margin, margin, imgW, imgH);
            pdf.save(`Laporan-Seragam-${new Date().toISOString().slice(0,10)}.pdf`);

            // 7) Bersihkan wrapper
            document.body.removeChild(wrap);

        } catch (e) {
            Swal.fire('Error', 'Gagal export PDF: ' + e.message, 'error');
        }
    }
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>



<?php $this->view('templates/footer'); ?>