<?php

class Seragam_model extends BaseModel
{
    private $table = 'seragam';

    // Ambil semua seragam
    public function getAll(): array
    {
        $query = "SELECT * FROM {$this->table} ORDER BY sort_order ASC";
        return $this->fetchAll($query);
    }

    // Ambil data seragam berdasarkan ID
    public function getById(int $id): array|false
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        return $this->fetchOne($query, ['id' => $id]);
    }

    // Tambah seragam
    public function create(array $data): bool
    {
        $query = "INSERT INTO {$this->table} 
                  (nama, harga, harga_tambahan, berhijab, harga_berhijab) 
                  VALUES (:nama, :harga, :harga_tambahan, :berhijab, :harga_berhijab)";

        return $this->run($query, [
            'nama'           => $data['nama'],
            'harga'          => $data['harga'],
            'harga_tambahan' => $data['harga_tambahan'],
            'berhijab'       => $data['berhijab'],
            'harga_berhijab' => $data['harga_berhijab']
        ]);
    }

    // Edit data seragam
    public function update(array $data): bool
    {
        $query = "UPDATE {$this->table} 
                  SET nama = :nama, 
                      harga = :harga, 
                      harga_tambahan = :harga_tambahan, 
                      berhijab = :berhijab, 
                      harga_berhijab = :harga_berhijab 
                  WHERE id = :id";

        return $this->run($query, [
            'id'             => $data['id'],
            'nama'           => $data['nama'],
            'harga'          => $data['harga'],
            'harga_tambahan' => $data['harga_tambahan'],
            'berhijab'       => $data['berhijab'],
            'harga_berhijab' => $data['harga_berhijab']
        ]);
    }

    /**
     * Update hanya kolom sort_order untuk satu record.
     *
     * @param int $id
     * @param int $sortOrder
     * @return bool
     */
    public function updateSortOrder(int $id, int $sortOrder): bool
    {
        $query = "UPDATE {$this->table}
              SET sort_order = :sort_order
              WHERE id = :id";
        return $this->run($query, [
            'id'         => $id,
            'sort_order' => $sortOrder
        ]);
    }


    // Hapus seragam
    public function delete(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->run($query, ['id' => $id]);
    }
}
