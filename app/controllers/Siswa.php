<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class Siswa extends Controller
{
    private Siswa_model $siswaModel;

    public function __construct()
    {
        AuthMiddleware::checkLogin();
        $this->siswaModel = $this->model('Siswa_model');
    }

    public function index(): void
    {
        $data['judul'] = 'Manajemen Siswa';
        $data['jurusan'] = $this->siswaModel->getAllJurusan();
        // $this->view('templates/header', $data);
        $this->view('siswa/index', $data);
        // $this->view('templates/footer');
    }

    public function getAllSiswa(): void
    {
        $filters = [
            'keyword'        => $_GET['search'] ?? '',
            'sort_by'        => $_GET['sort_by'] ?? '',
            'jenis_kelamin'  => $_GET['jenis_kelamin'] ?? '',
            'jurusan'        => $_GET['jurusan'] ?? '',
            'limit'          => isset($_GET['limit']) ? (int)$_GET['limit'] : 15,
            'offset'         => isset($_GET['offset']) ? (int)$_GET['offset'] : 0
        ];

        $siswa = $this->siswaModel->getAll($filters);
        $totalSiswa = $this->siswaModel->getTotal($filters);

        $html = '';
        $no = 1;
        foreach ($siswa as $item) {
            $html .= "<tr>
                        <td><input type='checkbox' class='selectItem' value='{$item['id']}'></td>
                        <td>{$no}</td>
                        <td>{$item['no_pendaftaran']}</td>
                        <td>{$item['nis']}</td>
                        <td>{$item['nama']}</td>
                        <td>" . ($item['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') . "</td>
                        <td>{$item['jurusan']}</td>
                        <td>{$item['wali']}</td>
                        <td>{$item['no_hp']}</td>
                        <td>
                            <button class='btn btn-warning btnEdit' data-id='{$item['id']}'> <i class='bi bi-pencil-square me-2'></i></button>
                            <button class='btn btn-danger btnDelete' data-id='{$item['id']}' data-nama='{$item['nama']}'><i class='bi bi-trash me-2'></i></button>
                        </td>
                      </tr>";
            $no++;
        }

        // Hitung total halaman untuk pagination
        $limit = $filters['limit'] ?: 10;
        $totalPages = ceil($totalSiswa / $limit);
        header('Content-Type: application/json');
        echo json_encode([
            'data' => $html,
            'totalPages' => $totalPages
        ]);
    }


    public function cariSiswa(): void
    {
        if (isset($_GET['term'])) {
            $keyword = $_GET['term'];
            $siswa = $this->siswaModel->search($keyword);

            $hasil = [];
            foreach ($siswa as $row) {
                $hasil[] = [
                    'label' => $row['nama'] . " (" . $row['nis'] . ")",
                    'value' => $row['nama'],
                    'id' => $row['id'],
                    'nis' => $row['nis'],
                    'jenis_kelamin' => ($row['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan',
                    'jurusan' => $row['jurusan'],
                    'no_hp' => $row['no_hp']
                ];
            }
            echo json_encode($hasil);
        }
    }

    public function getSiswaById(int $id): void
    {
        $siswa = $this->siswaModel->getById($id);
        echo json_encode($siswa);
    }

    public function saveSiswa(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id' => $_POST['id'] ?? null,
                'no_pendaftaran' => $_POST['no_pendaftaran'],
                'nis' => $_POST['nis'],
                'nama' => $_POST['nama'],
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'jurusan' => $_POST['jurusan'],
                'wali' => $_POST['wali'],
                'no_hp' => $_POST['no_hp']
            ];

            if (empty($data['id'])) {
                $success = $this->siswaModel->create($data);
                $message = $success ? "Siswa berhasil ditambahkan!" : "Gagal menambahkan siswa.";
            } else {
                $success = $this->siswaModel->update($data);
                $message = $success ? "Siswa berhasil diperbarui!" : "Gagal mengedit siswa.";
            }

            Flasher::setFlash($message, "success", $success ? "success" : "danger");

            echo json_encode([
                "status" => $success ? "success" : "error",
                "flash" => Flasher::Flash()
            ]);
        }
    }

    public function deleteSiswa(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $success = $this->siswaModel->delete((int)$id);
                $message = $success ? "Siswa berhasil dihapus!" : "Gagal menghapus siswa.";
            } else {
                $message = "ID tidak ditemukan.";
                $success = false;
            }

            Flasher::setFlash($message, "success", $success ? "success" : "danger");

            echo json_encode([
                "status" => $success ? "success" : "error",
                "flash" => Flasher::Flash()
            ]);
        }
    }



    public function bulkDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            if (isset($data['ids']) && is_array($data['ids'])) {
                $ids = $data['ids'];

                if ($this->siswaModel->deleteSelected($ids)) {
                    echo json_encode(['success' => true, 'message' => 'Data berhasil dihapus.']);
                    exit;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Gagal menghapus data.']);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
                exit;
            }
        }
    }


    public function importExcel(): void
    {
        if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['file']['tmp_name'];

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray();

                // Ambil header dan validasi
                $header = array_map(fn($h) => is_string($h) ? strtolower(trim($h)) : '', $data[0]);
                $expectedHeader = ['no pendaftaran', 'nis', 'nama', 'jenis kelamin', 'jurusan', 'wali', 'no hp'];

                if (count($data) < 2) {
                    Flasher::setFlash("File tidak berisi data siswa.", "danger", "danger");
                    header("Location: " . BASEURL . "/siswa");
                    exit;
                }

                if ($header !== $expectedHeader) {
                    Flasher::setFlash("Struktur kolom tidak sesuai. Harus: " . implode(', ', $expectedHeader), "danger", "danger");
                    header("Location: " . BASEURL . "/siswa");
                    exit;
                }



                array_shift($data); // skip header

                foreach ($data as $row) {
                    if (count($row) < count($expectedHeader)) continue; // skip baris tidak lengkap

                    $siswaData = [
                        "no_pendaftaran" => $row[0],
                        "nis" => $row[1],
                        "nama" => $row[2],
                        "jenis_kelamin" => $row[3],
                        "jurusan" => $row[4],
                        "wali" => $row[5],
                        "no_hp" => $row[6]
                    ];

                    $this->siswaModel->create($siswaData);
                }

                Flasher::setFlash("Import data berhasil!", "success", "success");
            } catch (Exception $e) {
                Flasher::setFlash("Gagal mengimpor data: " . $e->getMessage(), "danger", "danger");
            }
        } else {
            Flasher::setFlash("Gagal mengunggah file!", "danger", "danger");
        }

        header("Location: " . BASEURL . "/siswa");
        exit;
    }


    public function getSiswaPaged(): void
    {
        $limit = $_GET['limit'] ?? 10;  // batas data per halaman
        $page = $_GET['page'] ?? 1;     // halaman ke berapa

        $result = $this->siswaModel->getPaged((int)$limit, (int)$page);

        echo json_encode($result);
    }
}
