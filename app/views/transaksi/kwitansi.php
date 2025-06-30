<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kwitansi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .kwitansi-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .kwitansi {
            width: 90%;
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            border: 1px solid black;
            padding: 5px;
        }

        .total {
            font-weight: bold;
        }

        .terbilang {
            margin-top: 10px;
            font-style: italic;
        }

        .tanda-tangan {
            text-align: right;
            margin-top: 20px;
        }

        .no-print {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="kwitansi-container">
        <!-- Tiga kwitansi dalam satu halaman -->

        <div class="kwitansi">
            <h3>Kwitansi</h3>
            <p>No Kwitansi: <span id="no_kwitansi"></span></p>
            <p>No Pendaftaran: <span id="no_pendaftaran"></span></p>
            <p>Jurusan: <span id="jurusan"></span></p>
            <p>No HP: <span id="no_hp"></span></p>
            <p>Telah diterima dari: <span id="nama_siswa"></span></p>
            <p>Guna Membayar: Bahan pakaian</p>

            <table>
                <tr>
                    <th>Item</th>
                    <th>Jenis</th>
                    <!-- <th>Harga</th> -->
                </tr>
                <tbody id="item_list"></tbody>
            </table>

            <p class="total">Total: <span id="total_harga"></span></p>
            <p class="terbilang">Terbilang: <span id="terbilang"></span></p>
            <p class="tanda-tangan">Purwokerto, <span id="tanggal"></span><br>Petugas<br><br>______________</p>
        </div>


        <!-- <div class="kwitansi">(Kwitansi untuk Gudang, format sama)</div>
        <div class="kwitansi">(Kwitansi untuk Siswa, format sama)</div> -->
    </div>


    <script>
        function formatRupiah(angka) {
            return `Rp. ${angka.toLocaleString("id-ID")}`;
        }

        function terbilang(nilai) {
            const angka = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
            if (nilai < 12) return angka[nilai];
            if (nilai < 20) return terbilang(nilai - 10) + " Belas";
            if (nilai < 100) return terbilang(Math.floor(nilai / 10)) + " Puluh " + terbilang(nilai % 10);
            if (nilai < 200) return "Seratus " + terbilang(nilai - 100);
            if (nilai < 1000) return terbilang(Math.floor(nilai / 100)) + " Ratus " + terbilang(nilai % 100);
            if (nilai < 2000) return "Seribu " + terbilang(nilai - 1000);
            if (nilai < 1000000) return terbilang(Math.floor(nilai / 1000)) + " Ribu " + terbilang(nilai % 1000);
            if (nilai < 1000000000) return terbilang(Math.floor(nilai / 1000000)) + " Juta " + terbilang(nilai % 1000000);
            return "Nilai terlalu besar";
        }

        document.addEventListener("DOMContentLoaded", function() {
            let transaksi = JSON.parse(localStorage.getItem("transaksi"));
            // console.log(transaksi.total_harga);
            if (transaksi) {
                document.getElementById("no_kwitansi").textContent = transaksi.no_kwitansi;
                // document.getElementById("no_pendaftaran").textContent = transaksi.no_pendaftaran;
                document.getElementById("jurusan").textContent = transaksi.jurusan;
                document.getElementById("no_hp").textContent = transaksi.no_hp;
                document.getElementById("nama_siswa").textContent = transaksi.nama_siswa;
                document.getElementById("total_harga").textContent = formatRupiah(transaksi.total_harga);
                document.getElementById("terbilang").textContent = terbilang(parseInt(transaksi.total_harga)) + " Rupiah";
                document.getElementById("tanggal").textContent = new Date().toLocaleDateString("id-ID");

                let itemList = document.getElementById("item_list");
                transaksi.items.forEach(item => {
                    let row = `<tr>
                        <td>${item.nama}</td>
                        <td>${item.status}</td>
                       
                    </tr>`;
                    itemList.innerHTML += row;
                });
            }
            // <td>${formatRupiah(item.harga)}</td>
            window.print()
        });
    </script>
</body>

</html>