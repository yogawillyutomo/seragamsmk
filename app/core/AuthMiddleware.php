<?php

class AuthMiddleware
{
    /**
     * Cek apakah user sudah login.
     */
    public static function checkLogin(): void
    {
        if (!isset($_SESSION['user_id'])) {
            Flasher::setFlash('Harap login terlebih dahulu!', 'gagal', 'danger');
            self::redirect('/auth');
        }
    }

    /**
     * Cek apakah user belum login (guest).
     */
    public static function checkGuest(): void
    {
        if (isset($_SESSION['user_id'])) {
            self::redirect('/dashboard');
        }
    }

    /**
     * Verifikasi token CSRF
     */
    public static function verifyCSRF(string $token): void
    {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            $userId = $_SESSION['user_id'] ?? 0;
            Logger::logActivity("Gagal validasi CSRF", $userId);
            Flasher::setFlash('Token CSRF tidak valid.', 'gagal', 'danger');
            self::redirect('/auth');
        }
    }

    /**
     * Cek role user, redirect jika tidak memiliki izin
     *
     * @param string|array $roles
     */
    public static function checkRole(string|array $roles): void
    {
        if (!isset($_SESSION['role'])) {
            Flasher::setFlash('Tidak ada akses. Silakan login kembali.', 'gagal', 'danger');
            self::redirect('/auth');
        }

        $userRole = $_SESSION['role'];
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        if (!in_array($userRole, $allowedRoles, true)) {
            Flasher::setFlash('Anda tidak memiliki izin untuk mengakses halaman ini.', 'gagal', 'warning');
            self::redirect('/dashboard');
        }
    }

    /**
     * Helper untuk redirect dengan BASEURL
     */
    private static function redirect(string $path): void
    {
        header("Location: " . BASEURL . $path);
        exit;
    }
}
