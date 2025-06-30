<?php

class Transaksi extends Controller
{
    private $transaksiModel;

    public function __construct()
    {
        AuthMiddleware::checkLogin();

        $this->transaksiModel = $this->model('Transaksi_model');
        // var_dump($_SESSION['role']);
    }

    // Menampilkan halaman utama daftar transaksi
    public function index()
    {


        $data['role'] = $_SESSION['role'];
        $data['judul'] = 'Manajemen Transaksi';
        $this->view('templates/header', $data);
        $this->view('transaksi/index', $data);
        $this->view('templates/footer');
    }

    public function getAllTransaksi()
    {
        try {
            $role = $_SESSION['role'];
            $username = $_SESSION['user_id'];
            $filters = $_GET;

            $limit = isset($filters['limit']) ? (int)$filters['limit'] : 2;
            $offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;

            $data = $this->transaksiModel->getFilteredTransaksi($filters, $role, $username);
            $totalRows = $this->transaksiModel->countFilteredTransaksi($filters, $role, $username);


            echo json_encode([
                'data' => $data,
                'totalPages' => ceil($totalRows / $limit)
            ]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }



    public function getDetail($id)
    {
        $data = $this->model('Transaksi_model')->getDetailItemByTransaksiforAction($id);
        echo json_encode($data);
    }

    // Menambahkan transaksi baru
    public function tambah()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = json_decode(file_get_contents("php://input"), true);
                $kode_transaksi = 'TRX-' . time();
                $tanggal_transaksi = date('Y-m-d H:i:s');
                $kasir_id = $_SESSION['user_id'];
                // $kasir_id = 1;
                $siswa_id = $data['siswa_id'];
                $metode_pembayaran = $data['metode_pembayaran'];
                $status = 'lunas';
                $total_harga = 0;

                // Mengumpulkan data transaksi utama
                $dataTransaksi = [
                    'kode_transaksi' => $kode_transaksi,
                    'kasir_id' => $kasir_id,
                    'siswa_id' => $siswa_id,
                    'metode_pembayaran' => $metode_pembayaran,
                    'total_harga' => 0,
                    'status' => $status
                ];

                // Mengumpulkan data item seragam
                $items = [];
                if (isset($data['seragam'])) {

                    foreach ($data['seragam'] as $seragam) {

                        $items[] = [
                            'seragam_id' => $seragam['id'],
                            'ukuran' => $seragam['ukuran'],
                            'status' => $seragam['status'],
                            'harga' => $seragam['harga']
                        ];
                        // echo "item: " .  $items["seragam_id"] . "<br>";
                        $total_harga += $seragam['harga'];
                    }
                }

                // Update total harga
                $dataTransaksi['total_harga'] = $total_harga;

                // Simpan ke database
                $id_transaksi = $this->transaksiModel->tambahTransaksi($dataTransaksi, $items);

                if ($id_transaksi) {
                    echo json_encode(["status" => "success"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Gagal menyimpan transaksi"]);
                }
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }




    // Mengedit transaksi yang belum lunas
    public function edit()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];
            $siswa_id = $_POST['siswa_id'];
            $metode_pembayaran = $_POST['metode_pembayaran'];

            // Cek status transaksi sebelum mengedit
            $transaksi = $this->transaksiModel->getTransaksiById($id);
            if (!$transaksi) {
                Flasher::setFlash('Transaksi tidak ditemukan!', 'gagal', 'danger');
                header('Location: ' . BASEURL . '/transaksi');
                exit;
            }

            if ($transaksi['status'] === 'lunas') {
                Flasher::setFlash('Transaksi sudah lunas, tidak bisa diedit!', 'peringatan', 'warning');
                header('Location: ' . BASEURL . '/transaksi');
                exit;
            }

            // Update transaksi utama
            $updated = $this->transaksiModel->updateTransaksi($id, $siswa_id, $metode_pembayaran);
            if ($updated) {
                // Hapus detail transaksi lama
                $this->transaksiModel->hapusDetailTransaksi($id);

                $total_harga = 0;

                // Simpan detail transaksi yang baru
                foreach ($_POST['seragam'] as $seragam) {
                    $seragam_id = $seragam['id'];
                    $jumlah = $seragam['jumlah'];
                    $subtotal = $seragam['subtotal'];
                    $total_harga += $subtotal;

                    $this->transaksiModel->tambahDetailTransaksi([
                        'transaksi_id' => $id,
                        'seragam_id' => $seragam_id,
                        'jumlah' => $jumlah,
                        'subtotal' => $subtotal
                    ]);
                }

                // Update total harga transaksi
                $this->transaksiModel->updateTotalHarga($id, $total_harga);

                Flasher::setFlash('Transaksi berhasil diperbarui!', 'success', 'success');
            } else {
                Flasher::setFlash('Gagal memperbarui transaksi!', 'gagal', 'danger');
            }

            header('Location: ' . BASEURL . '/transaksi');
            exit;
        }
    }

    // Membatalkan transaksi (hanya jika sudah lunas)
    public function batal()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $id = $_POST['id'];

            // Cek apakah transaksi ada dan sudah lunas
            $transaksi = $this->transaksiModel->getTransaksiById($id);
            if (!$transaksi) {
                Flasher::setFlash('Transaksi tidak ditemukan!', 'gagal', 'danger');
                header('Location: ' . BASEURL . '/transaksi');
                exit;
            }

            if ($transaksi['status'] !== 'lunas') {
                Flasher::setFlash('Transaksi belum lunas, tidak bisa dibatalkan!', 'peringatan', 'warning');
                header('Location: ' . BASEURL . '/transaksi');
                exit;
            }

            // Batalkan transaksi
            $canceled = $this->transaksiModel->batalTransaksi($id);
            if ($canceled) {
                Flasher::setFlash('Transaksi berhasil dibatalkan!', 'sukses', 'success');
            } else {
                Flasher::setFlash('Gagal membatalkan transaksi!', 'gagal', 'danger');
            }
            header('Location: ' . BASEURL . '/transaksi');
            exit;
        }
    }

    public function kwitansijs()
    {
        $this->view('transaksi/kwitansi');
    }

    public function kwitansi($id)
    {
        $data['judul'] = 'Kwitansi';
        $data['transaksi'] = $this->transaksiModel->getTransaksiById($id);
        $data['items'] = $this->transaksiModel->getDetailItemByTransaksi($id);

        // $this->view('templates/header', $data);
        $this->view('transaksi/kwitansibe', $data);
        // $this->view('templates/footer');
    }


    public function updateStatus()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];
        $status = $data['status'];

        // Validasi data
        if (empty($id) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
            return;
        }

        $transaksi = $this->transaksiModel->getTransaksiById($id);
        if (!$transaksi) {
            echo json_encode(['success' => false, 'message' => 'Transaksi tidak ditemukan']);
            return;
        }

        // Pastikan hanya transaksi lunas yang bisa diubah statusnya (misalnya untuk dibatalkan)
        // if ($transaksi['status'] !== 'lunas') {
        //     echo json_encode(['success' => false, 'message' => 'Hanya transaksi lunas yang bisa diubah']);
        //     return;
        // }

        $updated = $this->transaksiModel->updateStatus($id, $status);
        if ($updated > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tidak ada perubahan status']);
        }
    }


    public function updateStatusAmbil()
    {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);

            if (!isset($data['all']) || !is_array($data['all'])) {
                echo json_encode(['status' => 'failed', 'message' => 'All IDs not supplied.']);
                return;
            }

            $trxID = $data['transaksi_id'];
            $all = $data['all'];
            $ambil = $data['ambil'] ?? [];

            $belum = array_diff($all, $ambil); // Yang tidak dicentang

            $updatedAmbil = $this->transaksiModel->setStatusAmbil($ambil, 'diambil');
            $updatedBelum = $this->transaksiModel->setStatusAmbil($belum, 'belum'); // Atau 'belum diambil'

            count($all) === count($ambil) ? $this->transaksiModel->updateStatus($trxID, "diambil") : $this->transaksiModel->updateStatus($trxID, "lunas");

            echo json_encode(['status' => 'success']);
        } catch (Exception $e) {
            error_log("Transaksi - Exception: " . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * Hapus semua data transaksi dan detailnya (hanya admin)
     */
    public function truncate()
    {
        if ($_SESSION['role'] !== 'admin') {
            Flasher::setFlash('Akses ditolak', 'gagal', 'danger');
            header('Location: ' . BASEURL . '/transaksi');
            exit;
        }

        try {
            $success = $this->transaksiModel->truncateTransaksi();
            if ($success) {
                Flasher::setFlash('Semua data transaksi berhasil dihapus dan AUTO_INCREMENT di-reset.', 'sukses', 'success');
            } else {
                Flasher::setFlash('Gagal mereset data transaksi. Coba lagi.', 'gagal', 'danger');
            }
        } catch (Exception $e) {
            Flasher::setFlash('Error: ' . $e->getMessage(), 'gagal', 'danger');
        }

        header('Location: ' . BASEURL . '/transaksi');
        exit;
    }
}
