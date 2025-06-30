<?php

class Jurusan extends Controller
{
    private Jurusan_model $jurusanModel;

    public function __construct()
    {
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkRole('admin');

        $this->jurusanModel = $this->model('Jurusan_model');
    }

    /**
     * Halaman daftar jurusan
     */
    public function index(): void
    {
        $keyword = $_GET['keyword'] ?? '';
        $page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit   = 10;
        $offset  = ($page - 1) * $limit;

        $filters = [
            'keyword'  => $keyword,
            'limit'    => $limit,
            'offset'   => $offset,
            'sort_by'  => 'nama_jurusan_asc',
        ];

        $data['title']     = 'Manajemen Jurusan';
        $data['jurusan']   = $this->jurusanModel->getFiltered($filters);
        $data['totalData'] = $this->jurusanModel->countFiltered($filters);
        $data['currentPage'] = $page;
        $data['limit']     = $limit;
        $data['keyword']   = $keyword;

        $this->view('templates/header', ['judul' => $data['title']]);
        // load konten jurusan
        $this->view('jurusan/index', $data);
        // load footer
        $this->view('templates/footer');
    }

    /**
     * Proses tambah jurusan
     */
    public function tambah(): void
    {
        $nama = trim($_POST['nama_jurusan'] ?? '');

        if ($nama === '') {
            Flasher::setFlash('Nama jurusan tidak boleh kosong.', 'gagal', 'danger');
            header('Location: ' . BASEURL . '/jurusan');
            exit;
        }

        $result = $this->jurusanModel->insertJurusan([
            'nama_jurusan' => $nama
        ]);

        if ($result) {
            Flasher::setFlash('Jurusan berhasil ditambahkan.', 'sukses', 'success');
        } else {
            Flasher::setFlash('Gagal menambahkan jurusan.', 'gagal', 'danger');
        }

        header('Location: ' . BASEURL . '/jurusan');
        exit;
    }

    /**
     * Proses edit jurusan
     */
    public function edit(): void
    {
        $id   = (int)($_POST['id'] ?? 0);
        $nama = trim($_POST['nama_jurusan'] ?? '');

        if ($id <= 0 || $nama === '') {
            Flasher::setFlash('Data tidak valid.', 'gagal', 'danger');
            header('Location: ' . BASEURL . '/jurusan');
            exit;
        }

        $result = $this->jurusanModel->updateJurusan($id, [
            'nama_jurusan' => $nama
        ]);

        if ($result) {
            Flasher::setFlash('Jurusan berhasil diubah.', 'sukses', 'success');
        } else {
            Flasher::setFlash('Gagal mengubah jurusan.', 'gagal', 'danger');
        }

        header('Location: ' . BASEURL . '/jurusan');
        exit;
    }

    /**
     * Proses hapus jurusan
     */
    public function hapus($id): void
    {
        $id = (int)$id;

        if ($id <= 0) {
            Flasher::setFlash('ID tidak valid.', 'gagal', 'danger');
            header('Location: ' . BASEURL . '/jurusan');
            exit;
        }

        $result = $this->jurusanModel->deleteJurusan($id);

        if ($result) {
            Flasher::setFlash('Jurusan berhasil dihapus.', 'sukses', 'success');
        } else {
            Flasher::setFlash('Gagal menghapus jurusan.', 'gagal', 'danger');
        }

        header('Location: ' . BASEURL . '/jurusan');
        exit;
    }
}
