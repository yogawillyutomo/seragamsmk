<?php

class Dashboard extends Controller
{

    private Laporan_model $laporanModel;


    public function __construct()
    {
        AuthMiddleware::checkLogin();
        $this->laporanModel = $this->model('Laporan_model');
    }
    public function index()
    {
        $judul = 'Dashboard';
        AuthMiddleware::checkLogin();

        // 1) Rekap per jurusan
        $jurusanData = $this->laporanModel->getSummaryByJurusan();

        // siapkan default
        $kasirData = [];
        $taken     = 0;
        $notTaken  = 0;

        $role   = $_SESSION['role'];
        $userId = $_SESSION['user_id'];

        // 2) Jika admin atau kasir: ambil pendapatan per kasir
        if ($role === 'admin' || $role === 'kasir') {
            // untuk admin: pass null → semua kasir; untuk kasir: pass own ID
            $kasirData = $this->laporanModel->getSummaryByKasir(
                $role === 'kasir' ? $userId : null
            );
        }

        // 3) Jika admin atau gudang: hitung rekap pengambilan
        if ($role === 'admin' || $role === 'gudang') {
            $taken   = $this->laporanModel->countTaken();   // hanya status_ambil='diambil' & status!='batal'
            $bought  = $this->laporanModel->countBought();  // hanya status!='batal'
            $notTaken = $bought - $taken;
        }

        $this->view('dashboard/index', compact(
            'judul',
            'jurusanData',
            'kasirData',
            'taken',
            'notTaken'
        ));
    }
}
