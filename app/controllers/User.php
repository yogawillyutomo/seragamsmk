<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

class User extends Controller
{
    private object $userModel;

    public function __construct()
    {
        AuthMiddleware::checkLogin(); // Pastikan pengguna sudah login sebelum mengakses
        AuthMiddleware::checkRole('admin');
        $this->userModel = $this->model('User_model'); // Inisialisasi model User
    }

    // Menampilkan halaman utama manajemen user
    public function index(): void
    {
        $data['judul'] = 'Manajemen User';
        $data['users'] = $this->userModel->getAllUsers(); // Ambil semua data user dari model
        $this->view('templates/header', $data);
        $this->view('user/index', $data);
        $this->view('templates/footer');
    }

    // Menampilkan data user dalam bentuk HTML untuk tabel
    public function getAllUsers(): void
    {
        $users = $this->userModel->getAllUsers(); // Ambil semua data user
        $i = 1;
        foreach ($users as $user) {
            echo "<tr>
                    <td>{$i}</td>
                    <td>{$user['username']}</td>
                    <td>{$user['nama']}</td>
                    <td>{$user['created_at']}</td>
                    <td>{$user['role']}</td>
                    <td>
                        <button class='btn btn-warning btnEdit' data-id='{$user['id']}'><i class='bi bi-pencil-square me-2'></i></button>
                        <button class='btn btn-danger btnDelete' data-id='{$user['id']}' data-user='{$user['username']}'><i class='bi bi-trash me-2'></i></button>
                    </td>
                  </tr>";

            $i++;
        }
    }

    // Mengambil data user berdasarkan ID
    public function getUserById(): void
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $user = $this->userModel->getUserById($id);

            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["error" => "User tidak ditemukan"]);
            }
        } else {
            echo json_encode(["error" => "ID tidak ditemukan"]);
        }
    }

    // Menyimpan data user baru atau memperbarui user yang sudah ada
    public function saveUser(): void
    {
        if (!isset($_POST['id']) || $_POST['id'] == "") {
            // Menambah user baru
            $success = $this->userModel->createUser($_POST);
            $message = $success ? "User berhasil ditambahkan!" : "Gagal menambahkan user.";
        } else {
            // Mengedit user yang sudah ada
            $success = $this->userModel->updateUser((int)$_POST['id'], $_POST);
            $message = $success ? "User berhasil diperbarui!" : "Gagal mengedit user.";
        }

        Flasher::setFlash($message, "success", $success ? "success" : "danger");

        // Kirim response dalam bentuk JSON
        echo json_encode([
            "status" => $success ? "success" : "error",
            "flash" => Flasher::Flash()
        ]);
    }

    // Menghapus user
    public function deleteUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $success = $this->userModel->deleteUser($id);
                $message = $success ? "User berhasil dihapus!" : "Gagal menghapus User.";
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

    /**
     * Import pengguna dari Excel (.xls/.xlsx)
     */
    public function importExcel()
    {
        header('Content-Type: application/json');

        if (empty($_FILES['file']['tmp_name'])) {
            echo json_encode([
                'status' => 'error',
                'flash'  => [
                    'message' => 'File tidak ditemukan',
                    'type'    => 'danger'
                ]
            ]);
            return;
        }

        try {
            // 1) Baca file via PhpSpreadsheet
            $reader      = IOFactory::createReaderForFile($_FILES['file']['tmp_name']);
            $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
            $rows        = $spreadsheet->getActiveSheet()->toArray();

            $imported = 0;
            foreach ($rows as $i => $row) {
                if ($i === 0) {
                    // baris 0 dianggap header, skip
                    continue;
                }

                // Asumsi kolom: A=username, B=nama, C=password, D=role
                [$username, $nama, $plainPassword, $role] = array_map('trim', $row);

                if (!$username || !$nama || !in_array($role, ['admin', 'kasir', 'gudang'])) {
                    // lewati baris yang tidak valid
                    continue;
                }

                // cek sudah ada?
                $existing = $this->userModel->findByUsername($username);

                $data = [
                    'username' => $username,
                    'nama'     => $nama,
                    'role'     => $role
                ];

                // password wajib buat user baru; untuk update, password hanya jika diisi
                if ($plainPassword !== '') {
                    $data['password'] = password_hash($plainPassword, PASSWORD_DEFAULT);
                }

                if ($existing) {
                    // update: panggil updateUser()
                    $this->userModel->updateUser((int)$existing['id'], $data);
                } else {
                    // create: hanya jika password diisi
                    if (empty($data['password'])) {
                        continue;
                    }
                    $this->userModel->createUser($data);
                }

                $imported++;
            }

            echo json_encode([
                'status' => 'success',
                'flash'  => [
                    'message' => "Berhasil mengimpor {$imported} pengguna.",
                    'type'    => 'success'
                ]
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'status' => 'error',
                'flash'  => [
                    'message' => 'Gagal mengimpor: ' . $e->getMessage(),
                    'type'    => 'danger'
                ]
            ]);
        }
    }
    
}
