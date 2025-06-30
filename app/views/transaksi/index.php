<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .ui-autocomplete {
        z-index: 1050;
        /* Pastikan tidak tertutup elemen lain */
        /* max-height: 300px; */
        overflow-y: auto;
        /* Tambahkan scroll jika terlalu panjang */
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid #ddd;
        max-height: 200px;
        list-style-type: none;
        padding: 5px;

    }



    .ui-menu-item {
        padding: 10px;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .ui-menu-item:hover,
    .ui-menu-item.ui-state-focus {
        background: rgb(79, 152, 212);
        border-radius: 5px;
        /* font-weight: bold; */
    }

    .total-container {
        display: flex;
        justify-content: flex-end;
        /* Meletakkan ke kanan */
        align-items: center;
        margin-bottom: 15px;
        /* Tambahkan margin bawah */
    }

    #totalDisplay {
        font-weight: bold;
        font-size: 18px;
        margin-left: 10px;
    }
</style>

<div class="container-fluid mt-4 px-4">



    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-4">Manajemen Transaksi</h2>
        <div class="d-flex gap-2">
            <?php if ($data['role'] === 'admin'): ?>
                <div class="mb-3">
                    <a
                        id="btnTruncate"
                        class="btn btn-danger"
                        href="#"
                        data-href="<?= BASEURL; ?>/transaksi/truncate">
                        Truncate Transaksi
                    </a>
                </div>
            <?php endif ?>
            <?php if ($data['role'] !== 'gudang'): ?>
                <div class="mb-3">
                    <button class="btn btn-primary" data-toggle="modal" id="btnTambah" data-target="#modalTambahTransaksi"> <i class="bi bi-plus-circle"></i> Tambah Transaksi</button>
                </div>
            <?php endif ?>
        </div>
    </div>
    <div class="row mb-3">
        <div class="input-group mb-3">
            <input type="text" id="filterNamaSiswa" class="form-control" placeholder="Cari Nama Siswa...">
            <select id="sortBy" class="form-select" style="max-width: 200px;">
                <option value="">Urutkan</option>
                <option value="nama_asc">Nama A-Z</option>
                <option value="nama_desc">Nama Z-A</option>
                <option value="trx_asc">TRX Asc</option>
                <option value="trx_desc">TRX Desc</option>
            </select>
        </div>

    </div>
    <div id="flash-message"></div>

    <div class="card shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="transaksiTable">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Transaksi</th>
                            <th>Tanggal</th>
                            <th>Kasir</th>
                            <th>Siswa</th>
                            <th>Jurusan</th>
                            <th>Metode Pembayaran</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="transaksiBody">
                        <!-- Data akan dimasukkan di sini -->
                    </tbody>
                </table>
                <nav>
                    <ul class="pagination justify-content-center" id="paginationContainer"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="modalTambahTransaksi" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title">Tambah Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="formTambahTransaksi">
                    <div class="form-group mb-3">
                        <label for="siswa" class="mb-2"><strong> Cari Siswa</strong></label>
                        <input type="text" class="form-control" id="siswa" placeholder="Masukkan nama" required>
                        <input type="hidden" id="siswa_id" name="siswa_id">
                    </div>
                    <div id="detailSiswa" class="mb-3">
                        <div class="form-group mb-3">
                            <!-- <label for="nis">NIS</label> -->
                            <input type="text" id="nis" class="form-control" placeholder="NIS" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <!-- <label for="jurusan">Jurusan</label> -->
                            <input type="text" id="jurusan" class="form-control" placeholder="Jurusan" readonly>
                        </div>
                        <div class="form-group mb-3">
                            <!-- <label for="no_hp">No HP</label> -->
                            <input type="text" id="no_hp" class="form-control" placeholder="No HP" readonly>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nama Seragam</th>
                                <th>Status</th>
                                <th>Ukuran</th>
                                <th>Ukuran Tambahan</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="seragamList"></tbody>
                    </table>

                    <div class="d-flex justify-content-end align-items-center mt-3">
                        <label for="total" class="me-2">Total Harga:</label>
                        <input type="hidden" id="total" class="form-control" readonly>
                        <span id="totalDisplay" class="fw-bold fs-5"></span>
                    </div>



                    <div class="form-group mt-3">
                        <label for="metodePembayaran">Metode Pembayaran</label>
                        <select class="form-control" id="metodePembayaran" name="metode_pembayaran">
                            <option value="cash">Cash</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>

                    <div id="qrisContainer" class="mt-3" style="display: none; text-align: center;">
                        <img src="path/to/qris_qrcode.png" alt="QRIS" width="200">
                    </div>

                    <div id="cashContainer" class="mt-3">
                        <label for="jumlahBayar">Jumlah Uang</label>
                        <input type="text" class="form-control" id="jumlahBayar" placeholder="Masukkan jumlah uang">
                        <label for="kembalian" class="mt-2">Kembalian</label>
                        <input type="text" class="form-control" id="kembalian" readonly>
                    </div>

                    <div class="form-group d-flex justify-content-end mt-3">
                        <button id="btnReset" class="btn btn-danger me-2"> <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Pilihan</button>
                        <button id="btnSimpanTransaksi" type="submit" class="btn btn-success me-2"> <i class="bi bi-save"></i> Simpan</button>
                        <!-- <button type="button" class="btn btn-primary" id="btnCetakKwitansi">Cetak Kwitansi</button> -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="konfirmasiTransaksiId">
                <p><strong>Nama Siswa:</strong> <span id="konfirmasiNama"></span></p>
                <p><strong>Jurusan:</strong> <span id="konfirmasiJurusan"></span></p>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th id="thCheckAll" style="display:none;">
                                <input type="checkbox" id="checkAll">
                            </th>
                            <th>Nama Seragam</th>
                            <th>Ukuran</th>
                            <th>Status</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="konfirmasiSeragamList"></tbody>
                </table>

                <p class="text-end fw-bold">Total Harga: <span id="konfirmasiTotalHarga"></span></p>
            </div>
            <div class="modal-footer">
                <!-- Batal -->
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>

                <!-- Ya, Simpan -->
                <button type="button" class="btn btn-primary" id="btnProsesTransaksi">
                    <i class="bi bi-save me-1"></i> Ya, Simpan
                </button>

                <!-- Simpan Status Ambil -->
                <button type="button" style="display:none;" class="btn btn-success" id="btnSimpanAmbil">
                    <i class="bi bi-check2-circle me-1"></i> Simpan
                </button>


            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditStatus" tabindex="-1" aria-labelledby="editStatusLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editStatusLabel">Ubah Status Transaksi <br><span id="kodeTransaksiEdit"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editTransaksiId">
                <div class="mb-3">
                    <label for="statusTransaksi" class="form-label">Status</label>
                    <select class="form-select" id="statusTransaksi">
                        <!-- <option value="pending">Pending</option> -->
                        <option value="lunas">Lunas</option>
                        <!-- <option value="batal">Batal</option> -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSimpanStatus">Simpan</button>
            </div>
        </div>
    </div>
</div>

</div>


<script>
    let currentPage = 1;
    let limit = 10;

    function loadTransaksi(page = 1) {
        currentPage = page;
        const offset = (page - 1) * limit;
        const keyword = document.getElementById('filterNamaSiswa').value.trim();
        const sortBy = document.getElementById('sortBy').value;

        const params = new URLSearchParams({
            limit: limit,
            offset: offset
        });

        if (keyword) {
            params.append('keyword', keyword);
        }

        if (sortBy) {
            // Ubah nama_asc → s.nama_asc, trx_desc → t.kode_transaksi_desc (sesuai field valid di backend)
            switch (sortBy) {
                case 'nama_asc':
                    params.append('sort_by', 's.nama|ASC');
                    break;
                case 'nama_desc':
                    params.append('sort_by', 's.nama|DESC');
                    break;
                case 'trx_asc':
                    params.append('sort_by', 't.tanggal_transaksi|ASC');
                    break;
                case 'trx_desc':
                    params.append('sort_by', 't.tanggal_transaksi|DESC');
                    break;
            }
        }

        fetch(`<?= BASEURL ?>/transaksi/getAllTransaksi?${params.toString()}`)
            .then(response => response.json())
            .then(response => {
                renderTransaksi(response.data);
                renderPagination(response.totalPages, currentPage);
            })
            .catch(error => {
                console.error('Error fetching transaksi:', error);
            });
    }

    document.getElementById('filterNamaSiswa').addEventListener('input', function() {
        loadTransaksi(1); // Reset ke halaman 1 saat ketik pencarian
    });

    document.getElementById('sortBy').addEventListener('change', function() {
        loadTransaksi(1); // Reset juga saat sort berubah
    });


    function renderTransaksi(data) {
        const transaksiBody = document.getElementById("transaksiBody");
        transaksiBody.innerHTML = "";
        let no = 1;
        data.forEach(item => {
            let row = document.createElement("tr");

            let badge = "bg-primary";
            if (item.status === 'lunas') {
                badge = 'bg-success';
            } else if (item.status === 'batal') {
                badge = 'bg-danger';
            }

            row.innerHTML = `
            <td>${no}</td>
            <td>${item.kode_transaksi}</td>
            <td>${item.tanggal_transaksi}</td>
            <td>${item.kasir}</td>
            <td>${item.siswa}</td>
            <td>${item.jurusan}</td>
            <td>${item.metode_pembayaran}</td>
            <td>Rp ${new Intl.NumberFormat('id-ID').format(item.total_harga)}</td>
            <td><span class="badge ${badge}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span></td>
            <td>
                ${"<?= $_SESSION['role']; ?>" !== 'gudang' ?
                `<button class='btn btn-secondary btn-sm text-nowrap btnCetak' data-id='${item.id}'>
                    <i class='bi bi-printer'></i> Cetak
                </button>` : ''}

                <button class='btn btn-info btn-sm text-nowrap btnDetail' data-id='${item.id}' data-kode='${item.kode_transaksi}' data-status='${item.status}'>
                    <i class="bi bi-info-circle me-1"></i> Detail
                </button>


                ${item.status === 'batal' && "<?= $_SESSION['role']; ?>" === 'admin' ? `
                <button class='btn btn-warning btn-sm text-nowrap btnEdit' data-id='${item.id}' data-kode='${item.kode_transaksi}'>
                    <i class="bi bi-pencil-square me-1"></i> Edit
                </button>` : ''}

                ${item.status !== 'batal' && "<?= $_SESSION['role']; ?>" === 'admin' ? `
                <button class='btn btn-danger btn-sm text-nowrap btnBatal' data-id='${item.id}' data-kode='${item.kode_transaksi}'>
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>` : ''}

                ${item.status === 'lunassss' && "<?= $_SESSION['role']; ?>" === 'gudassng' ? `
                <button class='btn btn-success btn-sm text-nowrap btnKonfirmasiAmbil' data-id='${item.id}'>
                    <i class="bi bi-check2-circle me-1"></i> Diambil
                </button>` : ''}

            </td>
        `;

            transaksiBody.appendChild(row);
            no++;
        });
    }

    function renderPagination(totalPages, current) {
        const container = document.getElementById('paginationContainer');
        container.innerHTML = "";

        const maxVisible = 6;
        let startPage = Math.max(1, current - Math.floor(maxVisible / 2));
        let endPage = startPage + maxVisible - 1;

        if (endPage > totalPages) {
            endPage = totalPages;
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        // Tombol Prev
        const prev = document.createElement("li");
        prev.className = "page-item" + (current === 1 ? " disabled" : "");
        prev.innerHTML = `<a class="page-link" href="#">Prev</a>`;
        prev.addEventListener("click", e => {
            e.preventDefault();
            if (current > 1) loadTransaksi(current - 1);
        });
        container.appendChild(prev);

        // Tampilkan halaman 1 + ...
        if (startPage > 1) {
            container.appendChild(createPageItem(1, current));
            if (startPage > 2) {
                container.appendChild(createDots());
            }
        }

        // Halaman tengah
        for (let i = startPage; i <= endPage; i++) {
            container.appendChild(createPageItem(i, current));
        }

        // Tampilkan ... + halaman terakhir
        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                container.appendChild(createDots());
            }
            container.appendChild(createPageItem(totalPages, current));
        }

        // Tombol Next
        const next = document.createElement("li");
        next.className = "page-item" + (current === totalPages ? " disabled" : "");
        next.innerHTML = `<a class="page-link" href="#">Next</a>`;
        next.addEventListener("click", e => {
            e.preventDefault();
            if (current < totalPages) loadTransaksi(current + 1);
        });
        container.appendChild(next);

        // Helper untuk membuat tombol halaman
        function createPageItem(page, currentPage) {
            const li = document.createElement("li");
            li.className = "page-item" + (page === currentPage ? " active" : "");
            li.innerHTML = `<a class="page-link" href="#">${page}</a>`;
            li.addEventListener("click", e => {
                e.preventDefault();
                loadTransaksi(page);
            });
            return li;
        }

        // Helper untuk titik-titik (...)
        function createDots() {
            const li = document.createElement("li");
            li.className = "page-item disabled";
            li.innerHTML = `<span class="page-link">...</span>`;
            return li;
        }
    }



    document.addEventListener("DOMContentLoaded", function() {

        loadTransaksi();


        // Filter ketika user mengetik di input
        // document.getElementById("filterNamaSiswa").addEventListener("input", function() {
        //     const keyword = this.value.toLowerCase();
        //     const filtered = allTransaksi.filter(item =>
        //         item.siswa.toLowerCase().includes(keyword)
        //     );
        //     renderTransaksi(filtered);
        // });

        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("btnCetak") || e.target.closest(".btnCetak")) {
                const btn = e.target.closest(".btnCetak");
                const transaksiId = btn.getAttribute("data-id");

                if (transaksiId) {
                    const url = "<?= BASEURL ?>/transaksi/kwitansi/" + transaksiId;
                    window.open(url, "_blank"); // Buka tab baru
                }
            }
        });


    });
</script>


<script>
    // 1) Helper untuk enable/disable inputs di dalam tabel
    function toggleDetailInputs() {
        const hasSiswa = $('#siswa').val().trim() !== '';
        // selalu disable custom dulu
        $('.ukuranCustom').prop('disabled', true).val('');
        // enable status & ukuran hanya jika sudah pilih siswa
        $('.status, .ukuran').prop('disabled', !hasSiswa);
        // reset harga & total juga
        $('.harga').text('Rp. 0');
        $('#total').val('');
        $('#totalDisplay').text('');
        updateButtonState();
    }

    $("#btnTambah").click(function() {
        $("#formTambahTransaksi")[0].reset();


        const inputJumlahBayar = document.getElementById("jumlahBayar");

        inputJumlahBayar.addEventListener("input", function() {
            let value = this.value.replace(/[^,\d]/g, "").toString();
            let split = value.split(",");
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;

            this.value = "Rp " + rupiah;
        });

        // kosongkan detail siswa
        $('#nis,#jurusan,#no_hp').val('');
        toggleDetailInputs();

        $("#modalTambahTransaksi").modal("show");
    });

    function updateKembalian() {
        // const nominal = document.getElementById("jumlahBayar").value.replace(/[^\d]/g, "");
        let total = parseInt($("#total").val()) || 0;
        let bayar = parseInt($("#jumlahBayar").val().replace(/[^\d]/g, "")) || 0;
        let kembalian = bayar - total;
        $("#kembalian").val(kembalian >= 0 ? formatRupiah(kembalian) : formatRupiah(0));
    }

    function formatRupiah(angka) {
        return `Rp. ${angka.toLocaleString("id-ID")}`;
    }

    function updateButtonState() {
        let isAnyItemSelected = $(".status").toArray().some(select => $(select).val() !== "tidak_beli");
        // console.log(isAnyItemSelected);
        $("#btnCetakKwitansi").prop("disabled", !isAnyItemSelected);
        $("#formTambahTransaksi button[type='submit']").prop("disabled", !isAnyItemSelected);
    }

    $(document).ready(function() {

        $("#metodePembayaran").change(function() {
            let metode = $(this).val();
            if (metode === "qris") {
                $("#qrisContainer").show();
                $("#cashContainer").hide();
            } else {
                $("#qrisContainer").hide();
                $("#cashContainer").show();
            }
        });

        $("#jumlahBayar").on("input", updateKembalian);


        document.getElementById("btnReset").addEventListener("click", function() {
            event.preventDefault(); // Mencegah form tertutup / reload
            // Reset semua dropdown ke "tidak_beli"
            document.querySelectorAll(".status").forEach(select => select.value = "tidak_beli");

            // Reset ukuran ke default "S"
            document.querySelectorAll(".ukuran").forEach(select => select.value = "S");

            // Reset input ukuran custom dan harga ke 0
            document.querySelectorAll(".ukuranCustom").forEach(input => {
                input.value = "";
                input.disabled = true;
            });

            document.querySelectorAll(".harga").forEach(td => td.textContent = "Rp. 0");
            $("#total").val('');

            // Tampilkan dalam format rupiah di elemen lain
            $("#totalDisplay").text('');
            updateButtonState();
        });


        // Jalankan fungsi setiap kali ada perubahan dalam dropdown status
        $(document).on("change", ".status", function() {
            updateButtonState();
        });

        // Ketika modal dibuka, tombol harus dinonaktifkan dulu
        $("#modalTambahTransaksi").on("shown.bs.modal", function() {
            updateButtonState();
        });

        $('#modalTambahTransaksi')
            .on('shown.bs.modal', toggleDetailInputs)
            .on('hidden.bs.modal', function() {
                // disable semua saat ditutup, untuk jaga–jaga
                $('.status, .ukuran, .ukuranCustom').prop('disabled', true);
            });

        $("#siswa").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "siswa/cariSiswa",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    dataType: "json",
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.label,
                                value: item.value,
                                id: item.id,
                                nis: item.nis,
                                jenis_kelamin: item.jenis_kelamin,
                                jurusan: item.jurusan,
                                no_hp: item.no_hp
                            };
                        }));
                    }
                });
            },
            minLength: 2,
            appendTo: "#formTambahTransaksi", // Tempatkan dalam div khusus agar lebih fleksibel
            select: function(event, ui) {
                $("#siswa_id").val(ui.item.id);
                $("#nis").val(ui.item.nis);
                $("#jurusan").val(ui.item.jurusan);
                $("#no_hp").val(ui.item.no_hp);
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li class='list-group-item list-group-item-action'>")
                .append("<strong>" + item.value + "</strong> <br><small>NIS: " + item.nis + " | " + item.jurusan + "</small>")
                .appendTo(ul);
        };






        let seragamData = [];


        // Ambil data dari Controller Seragam menggunakan AJAX
        function loadSeragamData() {
            $.ajax({
                url: "seragam/getSeragam",
                method: "GET",
                dataType: "json",
                success: function(data) {

                    seragamData = data;
                    renderSeragamList();
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                },
            });
        }

        function renderSeragamList() {
            let html = "";
            seragamData.forEach((item, index) => {
                const hargaTambahan = parseFloat(item.harga_tambahan) || 0;
                const berhijabOpt = item.berhijab == 1 ?
                    `<option value="berjilbab">Berjilbab</option>` :
                    "";

                // 1) Opsi ukuran: jika hargaTambahan==0 maka custom di-disable
                const ukuranOptions = [
                    `<option value="standar">Standar</option>`,
                    hargaTambahan > 0 ?
                    `<option value="custom">Custom</option>` :
                    `<option value="custom" disabled style="color:#999;">Custom</option>`
                ].join("");

                // 2) Placeholder untuk input custom hanya jika boleh custom
                const placeholderCustom = hargaTambahan > 0 ?
                    "" :
                    "";

                html += `
                    <tr>
                        <td style="display:none;">${item.id}</td>
                        <td>${item.nama}</td>
                        <td>
                        <select class="form-control status" data-index="${index}" disabled>
                            <option value="tidak_beli">Tidak Beli</option>
                            <option value="beli">Beli</option>
                            ${berhijabOpt}
                        </select>
                        </td>
                        <td>
                        <select class="form-control ukuran" data-index="${index}" disabled>
                            ${ukuranOptions}
                        </select>
                        </td>
                        <td>
                        <input
                            type="number"
                            class="form-control ukuranCustom"
                            data-index="${index}"
                            disabled
                            placeholder="${placeholderCustom}"
                        >
                        </td>
                        <td class="harga" data-index="${index}">Rp. 0</td>
                    </tr>
                    `;
            });
            $("#seragamList").html(html);
        }


        $("#siswa").on("input", function() {
            toggleDetailInputs();

            if ($(this).val().trim() !== "") {
                $(".status, .ukuran").prop("disabled", false);
            } else {
                $(".status, .ukuran").prop("disabled", true);
            }
        });

        $(document).on("change input", ".status, .ukuran, .ukuranCustom", function() {
            let index = $(this).data("index");
            let status = $(".status[data-index='" + index + "']").val();
            let ukuran = $(".ukuran[data-index='" + index + "']").val();
            let ukuranCustom = $(".ukuranCustom[data-index='" + index + "']").val();

            let hargaDasar = parseFloat(seragamData[index].harga);
            let hargaBerhijab = parseFloat(seragamData[index].harga_berhijab);
            let hargaTambahanPerCm = parseFloat(seragamData[index].harga_tambahan);
            let hargaFinal = 0;

            if (status !== "tidak_beli") {
                let hargaAwal = (status === "berjilbab") ? hargaBerhijab : hargaDasar;

                if (ukuran === "custom" && ukuranCustom) {
                    let tambahanUkuran = parseInt(ukuranCustom);
                    hargaFinal = hargaAwal + (tambahanUkuran * hargaTambahanPerCm);
                } else {
                    hargaFinal = hargaAwal;
                }
            }

            $(".harga[data-index='" + index + "']").text(formatRupiah(hargaFinal));
            updateTotal();
        });

        $(document).on("input", ".ukuranCustom", function() {
            $(this).trigger("change");
        });

        $(document).on("change", ".ukuran", function() {
            const index = $(this).data("index");
            const pilihan = $(this).val();
            const hargaTambahan = parseFloat(seragamData[index].harga_tambahan) || 0;
            const $customInput = $(".ukuranCustom[data-index='" + index + "']");

            if (pilihan === "custom" && hargaTambahan > 0) {
                $customInput.prop("disabled", false);
            } else {
                $customInput.prop("disabled", true).val("");
            }

            // trigge harga update
            $customInput.trigger("change");
        });

        function updateTotal() {
            let total = 0;
            $(".harga").each(function() {
                let hargaText = $(this).text().replace("Rp. ", "").replace(/\./g, ""); // Hilangkan format Rp
                total += parseInt(hargaText) || 0;
            });

            // Simpan angka murni dalam input type="number"
            $("#total").val(total);

            // Tampilkan dalam format rupiah di elemen lain
            $("#totalDisplay").text(formatRupiah(total));
        }


        // Load data saat halaman siap
        loadSeragamData();
    });

    $(document).on("click", "#btnCetakKwitansi", function() {
        // localStorage.clear();
        let transaksi = {
            no_kwitansi: "A2", // Ambil dari database atau buat otomatis
            // no_pendaftaran: document.getElementById("no_pendaftaran").value,
            jurusan: document.getElementById("jurusan").value,
            no_hp: document.getElementById("no_hp").value,
            nama_siswa: document.getElementById("siswa").value,
            total_harga: parseInt(document.getElementById("total").value.replace(/[^\d]/g, "").trim()),
            // terbilang: document.getElementById("terbilang").textContent,
            items: []
        };
        // console.log(document.getElementById("total").value);
        document.querySelectorAll("#seragamList tr").forEach(row => {
            let status = row.querySelector(".status").value; // Ambil status pembelian
            if (status !== "tidak_beli") { // Hanya jika dibeli

                let nama = row.cells[1].textContent.trim();
                let status = row.querySelector(".status").value;
                let ukuranCustom = row.querySelector(".ukuranCustom");
                let ukuran = ukuranCustom.value ? ukuranCustom.value : row.querySelector(".ukuran").value;
                let harga = row.querySelector(".harga").innerText;

                // console.log(harga);
                if (harga != "") {
                    transaksi.items.push({
                        nama,
                        status,
                        ukuran,
                        harga
                    });

                    // console.log(transaksi);
                }
            }
        });

        localStorage.setItem("transaksi", JSON.stringify(transaksi));
        // window.location.href = "transaksi/kwitansi"; // Arahkan ke halaman cetak
        let printWindow = window.open("transaksi/kwitansijs", "_blank");
        printWindow.focus();
    });
</script>

<script>
    document.getElementById("formTambahTransaksi").addEventListener("submit", function(event) {
        event.preventDefault(); // Mencegah submit langsung

        // Ambil informasi siswa
        let namaSiswa = document.getElementById("siswa").value;
        let jurusan = document.getElementById("jurusan").value;

        // Ambil daftar seragam yang dibeli
        let seragamRows = document.querySelectorAll("#seragamList tr");
        let konfirmasiSeragamList = document.getElementById("konfirmasiSeragamList");
        konfirmasiSeragamList.innerHTML = "";

        let totalHarga = 0;

        seragamRows.forEach(row => {
            let status = row.querySelector(".status").value; // Ambil status pembelian
            if (status !== "tidak_beli") { // Hanya jika dibeli
                let namaSeragam = row.cells[1].innerText; // Nama seragam dari kolom pertama
                let ukuranCustom = row.querySelector(".ukuranCustom");
                let ukuran = ukuranCustom.value ? ukuranCustom.value : row.querySelector(".ukuran").value;
                let status = row.querySelector(".status").value;
                let harga = row.querySelector(".harga").innerText;

                totalHarga += parseFloat(harga.replace(/[^\d]/g, "")); // Konversi harga ke angka

                let tr = document.createElement("tr");
                tr.innerHTML = `
                <td>${namaSeragam}</td>
                <td>${ukuran}</td>
                <td>${status}</td>
                <td>${harga}</td>
            `;
                konfirmasiSeragamList.appendChild(tr);
            }
        });

        // Set nilai dalam modal konfirmasi
        document.getElementById("konfirmasiNama").innerText = namaSiswa;
        document.getElementById("konfirmasiJurusan").innerText = jurusan;
        document.getElementById("konfirmasiTotalHarga").innerText = "Rp " + totalHarga.toLocaleString();

        // Tampilkan modal konfirmasi
        let modalKonfirmasi = new bootstrap.Modal(document.getElementById("modalKonfirmasi"));
        modalKonfirmasi.show();
    });

    // Proses transaksi ke controller setelah konfirmasi
    document.getElementById("btnProsesTransaksi").addEventListener("click", function() {
        let form = document.getElementById("formTambahTransaksi");
        // let dataForm = new FormData(form); // Mengambil data dari form
        // dataForm.forEach((value, key) => {
        //     console.log(`${key}: ${value}`);
        // });
        let formData = {
            siswa_id: $("#siswa_id").val(),
            metode_pembayaran: $("#metodePembayaran").val(),
            seragam: []
        };

        $("#seragamList tr").each(function() {
            let status = $(this).find(".status").val();
            if (status !== "tidak_beli") {
                let id = $(this).find("td:first").text().trim();
                let nama = $(this).find('td').eq(1).text().trim();
                let ukuran = $(this).find(".ukuranCustom").val() || $(this).find(".ukuran").val();
                let status = $(this).find(".status").val() || 0;
                let harga = $(this).find(".harga").text().replace("Rp. ", "").replace(".", "").trim();

                formData.seragam.push({
                    id: id,
                    nama: nama,
                    ukuran: ukuran,
                    status: status,
                    harga: harga
                });
            }
        });


        // AJAX Request
        fetch("<?= BASEURL; ?>/transaksi/tambah", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.text()) // Gunakan `.text()` dulu untuk melihat hasil mentah
            .then(result => {
                console.log("Response dari server:", result);
                return JSON.parse(result); // Konversi ke JSON setelah dicek
            })
            .then(result => {
                if (result.status === "success") {
                    alert("Transaksi berhasil!");
                    $("#modalTambahTransaksi").modal("hide");
                    document.getElementById("formTambahTransaksi").reset();
                    location.reload();
                } else {
                    alert("Gagal: " + result.message);
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan.");
            });

    });

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("btnDetail")) {
            const id = e.target.dataset.id;
            const kode = e.target.dataset.kode;
            const statusTransaksi = e.target.dataset.status;

            fetch(`<?= BASEURL ?>/transaksi/getDetail/${id}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById("konfirmasiSeragamList");
                    tbody.innerHTML = "";

                    // Reset dulu: uncheck master checkAll
                    document.getElementById("checkAll").checked = false;

                    let total = 0;
                    data.forEach(item => {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                        <td class='tdcheckAll'><input type="checkbox" name="ambil[]" class="check-seragam" value="${item.id}" ${item.status_ambil === 'diambil' ? 'checked' : ''}></td>
                        <td>${item.nama_seragam}</td>
                        <td>${item.ukuran}</td>
                        <td>${item.berhijab ? "Berhijab" : "Biasa"}</td>
                        <td>Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
                    `;
                        tbody.appendChild(row);
                        total += parseInt(item.harga);
                    });

                    // Update total harga
                    document.getElementById("konfirmasiTotalHarga").innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);

                    if (data.length > 0) {
                        document.getElementById("konfirmasiNama").innerText = data[0].nama_siswa;
                        document.getElementById("konfirmasiJurusan").innerText = data[0].jurusan;
                    }

                    const USER_ROLE = "<?= $_SESSION['role'] ?>";

                    // Atur tampilan tombol & kolom "Ceklis Semua" berdasarkan role
                    const checkAllTh = document.getElementById("thCheckAll");
                    const tdCheckAllList = tbody.querySelectorAll('.tdcheckAll');
                    const btnSimpanAmbil = document.getElementById("btnSimpanAmbil");

                    if (USER_ROLE === 'admin' || USER_ROLE === 'gudang') {
                        btnSimpanAmbil.style.display = "inline-block";
                        tdCheckAllList.forEach(td => td.style.display = '');
                        checkAllTh.style.display = "";
                    } else {

                        btnSimpanAmbil.style.display = "none";
                        tdCheckAllList.forEach(td => td.style.display = 'none');
                        checkAllTh.style.display = "none";
                    }

                    // Modal pengaturan
                    document.getElementById("btnProsesTransaksi").style.display = "none";
                    document.querySelector("#modalKonfirmasi .modal-title").innerText = "Detail Transaksi " + kode;
                    document.querySelector("#modalKonfirmasi .btn-secondary").style.display = "none";
                    document.getElementById('konfirmasiTransaksiId').value = e.target.dataset.id;
                    const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasi'));
                    modal.show();
                    if (statusTransaksi === 'batal') {
                        // Disable semua checkbox
                        const allCheckboxes = tbody.querySelectorAll("input.check-seragam");
                        allCheckboxes.forEach(cb => {
                            cb.checked = false;
                            cb.disabled = true;
                        });

                        // Nonaktifkan tombol simpan ambil
                        document.getElementById("btnSimpanAmbil").disabled = true;

                        // Nonaktifkan checkbox "Ceklis Semua"
                        document.getElementById("checkAll").checked = false;
                        document.getElementById("checkAll").disabled = true;
                    } else {
                        // Aktifkan checkbox jika status bukan batal
                        const allCheckboxes = tbody.querySelectorAll("input.check-seragam");
                        allCheckboxes.forEach(cb => {
                            cb.disabled = false;
                        });

                        document.getElementById("btnSimpanAmbil").disabled = false;
                        document.getElementById("checkAll").disabled = false;
                    }

                })
                .catch(error => {
                    console.error("Gagal mengambil detail transaksi:", error);
                    alert("Terjadi kesalahan saat mengambil data detail transaksi.");
                });
        }
    });


    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('#konfirmasiSeragamList input[type="checkbox"]');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    const modalEl = document.getElementById('modalKonfirmasi');
    modalEl.addEventListener('hidden.bs.modal', function() {
        // Kembalikan ke tampilan semula
        document.querySelector("#modalKonfirmasi .modal-title").innerText = "Konfirmasi Transaksi";
        document.getElementById("btnProsesTransaksi").style.display = "block";
        document.querySelector("#modalKonfirmasi .btn-secondary").style.display = "block";
    });


    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("btnEdit")) {
            const id = e.target.dataset.id;
            const kode = e.target.dataset.kode; // ambil kode transaksi dari atribut

            document.getElementById("editTransaksiId").value = id;
            document.getElementById("kodeTransaksiEdit").textContent = kode;

            const modal = new bootstrap.Modal(document.getElementById("modalEditStatus"));
            modal.show();
        }
    });

    document.getElementById("btnSimpanStatus").addEventListener("click", function() {
        const id = document.getElementById("editTransaksiId").value;
        const status = document.getElementById("statusTransaksi").value;

        fetch(`<?= BASEURL ?>/transaksi/updateStatus`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id,
                    status
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Response dari server:", data);
                if (data.success) {
                    alert("Status berhasil diperbarui!");
                    location.reload();
                } else {
                    alert("Gagal memperbarui status: " + (data.message || ""));
                }
            })
            .catch(err => {
                console.error("Gagal:", err);
                alert("Terjadi kesalahan.");
            });
    });

    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("btnBatal")) {
            const id = e.target.dataset.id;
            const kode = e.target.dataset.kode;
            const status = "batal";

            Swal.fire({
                title: `Batalkan transaksi ${kode}?`,
                text: "Tindakan ini tidak bisa dibatalkan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, batalkan!",
                cancelButtonText: "Batal",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`<?= BASEURL ?>/transaksi/updateStatus`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify({
                                id,
                                status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    "Berhasil!",
                                    "Transaksi telah dibatalkan.",
                                    "success"
                                ).then(() => location.reload());
                            } else {
                                Swal.fire(
                                    "Gagal",
                                    data.message || "Gagal memperbarui status.",
                                    "error"
                                );
                            }
                        })
                        .catch(err => {
                            console.error("Gagal:", err);
                            Swal.fire("Error", "Terjadi kesalahan.", "error");
                        });
                }
            });
        }
    });



    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("btnKonfirmasiAmbil")) {
            const transaksiId = e.target.dataset.id;
            const kodeTransaksi = e.target.dataset.kode;

            if (confirm(`Konfirmasi bahwa transaksi ${kodeTransaksi} sudah diambil?`)) {
                fetch("<?= BASEURL ?>/transaksi/updateStatus", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            id: transaksiId,
                            status: "diambil"
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Status berhasil diperbarui!");
                            location.reload(); // atau bisa update langsung tanpa reload
                        } else {
                            alert("Gagal memperbarui status.");
                        }
                    })
                    .catch(error => {
                        console.error("Terjadi kesalahan:", error);
                        alert("Terjadi kesalahan.");
                    });
            }
        }
    });

    document.getElementById('btnSimpanAmbil').addEventListener('click', function() {
        const allCheckboxes = [...document.querySelectorAll('#konfirmasiSeragamList input[type="checkbox"]')];
        const allIds = allCheckboxes.map(cb => cb.value);
        const checkedIds = allCheckboxes.filter(cb => cb.checked).map(cb => cb.value);

        const transaksiId = document.getElementById('konfirmasiTransaksiId').value;

        fetch(`<?= BASEURL ?>/transaksi/updateStatusAmbil`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    transaksi_id: transaksiId,
                    all: allIds,
                    ambil: checkedIds
                })
            })
            .then(response => {
                if (!response.ok) throw new Error(`Server Error: ${response.status}`);
                return response.json();
            })
            .then(res => {
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Status ambil berhasil diperbarui!',
                        confirmButtonColor: '#3085d6',
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: res.message || 'Gagal memperbarui status.',
                    });
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat menyimpan data: ' + err.message,
                });
            });

    });


    document.getElementById('btnTruncate').addEventListener('click', function(e) {
        e.preventDefault();
        const url = this.dataset.href; // BASEURL/transaksi/truncate

        Swal.fire({
            title: 'Yakin ingin mengosongkan semua transaksi?',
            text: 'Data transaksi (master & detail) akan hilang permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, kosongkan!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user pilih Ya, redirect ke controller truncate
                window.location.href = url;
            }
        });
    });
</script>