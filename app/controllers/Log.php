<?php
class Log extends Controller
{
    public function index()
    {
        AuthMiddleware::checkLogin(); // Boleh diatur: hanya admin.

        $logPath = __DIR__ . '/../logs/activity.log';
        $data['logs'] = [];

        if (file_exists($logPath)) {
            $data['logs'] = array_reverse(file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
        }

        $data['judul'] = "Activity Log";
        $this->view('templates/header', $data);
        $this->view('log/index', $data);
        $this->view('templates/footer');
    }
}
