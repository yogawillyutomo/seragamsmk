<?php $this->view('templates/header', $data); ?>

<div class="container my-5">
    <h2 class="mb-4">Manajemen User</h2>

    <div class="d-flex mb-3">
        <!-- Form Import -->
        <form id="importUserForm" action="<?= BASEURL; ?>/user/importExcel" method="post" enctype="multipart/form-data" class="d-flex me-3">
            <input type="file" class="form-control" name="file" id="fileUser" required accept=".xlsx,.xls">
            <button type="button" class="btn btn-success ms-2 text-nowrap" id="btnImportUser">
                <i class="bi bi-upload"></i> Import
            </button>
        </form>

        <!-- Tombol Tambah -->
        <button type="button" id="btnTambah" class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#modalUser">
            <i class="bi bi-plus-circle"></i>
            Tambah Data
        </button>
    </div>

    <div id="flash-message"></div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 20%;">Username</th>
                    <th>Nama</th>
                    <th style="width: 20%;">Dibuat</th>
                    <th style="width: 15%;">Role</th>
                    <th style="width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <!-- Data akan di-load lewat AJAX -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal User -->
<div class="modal fade" id="modalUser" tabindex="-1" aria-labelledby="modalUserLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title" id="modalUserLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUser">
                    <input type="hidden" id="userId" name="id">

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <small id="passwordNote" class="text-muted d-none">(Kosongkan jika tidak ingin mengubah)</small></label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">-- Pilih Role --</option>
                            <option value="admin">Admin</option>
                            <option value="kasir">Kasir</option>
                            <option value="gudang">Gudang</option>
                        </select>
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

<script>
    $(document).ready(function() {
        loadUsers();

        // Tombol Tambah
        $("#btnTambah").click(function() {
            $("#modalUserLabel").text("Tambah User");
            $("#formUser")[0].reset();
            $("#userId").val("");
            $("#password").prop("required", true); // password wajib saat tambah
            $("#passwordNote").addClass("d-none"); // Sembunyikan catatan password saat tambah
        });


        // Tombol Edit
        $(document).off("click", ".btnEdit").on("click", ".btnEdit", function() {
            let id = $(this).data("id");
            $.ajax({
                url: "user/getUserById",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    try {
                        let user = JSON.parse(response);
                        $("#userId").val(user.id);
                        $("#username").val(user.username);
                        $("#nama").val(user.nama);
                        $("#password").val(""); // kosongkan password
                        $("#role").val(user.role);
                        $("#modalUserLabel").text("Edit User");
                        $("#password").prop("required", false); // password tidak wajib saat edit
                        $("#passwordNote").removeClass("d-none"); // Tampilkan catatan password saat edit
                        $("#modalUser").modal("show");
                    } catch (error) {
                        Swal.fire("Error!", "Gagal memproses data user.", "error");
                    }
                },
                error: function() {
                    Swal.fire("Error!", "Gagal mengambil data user.", "error");
                }
            });
        });

        // Auto fokus modal
        $("#modalUser").on("shown.bs.modal", function() {
            $(this).removeAttr("aria-hidden").focus();
        });


        // Submit form User
        $("#formUser").submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'user/saveUser',
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        response = typeof response === "string" ? JSON.parse(response) : response;
                        if (response.status === "success") {
                            $("#modalUser").modal("hide");
                            loadUsers();
                            showFlashMessage(response.flash.message, response.flash.type);
                        } else {
                            showFlashMessage(response.flash.message, "danger");
                        }
                    } catch (e) {
                        showFlashMessage("Terjadi kesalahan saat memproses data dari server.", "danger");
                    }
                },
                error: function(xhr) {
                    showFlashMessage("Terjadi kesalahan dalam pengiriman data.", "danger");
                }
            });
        });

        $(document).off("click", ".btnDelete").on("click", ".btnDelete", function() {
            let id = $(this).data("id");
            let username = $(this).data("user");

            Swal.fire({
                title: "Konfirmasi Hapus",
                text: `Apakah Anda yakin ingin menghapus user "${username}"?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "user/deleteUser",
                        type: "POST",
                        data: {
                            id: id
                        },
                        success: function(response) {
                            response = JSON.parse(response);
                            Swal.fire("Deleted!", "User berhasil dihapus.", "success").then(() => {
                                loadUsers();
                            });
                            showFlashMessage(response.flash.message, response.flash.type);
                        },
                        error: function() {
                            Swal.fire("Error!", "Gagal menghapus user.", "error");
                        }
                    });
                }
            });
        });

        function loadUsers() {
            $.ajax({
                url: "user/getAllUsers",
                type: "GET",
                success: function(data) {
                    $("#userTable").html(data);
                },
                error: function() {
                    console.error("Gagal memuat data user");
                }
            });
        }

        function showFlashMessage(message, type) {
            let flashHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $("#flash-message").html(flashHtml);
            setTimeout(() => {
                $(".alert").fadeOut();
            }, 3000);
        }

        // Handle Import User
        $('#btnImportUser').on('click', function() {
            const input = $('#fileUser');
            if (!input.val()) {
                showFlashMessage('Pilih file Excel terlebih dahulu.', 'warning');
                return;
            }

            const formEl = document.getElementById('importUserForm');
            const formData = new FormData(formEl);

            $.ajax({
                url: '<?= BASEURL; ?>/user/importExcel',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Jika server tidak mengembalikan JSON, coba parse
                    let res = response;
                    if (typeof res === 'string') {
                        try {
                            res = JSON.parse(res);
                        } catch (e) {
                            /* ignore */ }
                    }
                    if (res.status === 'success') {
                        showFlashMessage(res.flash.message || 'Import berhasil!', 'success');
                        loadUsers();
                    } else {
                        showFlashMessage(res.flash?.message || 'Import gagal.', 'danger');
                    }
                },
                error: function(xhr) {
                    showFlashMessage('Terjadi kesalahan saat mengimport.', 'danger');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<?php $this->view('templates/footer'); ?>