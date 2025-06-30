<?php


class Transaksi_model extends BaseModel
{

    protected string $defaultTable = 'transaksi';


    protected array $defaultSearchFields = ['s.nama', 'u.nama'];
    protected array $defaultExactFields = ['s.id'];


    public function getTransaksiByUser($role, $username)
    {
        $query = "
            SELECT t.*, u.nama AS kasir, s.nama AS siswa, s.jurusan
            FROM transaksi t
            JOIN users u ON t.kasir_id = u.id
            JOIN siswa s ON t.siswa_id = s.id
        ";

        $params = [];

        if ($role !== 'admin' && $role !== 'gudang') {
            $query .= " WHERE u.username = :kasir";
            $params = ['kasir' => $username];
        }

        $query .= " ORDER BY t.tanggal_transaksi DESC";

        $result = $this->fetchAll($query, $params);

        if (empty($result)) {
            Logger::logQuery($query, $params);
        }

        return $result;
    }


    public function getFilteredTransaksi(array $filters = [], $role = 'admin', $username = null): array
    {
        $searchFields = ['t.kode_transaksi', 's.nama', 'u.nama'];
        $exactFields = ['t.status', 't.metode_pembayaran'];

        $query = "
        SELECT t.*, u.nama AS kasir, s.nama AS siswa, s.jurusan
        FROM transaksi t
        JOIN users u ON t.kasir_id = u.id
        JOIN siswa s ON t.siswa_id = s.id
        WHERE 1=1
    ";

        $params = [];


        // Role filter
        if ($role !== 'admin' && $role !== 'gudang') {

            $query .= " AND u.id = :kasir";
            $params[':kasir'] = $username;
        }

        // LIKE filter (keyword)
        if (!empty($filters['keyword'])) {
            $like = [];
            foreach ($searchFields as $i => $field) {
                $key = ":keyword{$i}";
                $like[] = "$field LIKE $key";
                $params[$key] = "%" . $filters['keyword'] . "%";
            }
            $query .= " AND (" . implode(' OR ', $like) . ")";
        }

        // Exact match
        foreach ($exactFields as $field) {
            if (!empty($filters[$field])) {
                $query .= " AND t.$field = :$field";
                $params[":$field"] = $filters[$field];
            }
        }

        // Sorting
        $sortField = 't.tanggal_transaksi';
        $sortDirection = 'DESC';
        if (!empty($filters['sort_by'])) {
            $sortParts = explode('|', $filters['sort_by']);
            if (count($sortParts) === 2) {
                $field = $sortParts[0];
                $direction = strtoupper($sortParts[1]);
                if (in_array($field, ['t.tanggal_transaksi', 't.id', 's.nama', 'u.nama']) && in_array($direction, ['ASC', 'DESC'])) {
                    $sortField = $field;
                    $sortDirection = $direction;
                }
            }
        }

        $query .= " ORDER BY $sortField $sortDirection";

        // Pagination
        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 10;
        $offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;
        $query .= " LIMIT $limit OFFSET $offset";

        return $this->fetchAll($query, $params);
    }


    protected function getDefaultSortField(): string
    {
        return 't.tanggal_transaksi';
    }

    protected function getAllowedSortFields(): array
    {
        return ['t.tanggal_transaksi', 't.id', 't.kode_transaksi', 'u.nama', 's.nama'];
    }




    public function countFilteredTransaksi(array $filters = [], $role = 'admin', $username = null): int
    {
        $searchFields = ['t.kode_transaksi', 's.nama', 'u.nama'];
        $exactFields = ['t.status', 't.metode_pembayaran'];

        $query = "
        SELECT COUNT(*) AS total
        FROM transaksi t
        JOIN users u ON t.kasir_id = u.id
        JOIN siswa s ON t.siswa_id = s.id
        WHERE 1=1
    ";

        $params = [];

        if ($role !== 'admin' && $role !== 'gudang') {
            $query .= " AND u.id = :kasir";
            $params[':kasir'] = $username;
        }

        // LIKE filter (keyword)
        if (!empty($filters['keyword'])) {
            $like = [];
            foreach ($searchFields as $i => $field) {
                $key = ":keyword{$i}";
                $like[] = "$field LIKE $key";
                $params[$key] = "%" . $filters['keyword'] . "%";
            }
            $query .= " AND (" . implode(' OR ', $like) . ")";
        }

        // Exact match
        foreach ($exactFields as $field) {
            if (!empty($filters[$field])) {
                $query .= " AND t.$field = :$field";
                $params[":$field"] = $filters[$field];
            }
        }

        $result = $this->fetchOne($query, $params);
        return (int)($result['total'] ?? 0);
    }



    public function getAllTransaksi()
    {
        $this->db->query("
            SELECT t.*, u.nama AS kasir, s.nama AS siswa
            FROM transaksi t
            JOIN users u ON t.kasir_id = u.id
            JOIN siswa s ON t.siswa_id = s.id
            ORDER BY t.tanggal_transaksi DESC
        ");
        return $this->db->resultSet();
    }





    public function tambahTransaksi($data, $items)
    {
        try {
            $this->db->beginTransaction();

            $this->db->query("
                INSERT INTO transaksi 
                (kode_transaksi, kasir_id, siswa_id, metode_pembayaran, total_harga, bukti_pembayaran, status) 
                VALUES (:kode_transaksi, :kasir_id, :siswa_id, :metode_pembayaran, :total_harga, :bukti_pembayaran, :status)
            ");

            $this->db->bind(":kode_transaksi", $data["kode_transaksi"]);
            $this->db->bind(":kasir_id", $data["kasir_id"]);
            $this->db->bind(":siswa_id", $data["siswa_id"]);
            $this->db->bind(":metode_pembayaran", $data["metode_pembayaran"]);
            $this->db->bind(":total_harga", $data["total_harga"]);
            $this->db->bind(":bukti_pembayaran", $data["bukti_pembayaran"] ?? NULL);
            $this->db->bind(":status", $data["status"]);
            $this->db->execute();

            $transaksiId = $this->db->lastInserted();

            foreach ($items as $item) {
                $this->db->query("SELECT harga, harga_berhijab, harga_tambahan FROM seragam WHERE id = :seragam_id");
                $this->db->bind(":seragam_id", $item["seragam_id"]);
                $seragam = $this->db->single();

                if (!$seragam) throw new Exception("Seragam ID {$item['seragam_id']} tidak ditemukan.");

                $hijab = ($item["status"] === "berjilbab") ? 1 : 0;

                $this->db->query("
                    INSERT INTO transaksi_detail 
                    (transaksi_id, seragam_id, ukuran, berhijab, harga) 
                    VALUES (:transaksi_id, :seragam_id, :ukuran, :berhijab, :harga)
                ");
                $this->db->bind(":transaksi_id", $transaksiId);
                $this->db->bind(":seragam_id", $item["seragam_id"]);
                $this->db->bind(":ukuran", $item["ukuran"]);
                $this->db->bind(":berhijab", $hijab);
                $this->db->bind(":harga", $item["harga"]);
                $this->db->execute();
            }

            $this->db->commit();
            return $transaksiId;
        } catch (Exception $e) {
            $this->db->rollback();
            return ["error" => $e->getMessage()];
        }
    }

    public function getTransaksiById($id)
    {
        $this->db->query("
            SELECT t.*, s.nama AS nama_siswa, s.no_pendaftaran, s.nis, s.no_hp, s.jurusan, u.username AS kasir 
            FROM transaksi t
            JOIN siswa s ON t.siswa_id = s.id
            JOIN users u ON t.kasir_id = u.id
            WHERE t.id = :id
        ");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getDetailItemByTransaksi($id)
    {
        $this->db->query("
            SELECT ts.*, s.nama AS nama_seragam 
            FROM transaksi_detail ts
            JOIN seragam s ON ts.seragam_id = s.id
            WHERE ts.transaksi_id = :id
        ");
        $this->db->bind('id', $id);
        return $this->db->resultSet();
    }

    public function getDetailItemByTransaksiforAction($id)
    {
        $this->db->query("
            SELECT 
                ts.*, 
                s.nama AS nama_seragam, 
                tr.id AS transaksi_id,
                si.nama AS nama_siswa, 
                si.jurusan 
            FROM transaksi_detail ts
            JOIN seragam s ON ts.seragam_id = s.id
            JOIN transaksi tr ON ts.transaksi_id = tr.id
            JOIN siswa si ON tr.siswa_id = si.id
            WHERE ts.transaksi_id = :id
        ");
        $this->db->bind('id', $id);
        return $this->db->resultSet();
    }

    public function updateTransaksi($id, $kode_transaksi, $siswa_id, $metode_pembayaran, $total_harga)
    {
        $this->db->query("
            UPDATE transaksi 
            SET kode_transaksi = :kode_transaksi, 
                siswa_id = :siswa_id, 
                metode_pembayaran = :metode_pembayaran, 
                total_harga = :total_harga 
            WHERE id = :id
        ");
        $this->db->bind(':kode_transaksi', $kode_transaksi);
        $this->db->bind(':siswa_id', $siswa_id);
        $this->db->bind(':metode_pembayaran', $metode_pembayaran);
        $this->db->bind(':total_harga', $total_harga);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function batalTransaksi($id)
    {
        $this->db->query("UPDATE transaksi SET status = 'batal' WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateStatus($id, $status)
    {
        $this->db->query("UPDATE transaksi SET status = :status WHERE id = :id");
        $this->db->bind('status', $status);
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function setStatusAmbil(array $ids, string $status)
    {
        if (empty($ids)) return true; // Kosong = tidak perlu update

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $this->db->query("UPDATE transaksi_detail SET status_ambil = ? WHERE id IN ($placeholders)");
        return $this->db->execute(array_merge([$status], $ids));
    }
}
