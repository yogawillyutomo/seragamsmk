<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Data Jurusan</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalJurusan" onclick="openTambahModal()">+ Tambah Jurusan</button>
    </div>

    <?php Flasher::flash(); ?>

    <form method="GET" class="mb-3 row g-2">
        <div class="col-md-4">
            <input type="text" name="keyword" value="<?= htmlspecialchars($data['keyword']) ?>" class="form-control" placeholder="Cari nama jurusan...">
        </div>
        <div class="col-auto">
            <button class="btn btn-secondary" type="submit">Cari</button>
        </div>
        <div class="col-auto">
            <a href="<?= BASEURL; ?>/jurusan" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <form action="<?= BASEURL; ?>/jurusan/import" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="input-group" style="max-width: 400px;">
            <input type="file" name="file_excel" class="form-control" accept=".xlsx" required>
            <button type="submit" class="btn btn-success">Import Excel</button>
        </div>
        <small class="text-muted">Format: <a href="<?= BASEURL; ?>/template_import_jurusan.xlsx">Download Template</a></small>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width:5%;">No</th>
                    <th>Nama Jurusan</th>
                    <th style="width:20%;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data['jurusan'])) : ?>
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada data</td>
                    </tr>
                <?php else : ?>
                    <?php
                    $no = 1 + ($data['currentPage'] - 1) * $data['limit'];
                    foreach ($data['jurusan'] as $j) :
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($j['nama_jurusan']) ?></td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning me-1" onclick="openEditModal(<?= $j['id'] ?>, '<?= htmlspecialchars($j['nama_jurusan'], ENT_QUOTES) ?>')">Edit</button>
                                <button class="btn btn-sm btn-danger" onclick="confirmDelete(<?= $j['id'] ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    $totalPage = ceil($data['totalData'] / $data['limit']);
    if ($totalPage > 1) :
    ?>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $totalPage; $i++) : ?>
                    <li class="page-item <?= $i === $data['currentPage'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&keyword=<?= urlencode($data['keyword']) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
</div>

<!-- Modal Tambah/Edit -->
<div class="modal fade" id="modalJurusan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" method="POST" id="formJurusan">
            <div class="modal-header">
                <h5 class="modal-title" id="modalJurusanLabel">Tambah Jurusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="jurusanId">
                <div class="mb-3">
                    <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                    <input type="text" class="form-control" name="nama_jurusan" id="namaJurusan" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTambahModal() {
        document.getElementById('formJurusan').action = '<?= BASEURL ?>/jurusan/tambah';
        document.getElementById('modalJurusanLabel').innerText = 'Tambah Jurusan';
        document.getElementById('jurusanId').value = '';
        document.getElementById('namaJurusan').value = '';
    }

    function openEditModal(id, nama) {
        document.getElementById('formJurusan').action = '<?= BASEURL ?>/jurusan/edit';
        document.getElementById('modalJurusanLabel').innerText = 'Edit Jurusan';
        document.getElementById('jurusanId').value = id;
        document.getElementById('namaJurusan').value = nama;
        var modal = new bootstrap.Modal(document.getElementById('modalJurusan'));
        modal.show();
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Yakin hapus data?',
            text: "Data jurusan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= BASEURL ?>/jurusan/hapus/' + id;
            }
        });
    }
</script>