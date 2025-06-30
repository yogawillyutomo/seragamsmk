<?php
class Laporan_model extends BaseModel
{
    protected string $defaultTable          = 'transaksi t';
    protected array  $defaultSearchFields   = ['t.kode_transaksi', 's.nama', 'u.username'];
    protected array  $defaultExactFields    = ['t.status'];
    protected string $defaultSortField      = 't.tanggal_transaksi';
    protected string $defaultSortDirection  = 'DESC';
    protected array  $allowedSortFields     = ['t.tanggal_transaksi', 't.id', 's.nama', 'u.username'];

    /**
     * Mengambil laporan transaksi dengan paging
     */
    public function getAllKasir(): array
    {
        // Ambil semua user yang bisa berperan sebagai kasir: role 'kasir' atau 'admin'
        return $this->fetchAll(
            "SELECT id, username, nama FROM users WHERE role IN ('admin','kasir') ORDER BY username"
        );
    }

    public function getAllJurusan(): array
    {
        // Ambil daftar jurusan unik dari tabel siswa
        return $this->fetchAll(
            "SELECT DISTINCT jurusan FROM siswa ORDER BY jurusan"
        );
    }

    public function getLaporanTransaksi(array $f, string $role, int $me): array
    {
        $sql = "
            SELECT
              t.*, 
              u.username AS kasir, 
              u.nama AS namakasir, 
              s.nama     AS siswa,
              s.jurusan
            FROM transaksi t
            JOIN users   u ON t.kasir_id = u.id
            JOIN siswa   s ON t.siswa_id = s.id
            WHERE 1=1
        ";
        $params = [];

        // 1) jika bukan admin, batasi hanya transaksi milik dirinya
        if ($role !== 'admin') {
            $sql .= " AND t.kasir_id = ?";
            $params[] = $me;
        }

        // 2) filter multi-kasir
        if (!empty($f['kasir'])) {
            $in  = implode(',', array_fill(0, count($f['kasir']), '?'));
            $sql .= " AND t.kasir_id IN ($in)";
            $params = array_merge($params, $f['kasir']);
        }

        // 3) filter multi-jurusan
        if (!empty($f['jurusan'])) {
            $in  = implode(',', array_fill(0, count($f['jurusan']), '?'));
            $sql .= " AND s.jurusan IN ($in)";
            $params = array_merge($params, $f['jurusan']);
        }

        // 4) filter multi-status
        if (!empty($f['status'])) {
            $in  = implode(',', array_fill(0, count($f['status']), '?'));
            $sql .= " AND t.status IN ($in)";
            $params = array_merge($params, $f['status']);
        }

        // 5) rentang tanggal
        if (!empty($f['dari'])) {
            $sql    .= " AND t.tanggal_transaksi >= ?";
            $params[] = $f['dari'] . ' 00:00:00';
        }
        if (!empty($f['sampai'])) {
            $sql    .= " AND t.tanggal_transaksi <= ?";
            $params[] = $f['sampai'] . ' 23:59:59';
        }

        // 6) keyword pencarian
        if (!empty($f['keyword'])) {
            $kw = "%{$f['keyword']}%";
            $sql .= " AND ( t.kode_transaksi LIKE ? OR s.nama LIKE ? OR u.username LIKE ? )";
            $params = array_merge($params, [$kw, $kw, $kw]);
        }

        // 7) sorting
        $sortField     = in_array($f['sort_by'] ?? '', $this->getAllowedSortFields())
            ? $f['sort_by']
            : 't.tanggal_transaksi';
        $sortDirection = strtoupper($f['sort_dir'] ?? '') === 'ASC' ? 'ASC' : 'DESC';
        $sql .= " ORDER BY $sortField $sortDirection";

        // 8) pagination
        $limit  = (int)($f['limit']  ?? 15);
        $offset = (int)($f['offset'] ?? 0);
        $sql   .= " LIMIT $limit OFFSET $offset";

        return $this->fetchAll($sql, $params);
    }

    public function countLaporanTransaksi(array $f, string $role, int $me): int
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM transaksi t
            JOIN users u ON t.kasir_id = u.id
            JOIN siswa s ON t.siswa_id = s.id
            WHERE 1=1
        ";
        $params = [];

        if ($role !== 'admin') {
            $sql    .= " AND t.kasir_id = ?";
            $params[] = $me;
        }
        if (!empty($f['kasir'])) {
            $in  = implode(',', array_fill(0, count($f['kasir']), '?'));
            $sql .= " AND t.kasir_id IN ($in)";
            $params = array_merge($params, $f['kasir']);
        }
        if (!empty($f['jurusan'])) {
            $in  = implode(',', array_fill(0, count($f['jurusan']), '?'));
            $sql .= " AND s.jurusan IN ($in)";
            $params = array_merge($params, $f['jurusan']);
        }
        if (!empty($f['status'])) {
            $in  = implode(',', array_fill(0, count($f['status']), '?'));
            $sql .= " AND t.status IN ($in)";
            $params = array_merge($params, $f['status']);
        }
        if (!empty($f['dari'])) {
            $sql    .= " AND t.tanggal_transaksi >= ?";
            $params[] = $f['dari'] . ' 00:00:00';
        }
        if (!empty($f['sampai'])) {
            $sql    .= " AND t.tanggal_transaksi <= ?";
            $params[] = $f['sampai'] . ' 23:59:59';
        }
        if (!empty($f['keyword'])) {
            $kw = "%{$f['keyword']}%";
            $sql .= " AND ( t.kode_transaksi LIKE ? OR s.nama LIKE ? OR u.username LIKE ? )";
            $params = array_merge($params, [$kw, $kw, $kw]);
        }

        $row = $this->fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    public function getSummaryTransaksi(array $filters, string $role, int $userId): array
    {
        $sql = "
        SELECT
            SUM(CASE WHEN t.status = 'lunas'   THEN t.total_harga ELSE 0 END) AS total_lunas,
            SUM(CASE WHEN t.status = 'diambil' THEN t.total_harga ELSE 0 END) AS total_diambil,
            SUM(CASE WHEN t.status = 'batal'   THEN t.total_harga ELSE 0 END) AS total_batal
        FROM transaksi t
        JOIN users u   ON t.kasir_id = u.id
        JOIN siswa s   ON t.siswa_id = s.id
        WHERE 1=1
    ";
        $params = [];

        // filter kasir (bila bukan admin)
        if ($role !== 'admin') {
            $sql      .= " AND t.kasir_id = :me";
            $params['me'] = $userId;
        }
        // filter multi‐kasir
        if (!empty($filters['kasir'])) {
            $placeholders = implode(',', array_fill(0, count($filters['kasir']), '?'));
            $sql          .= " AND t.kasir_id IN ($placeholders)";
            $params       = array_merge($params, $filters['kasir']);
        }
        // filter jurusan
        if (!empty($filters['jurusan'])) {
            $ph = implode(',', array_fill(0, count($filters['jurusan']), '?'));
            $sql   .= " AND s.jurusan IN ($ph)";
            $params = array_merge($params, $filters['jurusan']);
        }
        // filter status
        if (!empty($filters['status'])) {
            $ph = implode(',', array_fill(0, count($filters['status']), '?'));
            $sql   .= " AND t.status IN ($ph)";
            $params = array_merge($params, $filters['status']);
        }
        // rentang tanggal
        if (!empty($filters['dari'])) {
            $sql      .= " AND t.tanggal_transaksi >= ?";
            $params[]  = $filters['dari'] . ' 00:00:00';
        }
        if (!empty($filters['sampai'])) {
            $sql      .= " AND t.tanggal_transaksi <= ?";
            $params[]  = $filters['sampai'] . ' 23:59:59';
        }
        // keyword search
        if (!empty($filters['keyword'])) {
            $kw = '%' . $filters['keyword'] . '%';
            $sql .= " AND (
            t.kode_transaksi LIKE ? OR
            s.nama            LIKE ? OR
            u.username        LIKE ?
        )";
            $params = array_merge($params, [$kw, $kw, $kw]);
        }

        $row = $this->fetchOne($sql, $params);
        return [
            'total_lunas'   => (int)($row['total_lunas']   ?? 0),
            'total_diambil' => (int)($row['total_diambil'] ?? 0),
            'total_batal'   => (int)($row['total_batal']   ?? 0),
            'total_pendapatan' => (int)(($row['total_lunas']   ?? 0) + ($row['total_diambil'] ?? 0)),
        ];
    }






    /** Laporan untuk gudang */
    public function getLaporanGudang(array $filters = []): array
    {
        // jika perlu filter jurusan / tanggal, tambahkan di sini
        return $this->fetchAll("
          SELECT s.nama_seragam, SUM(d.qty) AS total_terambil
          FROM transaksi_detail d
          JOIN seragam s ON d.seragam_id = s.id
          JOIN transaksi t ON d.transaksi_id = t.id
          WHERE t.status = 'diambil'
          GROUP BY s.nama_seragam
        ");
    }



    /**
     * Hitung total baris distinct siswa berdasarkan filter
     */
    public function countReportBySiswa(array $f): int
    {
        $sql = "
          SELECT COUNT(DISTINCT s.id) AS total
          FROM siswa s
          JOIN transaksi t ON t.siswa_id = s.id AND t.status <> 'batal'
          JOIN transaksi_detail td ON td.transaksi_id = t.id
          WHERE 1=1
        ";
        $params = [];

        if (!empty($f['keyword'])) {
            $sql .= " AND s.nama LIKE :keyword";
            $params['keyword'] = '%' . $f['keyword'] . '%';
        }
        if (!empty($f['jurusan'])) {
            $sql .= " AND s.jurusan = :jurusan";
            $params['jurusan'] = $f['jurusan'];
        }
        if (!empty($f['dari']) && !empty($f['sampai'])) {
            $sql .= " AND t.tanggal_transaksi BETWEEN :dari AND :sampai";
            $params['dari']   = $f['dari'] . ' 00:00:00';
            $params['sampai'] = $f['sampai'] . ' 23:59:59';
        }

        $row = $this->fetchOne($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    /**
     * Laporan per siswa dengan pagination
     *
     * @param array $f   filter: keyword,jurusan,dari,sampai,limit,offset
     */
    public function getReportBySiswa(array $f): array
    {
        $sql = "
          SELECT 
            s.id            AS siswa_id,
            s.nama          AS siswa,
            s.jurusan       AS jurusan,
            COUNT(td.id)    AS total_beli,
            SUM(td.status_ambil = 'diambil') AS total_diambil
          FROM siswa s
          JOIN transaksi t ON t.siswa_id = s.id AND t.status <> 'batal'
          JOIN transaksi_detail td ON td.transaksi_id = t.id
          WHERE 1=1
        ";
        $params = [];

        if (!empty($f['keyword'])) {
            $sql .= " AND s.nama LIKE :keyword";
            $params['keyword'] = '%' . $f['keyword'] . '%';
        }
        if (!empty($f['jurusan'])) {
            $sql .= " AND s.jurusan = :jurusan";
            $params['jurusan'] = $f['jurusan'];
        }
        if (!empty($f['dari']) && !empty($f['sampai'])) {
            $sql .= " AND t.tanggal_transaksi BETWEEN :dari AND :sampai";
            $params['dari']   = $f['dari'] . ' 00:00:00';
            $params['sampai'] = $f['sampai'] . ' 23:59:59';
        }

        $sql .= "
          GROUP BY s.id, s.nama, s.jurusan
          ORDER BY s.nama
            LIMIT {$f['limit']} OFFSET {$f['offset']}
        ";


        return $this->fetchAll($sql, $params);
    }

    /**
     * Detail per siswa, dengan transaksi_id & kode_transaksi
     */
    public function getDetailBySiswa(int $siswaId): array
    {
        $sql = "
          SELECT 
            t.id               AS transaksi_id,
            t.kode_transaksi   AS kode_transaksi,
            td.id              AS detail_id,
            sr.nama            AS seragam,
            td.ukuran,
            td.berhijab,
            td.harga,
            td.status_ambil
          FROM transaksi_detail td
          JOIN transaksi t   ON td.transaksi_id = t.id
          JOIN seragam sr    ON td.seragam_id    = sr.id
          WHERE t.siswa_id = :sid
            AND t.status   <> 'batal'
          ORDER BY t.id, td.id
        ";
        return $this->fetchAll($sql, ['sid' => $siswaId]);
    }

    /**
     * Report per seragam
     */
    public function getReportBySeragam(): array
    {
        $sql = "
          SELECT 
            sr.id   AS seragam_id,
            sr.nama AS seragam,
            COUNT(td.id) AS total_beli,
            SUM(td.status_ambil = 'diambil') AS total_diambil
          FROM seragam sr
          JOIN transaksi_detail td 
            ON td.seragam_id = sr.id
          JOIN transaksi t 
            ON t.id = td.transaksi_id 
           AND t.status <> 'batal'
          GROUP BY sr.id, sr.nama
          ORDER BY sr.nama
        ";
        return $this->fetchAll($sql);
    }



    /**
     * Update sort_order di daftar seragam — memanfaatkan autoUpdate()
     */
    public function updateSortOrder(int $id, int $order): bool
    {
        return $this->autoUpdate(
            'seragam',
            'id = :id',
            ['id' => $id, 'sort_order' => $order]
        );
    }


    // di app/models/Laporan_model.php

    // Rekap per jurusan: semua jurusan muncul, total siswa, total lunas, total diambil
    public function getSummaryByJurusan(): array
    {
        $sql = "
      SELECT
        s.jurusan,
        COUNT(DISTINCT s.id) AS total_siswa,
        COUNT(DISTINCT CASE WHEN t.status != 'batal' THEN s.id END) AS total_lunas
      FROM siswa s
      LEFT JOIN transaksi t
        ON t.siswa_id = s.id
      GROUP BY s.jurusan
      ORDER BY s.jurusan
    ";
        return $this->fetchAll($sql);
    }


    // Rekap per kasir: hanya transaksi lunas+diambil
    public function getSummaryByKasir(): array
    {
        $sql = "
      SELECT
        u.nama        AS kasir,
        role,
        COUNT(t.id)   AS total_transaksi,
        SUM(t.total_harga) AS total_pendapatan
      FROM users u
      LEFT JOIN transaksi t
        ON t.kasir_id = u.id
       AND t.status != 'batal'
      GROUP BY u.id
      ORDER BY u.nama
    ";
        return $this->fetchAll($sql);
    }


    public function countTaken(): int
    {
        $sql = "
      SELECT COUNT(*) AS cnt
      FROM transaksi_detail td
      JOIN transaksi t 
        ON t.id = td.transaksi_id
      WHERE td.status_ambil = 'diambil' 
        AND t.status != 'batal'
    ";
        $row = $this->fetchOne($sql);
        return (int)($row['cnt'] ?? 0);
    }

    public function countBought(): int
    {
        $sql = "
      SELECT COUNT(*) AS cnt
      FROM transaksi_detail td
      JOIN transaksi t 
        ON t.id = td.transaksi_id
      WHERE t.status != 'batal'
    ";
        $row = $this->fetchOne($sql);
        return (int)$row['cnt'];
    }
}
