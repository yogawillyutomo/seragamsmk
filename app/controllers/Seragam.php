<?php

class Seragam extends Controller
{
    private $seragamModel;

    public function __construct()
    {
        AuthMiddleware::checkLogin();
        $this->seragamModel = $this->model('Seragam_model');
    }

    public function index(): void
    {
        $data['judul'] = 'Manajemen Seragam';
        $this->view('templates/header', $data);
        $this->view('seragam/index', $data);
        $this->view('templates/footer');
    }

    public function getAllSeragam(): void
    {
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkRole('admin');

        $seragamList = $this->seragamModel->getAll();
        $i = 1;
        foreach ($seragamList as $item) {
            echo "<tr data-id=\"{$item['id']}\">
                <td class=\"text-center\">{$i}</td>
                <td>" . htmlspecialchars($item['nama']) . "</td>
                <td>Rp " . number_format($item['harga'], 0, ',', '.') . "</td>
                <td>Rp " . number_format($item['harga_tambahan'], 0, ',', '.') . "</td>
                <td>" . ($item['berhijab'] ? 'Ya' : 'Tidak') . "</td>
                <td>" . ($item['berhijab']
                ? 'Rp ' . number_format($item['harga_berhijab'], 0, ',', '.')
                : '-') . "
                </td>
                <td>
                    <button class=\"btn btn-warning btnEdit btn-sm text-nowrap\" data-id=\"{$item['id']}\">
                        <i class=\"bi bi-pencil-square me-1\"></i> Edit
                    </button>
                    <button class=\"btn btn-danger btnDelete btn-sm text-nowrap\" 
                            data-id=\"{$item['id']}\" 
                            data-nama=\"" . htmlspecialchars($item['nama']) . "\">
                        <i class=\"bi bi-trash me-2\"></i> Hapus
                    </button>
                </td>
              </tr>";
            $i++;
        }
    }


    public function getSeragam(): void
    {
        $data = $this->seragamModel->getAll();
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getSeragamById(int $id): void
    {
        $data = $this->seragamModel->getById($id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function saveSeragam(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $berhijab = isset($_POST['berhijab']) && $_POST['berhijab'] == "1" ? 1 : 0;

            $data = [
                'id'              => $_POST['id'] ?? null,
                'nama'            => $_POST['nama'],
                'harga'           => $_POST['harga_dasar'],
                'harga_tambahan'  => $_POST['harga_tambahan'],
                'berhijab'        => $berhijab,
                'harga_berhijab'  => $_POST['harga_berhijab'] ?: 0
            ];

            if (empty($data['id'])) {
                $result = $this->seragamModel->create($data);
                $message = $result ? "Seragam berhasil ditambahkan!" : "Gagal menambahkan seragam.";
            } else {
                $result = $this->seragamModel->update($data);
                $message = $result ? "Seragam berhasil diperbarui!" : "Gagal mengedit seragam.";
            }

            Flasher::setFlash($message, "success", $result ? "success" : "danger");
            echo json_encode([
                "status" => $result ? "success" : "error",
                "flash"  => Flasher::Flash()
            ]);
        }
    }

    public function deleteSeragam(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;

            if ($id) {
                $result = $this->seragamModel->delete($id);
                $message = $result ? "Seragam berhasil dihapus!" : "Gagal menghapus seragam.";
            } else {
                $result = false;
                $message = "ID seragam tidak valid.";
            }

            Flasher::setFlash($message, "success", $result ? "success" : "danger");
            echo json_encode([
                "status" => $result ? "success" : "error",
                "flash"  => Flasher::Flash()
            ]);
        }
    }

    public function updateOrder()
    {
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkRole('admin');

        // 2) Ambil array order dari POST
        //    HTML/JS harus mengirimkan key "order[]" atau "order" sebagai array
        $order = $_POST['order'] ?? [];
        if (! is_array($order)) {
            header('HTTP/1.1 400 Bad Request');
            header('Content-Type: application/json');
            echo json_encode([
                'status'  => 'error',
                'message' => 'Payload tidak valid, format order harus array.'
            ]);
            exit;
        }

        // 3) Loop dan update sort_order
        foreach ($order as $index => $id) {
            // cast ke int demi keamanan
            $this->seragamModel->updateSortOrder((int)$id, (int)$index);
        }

        // 4) Kembalikan response JSON sukses
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }
}
