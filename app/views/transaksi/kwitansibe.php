<?php
function terbilang($x)
{
    $angka = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
    if ($x < 12)      return $angka[$x];
    if ($x < 20)      return terbilang($x - 10) . " belas";
    if ($x < 100)     return terbilang((int)($x / 10)) . " puluh " . terbilang($x % 10);
    if ($x < 200)     return "seratus " . terbilang($x - 100);
    if ($x < 1000)    return terbilang((int)($x / 100)) . " ratus " . terbilang($x % 100);
    if ($x < 2000)    return "seribu " . terbilang($x - 1000);
    if ($x < 1000000) return terbilang((int)($x / 1000)) . " ribu " . terbilang($x % 1000);
    if ($x < 1000000000) return terbilang((int)($x / 1000000)) . " juta " . terbilang($x % 1000000);
    return "nilai terlalu besar";
}

$tx    = $data['transaksi'];
$items = $data['items'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Cetak Kwitansi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        .no-print {
            padding: 10px;
            text-align: right;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        .kwitansi-container {
            width: 90%;
            margin: 0 auto;
            display: grid;
            gap: 30px;
        }

        .kwitansi {
            border: 1px solid #000;
            padding: 20px;
        }

        .kop {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .info .col {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .table-item {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-item th,
        .table-item td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .table-item th {
            background: #f0f0f0;
        }

        .total {
            text-align: left;
            /* pindah ke kiri */
            font-weight: bold;
            margin-bottom: 5px;
        }

        .terbilang {
            font-style: italic;
            margin-bottom: 30px;
        }

        .tanda-tangan {
            text-align: right;
            padding-bottom: 50px;
        }

        .tanda-tangan .petugas {
            margin-top: 60px;
        }
    </style>
</head>

<body>

    <div class="no-print">
        <button onclick="window.print()">🖨️ Cetak Kwitansi</button>
    </div>

    <div class="kwitansi-container">

        <!-- Kwitansi Detail -->
        <div class="kwitansi">
            <div class="kop">
                KWITANSI PEMBAYARAN SERAGAM<br>
              
            </div>

            <div class="info">
                <div class="col">
                    <div><strong>No Kwitansi:</strong> <?= $tx['kode_transaksi'] ?></div>
                    <div><strong>Telah diterima dari:</strong> <?= htmlspecialchars($tx['nama_siswa']) ?></div>
                    <div><strong>No HP:</strong> <?= htmlspecialchars($tx['no_hp']) ?></div>
                    <div><strong>Guna Membayar:</strong> Seragam Sekolah</div>
                </div>
                <div class="col">

                    <div><strong>NISN:</strong> <?= htmlspecialchars($tx['nis']) ?></div>
                    <div><strong>Jurusan:</strong> <?= htmlspecialchars($tx['jurusan']) ?></div>
                </div>
            </div>

            <table class="table-item">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Ukuran</th>
                        <th>Berhijab</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $it):
                        $ukuran = $it['ukuran'];
                        if ($it['ukuran'] != "standar")  $ukuran = "custom (" . $it['ukuran'] . ")";
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($it['nama_seragam']) ?></td>
                            <td><?= $ukuran ?></td>
                            <td><?= $it['berhijab'] ? 'Berhijab' : 'Tidak' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="total">
                Total: Rp <?= number_format($tx['total_harga'], 0, ',', '.') ?>
            </div>
            <div class="terbilang">
                Terbilang: <?= ucwords(terbilang($tx['total_harga'])) ?> Rupiah
            </div>

            <div class="tanda-tangan">
                Purwokerto, <?= date('d-m-Y', strtotime($tx['tanggal_transaksi'])) ?><br>
                Petugas,<br><br><br>

            </div>
        </div>

        <!-- Kwitansi Ringkas (tanpa list) -->
        <div class="kwitansi">
            <div class="kop">
                KWITANSI PEMBAYARAN SERAGAM<br>
                
            </div>

            <div class="info">
                <div class="col">
                    <div><strong>No Kwitansi:</strong> <?= $tx['kode_transaksi'] ?></div>
                    <div><strong>Telah diterima dari:</strong> <?= htmlspecialchars($tx['nama_siswa']) ?></div>
                    <div><strong>No HP:</strong> <?= htmlspecialchars($tx['no_hp']) ?></div>
                    <div><strong>Guna Membayar:</strong> Seragam Sekolah</div>
                </div>
                <div class="col">
                </div>
            </div>

            <div class="total">
                Total: Rp <?= number_format($tx['total_harga'], 0, ',', '.') ?>
            </div>
            <div class="terbilang">
                Terbilang: <?= ucwords(terbilang($tx['total_harga'])) ?> Rupiah
            </div>

            <div class="tanda-tangan">
                Purwokerto, <?= date('d-m-Y', strtotime($tx['tanggal_transaksi'])) ?><br>
                Petugas,<br><br><br>

            </div>
            <div class="inf">
                <i>Ket. Barang yang sudah dibeli tidak boleh dikembalikan</i>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // jika ingin auto-print:
            window.print();
        });
    </script>
</body>

</html>