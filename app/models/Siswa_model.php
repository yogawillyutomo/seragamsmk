<?php

class Siswa_model extends BaseModel
{
    // protected string $table = 'siswa';
    protected string $defaultTable = 'siswa';
    protected array $defaultSearchFields = ['nama', 'nis', 'jurusan'];
    // protected array $defaultExactFields = ['jenis_kelamin', 'jurusan'];
    protected array $defaultExactFields = ['jurusan', 'kelas'];

    protected string $defaultSortField = 'id';
    protected string $defaultSortDirection = 'DESC';
    protected array $allowedSortFields = ['id', 'nama', 'nis'];

 

    // Menambahkan siswa baru
    public function create(array $data): int|false
    {
        return $this->autoInsert($this->defaultTable, [
            'no_pendaftaran' => $data['no_pendaftaran'],
            'nis' => $data['nis'],
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'jurusan' => $data['jurusan'],
            'wali' => $data['wali'],
            'no_hp' => $data['no_hp']
        ]);
    }




    public function getTotal(array $filters = []): int
    {
        return $this->countAll($filters);
    }

    // Mengambil semua data siswa
    public function getAll(array $filters = []): array
    {
        return $this->filterData($filters);
    }

    // Mencari siswa berdasarkan nama (auto-complete / search)
    public function search(string $keyword): array
    {
        $query = "SELECT * FROM {$this->defaultTable} WHERE nama LIKE :keyword LIMIT 10";
        return $this->fetchAll($query, [':keyword' => "%$keyword%"]);
    }

    // Mengambil data siswa berdasarkan ID
    public function getById(int $id): mixed
    {
        $query = "SELECT * FROM {$this->defaultTable} WHERE id = :id";
        return $this->fetchOne($query, [':id' => $id]);
    }

    // Memperbarui data siswa
    public function update(array $data): bool
    {
        return $this->autoUpdate($this->defaultTable, 'id = :id', [
            'no_pendaftaran' => $data['no_pendaftaran'],
            'nis' => $data['nis'],
            'nama' => $data['nama'],
            'jenis_kelamin' => $data['jenis_kelamin'],
            'jurusan' => $data['jurusan'],
            'wali' => $data['wali'],
            'no_hp' => $data['no_hp'],
            'id' => $data['id']
        ]);
    }

    // Menghapus data siswa berdasarkan ID
    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->defaultTable} WHERE id = :id";
        return $this->run($query, [':id' => $id]);
    }

    public function deleteSelected($ids)
    {
        return $this->bulkDelete($this->defaultTable, 'id', $ids);
    }

    // Mengambil semua data jurusan
    public function getAllJurusan(): array
    {
        $query = "SELECT * FROM jurusan ORDER BY nama_jurusan ASC";
        return $this->fetchAll($query);
    }

    // ====================
    // 🔥 PAGINATION METHOD
    // ====================
    public function getPaged(int $limit = 10, int $page = 1): array
    {
        $offset = ($page - 1) * $limit;

        // Ambil data siswa sesuai halaman
        $query = "SELECT * FROM {$this->defaultTable} ORDER BY nama ASC LIMIT :limit OFFSET :offset";
        $data = $this->fetchAll($query, [
            ':limit'  => $limit,
            ':offset' => $offset
        ]);

        // Hitung total data
        $countQuery = "SELECT COUNT(*) as total FROM {$this->defaultTable}";
        $countResult = $this->fetchOne($countQuery);
        $total = $countResult['total'] ?? 0;

        return [
            'data'      => $data,
            'total'     => $total,
            'limit'     => $limit,
            'page'      => $page,
            'lastPage'  => ceil($total / $limit)
        ];
    }
}
