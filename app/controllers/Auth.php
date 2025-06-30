<?php

class Auth extends Controller
{
    private object $userModel; // Type hinting untuk userModel

    public function __construct()
    {
        // Mendeklarasikan tipe objek yang digunakan dalam model
        $this->userModel = $this->model('User_model');
    }

    // Metode index
    public function index(): void
    {
        AuthMiddleware::checkGuest();
        $data['judul'] = 'LOGIN';
        $this->view('auth/login', $data);
    }

    // Metode login dengan tipe parameter yang jelas
    public function login(): void
    {
        AuthMiddleware::checkGuest();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Menggunakan CSRF token untuk validasi
            AuthMiddleware::verifyCSRF($_POST['csrf_token']);

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            if (empty($username) || empty($password)) {
                Flasher::setFlash('Username dan Password tidak boleh kosong.', 'gagal', 'danger');
                header("Location: " . BASEURL . "/auth");
                exit;
            }

            // Menambahkan tipe kembalian dari fungsi model
            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['role'] = $user['role'];

                $this->userModel->updateLoginSuccess($user['id']);
                Logger::logActivity('Berhasil login', $user['id']);

                header("Location: " . BASEURL . "/dashboard");
                exit;
            } else {
                Logger::logActivity("Gagal login dengan username: {$username}", 0);
                Flasher::setFlash('Username atau Password salah.', 'gagal', 'danger');
                header("Location: " . BASEURL . "/auth");
                exit;
            }
        } else {
            header("Location: " . BASEURL . "/auth");
            exit;
        }
    }

    // Metode logout
    public function logout(): void
    {
        Logger::logActivity('Logout', $_SESSION['user_id']);
        session_unset();
        session_destroy();
        header("Location: " . BASEURL . "/auth");
        exit;
    }
}
