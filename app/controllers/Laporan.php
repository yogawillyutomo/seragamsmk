<?php
class Laporan extends Controller
{
    private Laporan_model $laporanModel;
    private User_model    $userModel;

    public function __construct()
    {
        AuthMiddleware::checkLogin();
        $this->laporanModel = $this->model('Laporan_model');
        $this->userModel    = $this->model('User_model');       // panggil User_model
    }

    public function index()
    {
        AuthMiddleware::checkLogin();

        $role    = $_SESSION['role'];
        $userId  = $_SESSION['user_id'];
        $data['judul'] = 'Laporan';

        // baca page/limit
        $limit       = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $currentPage = isset($_GET['page'])  ? max(1, (int)$_GET['page']) : 1;
        $offset      = ($currentPage - 1) * $limit;

        // baca dan paksa filter jadi array
        $rawKasir   = $_GET['kasir']   ?? [];
        $rawJur     = $_GET['jurusan'] ?? [];
        $rawStatus  = $_GET['status']  ?? [];

        $filters = [
            'dari'    => $_GET['dari']     ?? '',
            'sampai'  => $_GET['sampai']   ?? '',
            'kasir'   => is_array($rawKasir)  ? $rawKasir  : (empty($rawKasir) ? [] : [$rawKasir]),
            'jurusan' => is_array($rawJur)    ? $rawJur    : (empty($rawJur)   ? [] : [$rawJur]),
            'status'  => is_array($rawStatus) ? $rawStatus : (empty($rawStatus) ? [] : [$rawStatus]),
            'keyword' => $_GET['keyword']   ?? '',
            'limit'   => $limit,
            'offset'  => $offset,
        ];

        // dropdown
        // $laporanModel = $this->model('Laporan_model');
        $data['list_kasir']   = $this->laporanModel->getAllKasir();
        $data['list_jurusan'] =  $this->laporanModel->getAllJurusan();

        // ambil data dan hitung total
        $data['laporan']     =  $this->laporanModel->getLaporanTransaksi($filters, $role, $userId);
        $data['summary']     =  $this->laporanModel->getSummaryTransaksi($filters, $role, $userId);
        $totalRows           =  $this->laporanModel->countLaporanTransaksi($filters, $role, $userId);
        $data['totalPages']  = (int)ceil($totalRows / $limit);
        $data['currentPage'] = $currentPage;
        $data['filters']     = $filters;
        $data['role']        = $role;

        // var_dump( $data['laporan']);

        $this->view('laporan/index', $data);
    }

    public function getAllLaporan()
    {
        AuthMiddleware::checkLogin();
        $role   = $_SESSION['role'];
        $me     = $_SESSION['user_id'];

        // 1) Baca filter sama dengan di index()
        $rawKasir  = $_GET['kasir']   ?? [];
        $rawJur    = $_GET['jurusan'] ?? [];
        $rawStatus = $_GET['status']  ?? [];
        $filters = [
            'dari'    => $_GET['dari']     ?? '',
            'sampai'  => $_GET['sampai']   ?? '',
            'kasir'   => is_array($rawKasir)  ? $rawKasir  : (empty($rawKasir)  ? [] : [$rawKasir]),
            'jurusan' => is_array($rawJur)  ? $rawJur    : (empty($rawJur)    ? [] : [$rawJur]),
            'status'  => is_array($rawStatus)  ? $rawStatus : (empty($rawStatus) ? [] : [$rawStatus]),
            'keyword' => $_GET['keyword']   ?? '',
            // dummy: nanti di‐override
            'limit'   => 0,
            'offset'  => 0,
        ];

        // 2) Hitung total baris sesuai filter
        $total = $this->laporanModel
            ->countLaporanTransaksi($filters, $role, $me);

        // 3) Ambil semua baris
        $filters['limit']  = $total;
        $filters['offset'] = 0;
        $data = $this->laporanModel
            ->getLaporanTransaksi($filters, $role, $me);

        // 4) Kembalikan JSON
        header('Content-Type: application/json');
        echo json_encode([
            'data'  => $data,
            'total' => $total
        ]);
        exit;
    }



    public function reportBySiswa()
    {

        AuthMiddleware::checkLogin();
        AuthMiddleware::checkRole(['admin', 'gudang']);

        // Ambil filter & pagination dari querystring
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $limit   = 15;
        $offset  = ($page - 1) * $limit;

        $filters = [
            'keyword' => trim($_GET['keyword']  ?? ''),
            'jurusan' => trim($_GET['jurusan']  ?? ''),
            'dari'    => $_GET['dari'] ?? '',
            'sampai'  => $_GET['sampai'] ?? '',
            'limit'   => $limit,
            'offset'  => $offset
        ];

        // Data untuk dropdown jurusan
        $rawJ = $this->laporanModel->getAllJurusan();
        $jurusans = array_column($rawJ, 'jurusan');

        // Hitung total & ambil page data
        $total    = $this->laporanModel->countReportBySiswa($filters);
        $reports  = $this->laporanModel->getReportBySiswa($filters);
        $totalPages = ceil($total / $limit);

        $data = [
            'judul'      => 'Laporan per Siswa',
            'reports'    => $reports,
            'jurusans'   => $jurusans,
            'filters'    => $filters,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];

        // echo '<pre>';
        // var_dump($filters, 'total=' . $total, $reports);
        // echo '</pre>';
        // exit;
        $this->view('laporan/report_by_siswa', $data);
    }


    public function detailSiswa(int $siswaId)
    {
        AuthMiddleware::checkLogin();
        AuthMiddleware::checkRole(['admin', 'gudang']);

        // Ambil detail dari model
        $detail = $this->laporanModel->getDetailBySiswa($siswaId);

        // Kirim header JSON
        header('Content-Type: application/json; charset=utf-8');

        // Output JSON dan hentikan eksekusi lebih lanjut
        echo json_encode($detail);
        exit;
    }


    public function reportBySeragam()
    {
        AuthMiddleware::checkRole(['admin', 'gudang']);
        $data = [
            'judul'  => 'Laporan per Seragam',
            'reports' => $this->laporanModel->getReportBySeragam()
        ];
        $this->view('laporan/report_by_seragam', $data);
    }
}
