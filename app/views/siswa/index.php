<?php $this->view('templates/header', $data); ?>
<!-- DataTables CSS -->
<!-- <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet"> -->

<!-- DataTables JS -->
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script> -->
<?php
// Ambil flash message jika ada
$flash = Flasher::flash();
if ($flash) {
    // Mengambil pesan dan jenisnya
    $message = $flash['message'];
    $type = $flash['type']; // success, danger, etc.
} else {
    $message = '';
    $type = '';
}
?>
<style>
    #pageInfo {
        min-width: 70px;
        text-align: center;
        font-size: 1.1rem;
    }
</style>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Manajemen Siswa</h2>
        <div class="d-flex gap-2">
            <!-- Form Import -->
            <form id="importForm"
                action="<?= BASEURL; ?>/siswa/importExcel"
                method="post"
                enctype="multipart/form-data"
                class="d-flex">
                <div class="input-group flex-fill">
                    <input
                        type="file"
                        class="form-control"
                        name="file"
                        id="file"
                        required
                        accept=".xlsx,.xls">
                    <button
                        type="button"
                        class="btn btn-primary text-nowrap"
                        id="btnImport">
                        <i class="bi bi-upload"></i> Import
                    </button>
                </div>
            </form>

            <!-- Tombol Download Format Import -->
            <a href="<?= BASEURL; ?>/file/format_import_siswa.xlsx" class="btn btn-success text-nowrap" download>
                <i class="bi bi-download"></i> Download Format Import
            </a>

            <!-- Tombol Tambah Siswa -->
            <button type="button" id="btnTambah" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#modalSiswa">
                <i class="bi bi-plus-circle"></i> Tambah Siswa
            </button>
        </div>
    </div>
    <!-- Flash Message -->
    <div id="flash-message"></div>
    <div class="input-group mb-3">
        <input type="text" id="searchSiswa" class="form-control" placeholder="Cari Nama / NIS / Jurusan...">
        <select id="sortBy" class="form-select" style="max-width: 200px;">
            <option value="">Urutkan</option>
            <option value="nama_asc">Nama A-Z</option>
            <option value="nama_desc">Nama Z-A</option>
            <option value="nis_asc">NIS Asc</option>
            <option value="nis_desc">NIS Desc</option>
        </select>
        <button class="btn btn-outline-primary" type="button" id="btnCariSiswa"> <i class="bi bi-search"></i> Cari</button>
    </div>


    <!-- Tabel Data Siswa -->
    <div class="card shadow-sm rounded-3 mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <div id="loadingSpinner" class="text-center my-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <button id="btnDeleteSelected" class="btn btn-danger mb-2 mt-2 ms-2" disabled>
                    <i class="bi bi-trash"></i> Hapus Terpilih
                </button>
                <table id="tabelSiswa" class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>No</th>
                            <th>No. Pendaftaran</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Jurusan</th>
                            <th>Wali</th>
                            <th>No. HP</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="dataSiswa">
                        <!-- Data siswa akan dimuat dengan AJAX -->
                    </tbody>
                </table>

                <nav aria-label="Siswa Page Navigation" style="margin-top: 1rem;">
                    <ul class="pagination justify-content-center" id="paginationSiswa">
                        <!-- Dynamic isi dari JavaScript -->
                    </ul>
                </nav>


            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Siswa -->
<div id="modalSiswa" class="modal fade" tabindex="-1" aria-labelledby="modalSiswaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="modalSiswaLabel">Tambah/Edit Siswa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSiswa">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label for="no_pendaftaran" class="form-label">No. Pendaftaran</label>
                        <input type="text" class="form-control" id="no_pendaftaran" name="no_pendaftaran" required>
                    </div>

                    <div class="mb-3">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>

                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select id="jurusan" name="jurusan" class="form-control">
                            <option value="">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusan as $row) : ?>
                                <option value="<?= htmlspecialchars($row['nama_jurusan']); ?>">
                                    <?= htmlspecialchars($row['nama_jurusan']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="wali" class="form-label">Wali</label>
                        <input type="text" class="form-control" id="wali" name="wali">
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No. HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp">
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-labelledby="konfirmasiModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="konfirmasiModalLabel">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin mengimpor data atau menambah siswa? Pastikan file yang diupload sudah benar.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmSubmit">
                    <span id="confirmSubmitText">Ya, Lanjutkan</span>
                    <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    let totalPages = 1; // Akan di-update berdasarkan jumlah total data yang ada

    function updatePageInfo() {
        $("#pageInfo").text(`Page ${currentPage} of ${totalPages}`);
        // Disable tombol Prev jika di halaman pertama
        $("#prevPage").prop('disabled', currentPage === 1);
        // Disable tombol Next jika di halaman terakhir
        $("#nextPage").prop('disabled', currentPage === totalPages);
    }

    function loadSiswa(keyword = '', sortBy = '', page = 1) {
        const limit = 15;
        const offset = (page - 1) * limit;

        // Tampilkan spinner dulu
        $("#loadingSpinner").fadeIn(200);
        $("#dataSiswa").fadeOut(200); // Sembunyikan tabel dulu

        $.ajax({
            url: "siswa/getAllSiswa",
            type: "GET",
            dataType: "json",
            data: {
                search: keyword,
                sort_by: sortBy,
                limit: limit,
                offset: offset
            },
            success: function(response) {
                $("#dataSiswa").empty(); // kosongkan tabel

                if (response.data.trim() === "") {
                    $("#dataSiswa").html("<tr><td colspan='9' class='text-center'>Data tidak ditemukan.</td></tr>");
                } else {
                    $("#dataSiswa").html(response.data);

                    // Animasi setiap baris satu per satu
                    $("#dataSiswa tr").hide().each(function(index) {
                        $(this).delay(70 * index).fadeIn(300);
                    });

                    totalPages = response.totalPages;
                    renderPagination(page, totalPages);
                }

                $("#loadingSpinner").fadeOut(200); // Sembunyikan spinner
                $("#dataSiswa").fadeIn(400); // Tampilkan tabel setelah loading

                // Scroll ke atas tabel siswa setelah load
                $('html, body').animate({
                    scrollTop: $("#dataSiswa").offset().top - 100
                }, 600);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                $("#loadingSpinner").fadeOut(200); // Tetap hilangkan spinner kalau error
            }
        });
    }


    function renderPagination(currentPage, totalPages) {
        const pagination = $("#paginationSiswa");
        pagination.empty();

        if (totalPages <= 1) return;

        const prevClass = currentPage === 1 ? "disabled" : "";
        pagination.append(`
        <li class="page-item ${prevClass}">
            <a class="page-link" href="#" onclick="loadSiswa('', '', ${currentPage - 1}); return false;">&laquo;</a>
        </li>
    `);

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, currentPage + 2);

        if (startPage > 1) {
            pagination.append(`
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadSiswa('', '', 1); return false;">1</a>
            </li>
        `);
            if (startPage > 2) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            const activeClass = currentPage === i ? "active" : "";
            pagination.append(`
            <li class="page-item ${activeClass}">
                <a class="page-link" href="#" onclick="loadSiswa('', '', ${i}); return false;">${i}</a>
            </li>
        `);
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pagination.append(`<li class="page-item disabled"><span class="page-link">...</span></li>`);
            }
            pagination.append(`
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadSiswa('', '', ${totalPages}); return false;">${totalPages}</a>
            </li>
        `);
        }

        const nextClass = currentPage === totalPages ? "disabled" : "";
        pagination.append(`
        <li class="page-item ${nextClass}">
            <a class="page-link" href="#" onclick="loadSiswa('', '', ${currentPage + 1}); return false;">&raquo;</a>
        </li>
    `);
    }





    // Event handlers
    $("#btnCariSiswa").click(function() {
        const keyword = $("#searchSiswa").val();
        const sortBy = $("#sortBy").val();
        currentPage = 1; // Reset ke halaman pertama
        loadSiswa(keyword, sortBy, currentPage);
    });

    $("#searchSiswa").keypress(function(e) {
        if (e.which === 13) {
            $("#btnCariSiswa").click();
        }
    });

    $("#sortBy").change(function() {
        const keyword = $("#searchSiswa").val();
        const sortBy = $(this).val();
        currentPage = 1; // Reset ke halaman pertama
        loadSiswa(keyword, sortBy, currentPage);
    });

    // Pagination buttons
    $("#prevPage").click(function() {
        if (currentPage > 1) {
            currentPage--;
            const keyword = $("#searchSiswa").val();
            const sortBy = $("#sortBy").val();
            loadSiswa(keyword, sortBy, currentPage);
        }
    });

    $("#nextPage").click(function() {
        if (currentPage < totalPages) {
            currentPage++;
            const keyword = $("#searchSiswa").val();
            const sortBy = $("#sortBy").val();
            loadSiswa(keyword, sortBy, currentPage);
        }
    });

    document.getElementById('file').addEventListener('change', function() {
        const btnImport = document.getElementById('btnImport');
        if (this.files.length > 0) {
            btnImport.disabled = false;
        } else {
            btnImport.disabled = true;
        }
    });

    // Cek saat klik tombol Import, kalau tidak ada file munculkan alert
    document.getElementById('btnImport').addEventListener('click', function() {
        const fileInput = document.getElementById('file');
        if (fileInput.files.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops!',
                text: 'Silakan pilih file terlebih dahulu sebelum mengimpor.'
            });

            // Jangan membuka modal jika tidak ada file yang dipilih
            return false; // Menghentikan eksekusi lebih lanjut
        }

        // Buka modal jika ada file yang dipilih
        var modalElement = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
        modalElement.show();
    });

    // Submit form saat klik "Ya, Lanjutkan" + tampilkan loading
    document.getElementById('confirmSubmit').addEventListener('click', function() {
        const fileInput = document.getElementById('file');
        if (fileInput.files.length === 0) {
            alert('Silakan pilih file terlebih dahulu.');
            return;
        }

        // Ganti tombol menjadi loading
        document.getElementById('confirmSubmitText').textContent = 'Mengirim...';
        document.getElementById('loadingSpinner').classList.remove('d-none');

        // Disable tombol supaya tidak double klik
        this.disabled = true;

        // Submit form setelah sedikit delay supaya user lihat animasi
        setTimeout(function() {
            document.getElementById('importForm').submit();
        }, 500);
    });




    // Panggil saat pertama load
    $(document).ready(function() {
        loadSiswa();
    });


    $(document).ready(function() {


        // Inisialisasi DataTable
        // $('#tabelSiswa').DataTable({
        //     responsive: true,
        //     pageLength: 10
        // });

        // Pilih semua checkbox
        $('#selectAll').on('change', function() {
            $('.selectItem').prop('checked', this.checked);
            toggleDeleteButton();
        });

        // Centang per item
        $(document).on('change', '.selectItem', function() {
            if ($('.selectItem:checked').length === $('.selectItem').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
            toggleDeleteButton();
        });

        // Tampilkan/sembunyikan tombol hapus bulk
        function toggleDeleteButton() {
            $('#btnDeleteSelected').prop('disabled', $('.selectItem:checked').length === 0);
        }

        // Klik tombol Hapus Terpilih
        $('#btnDeleteSelected').on('click', function() {
            const selectedIds = $('.selectItem:checked').map(function() {
                return this.value;
            }).get();

            if (selectedIds.length === 0) return;


            Swal.fire({
                title: 'Yakin menghapus data terpilih?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tombol loading
                    $('#btnDeleteSelected').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghapus...');
                    $('#btnDeleteSelected').prop('disabled', true);

                    // Kirim request hapus
                    fetch('<?= BASEURL ?>/siswa/bulkDelete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                ids: selectedIds
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            if (data.success) {
                                Swal.fire('Berhasil!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Gagal!', data.message, 'error');
                                resetButton();
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error!', 'Terjadi kesalahan.', 'error');
                            console.error(error);
                            resetButton();
                        });
                }
            });
        });

        function resetButton() {
            $('#btnDeleteSelected').html('<i class="bi bi-trash"></i> Hapus Terpilih');
            toggleDeleteButton();
        }



        // Tambah Siswa
        $("#btnTambah").click(function() {
            $("#formSiswa")[0].reset();
            $("#id").val("");
            $("#modalSiswaLabel").text("Tambah Siswa");
        });

        // Simpan Siswa
        $("#formSiswa").submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            $.post("<?= BASEURL ?>/siswa/saveSiswa", formData, function(response) {
                response = JSON.parse(response);
                showFlashMessage(response.flash.message, response.flash.type);
                $("#modalSiswa").modal("hide");
                loadSiswa(); // Muat data terbaru setelah simpan
            });
        });


        // Edit Siswa
        $(document).on("click", ".btnEdit", function() {
            let id = $(this).data("id");

            $.get("<?= BASEURL ?>/siswa/getSiswaById/" + id, function(response) {
                let data = JSON.parse(response);

                $("#id").val(data.id);
                $("#no_pendaftaran").val(data.no_pendaftaran);
                $("#nis").val(data.nis);
                $("#nama").val(data.nama);
                $("#jenis_kelamin").val(data.jenis_kelamin);
                $("#wali").val(data.wali);
                $("#no_hp").val(data.no_hp);

                let jurusanDariDB = data.jurusan.trim().toUpperCase();
                $("#jurusan option").each(function() {
                    let valueOption = $(this).val().trim().toUpperCase();
                    if (valueOption === jurusanDariDB) {
                        $(this).prop("selected", true);
                    }
                });

                $("#modalSiswaLabel").text("Edit Siswa");
                $("#modalSiswa").modal("show");
            });
        });

        // Hapus Siswa
        $(document).on("click", ".btnDelete", function() {
            let id = $(this).data("id");
            let nama = $(this).data("nama");

            Swal.fire({
                title: "Konfirmasi Hapus",
                text: `Apakah Anda yakin ingin menghapus data siswa ${nama}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("<?= BASEURL ?>/siswa/deleteSiswa", {
                        id: id
                    }, function(response) {
                        response = JSON.parse(response);
                        showFlashMessage(response.flash.message, response.flash.type);
                        loadSiswa(); // Muat data terbaru setelah hapus
                    });
                }
            });
        });

        const flashMessage = "<?php echo $message; ?>";
        const flashType = "<?php echo $type; ?>";

        if (flashMessage) {
            // Panggil showFlashMessage() untuk menampilkan pesan
            showFlashMessage(flashMessage, flashType);
        }

        // Tampilkan pesan flash
        function showFlashMessage(message, type) {
            let flashHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
          ${message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
            $("#flash-message").html(flashHtml);
            setTimeout(() => $(".alert").fadeOut(), 3000);
        }
    });


    document
        .getElementById('btnImport')
        .addEventListener('click', () => {
            const fileInput = document.getElementById('file');
            if (!fileInput.files.length) {
                // Jika belum pilih file, beri alert
                return alert('Silakan pilih file Excel terlebih dahulu.');
            }
            document.getElementById('importForm').submit();
        });

    // (Opsional) Jika ingin menampilkan nama file yang dipilih:
    document
        .getElementById('file')
        .addEventListener('change', function() {
            const fileName = this.files[0]?.name || '';
            // kita bisa misalnya ubah placeholder button atau tampilkan di sebelah
            document.getElementById('btnImport').innerHTML =
                `<i class="bi bi-upload"></i> ${fileName}`;
        });
</script>

<?php $this->view('templates/footer'); ?>