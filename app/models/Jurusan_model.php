<?php
// app/models/Jurusan_model.php

class Jurusan_model extends BaseModel
{
    protected string $defaultTable = 'jurusan';
    protected array  $defaultSearchFields = ['nama_jurusan'];
    protected array  $defaultExactFields  = [];
    protected string $defaultSortField    = 'nama_jurusan';
    protected string $defaultSortDirection = 'ASC';
    protected array  $allowedSortFields   = ['nama_jurusan'];

    /**
     * Ambil semua jurusan (tanpa filter)
     */
    public function getAll(): array
    {
        return $this->fetchAll(
            "SELECT id, nama_jurusan FROM {$this->defaultTable} ORDER BY {$this->defaultSortField} ASC"
        );
    }

    /**
     * Ambil satu jurusan berdasarkan ID
     */
    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            "SELECT * FROM {$this->defaultTable} WHERE id = :id",
            [':id' => $id]
        ) ?: null;
    }

    /**
     * Tambah jurusan
     */
    public function insertJurusan(array $data): int|false
    {
        return $this->insertAuto($this->defaultTable, $data);
    }

    /**
     * Update jurusan berdasarkan ID
     */
    public function updateJurusan(int $id, array $data): bool
    {
        return $this->updateAuto($this->defaultTable, "id = :id", array_merge(['id' => $id], $data));
    }

    /**
     * Hapus jurusan
     */
    public function deleteJurusan(int $id): bool
    {
        return $this->bulkDelete($this->defaultTable, 'id', [$id]);
    }

    /**
     * Hitung total jurusan dengan filter (untuk pagination)
     */
    public function countFiltered(array $filters = []): int
    {
        return $this->countAll($filters);
    }

    /**
     * Ambil data jurusan dengan filter + pagination
     */
    public function getFiltered(array $filters = []): array
    {
        return $this->filterAndPaginate($filters);
    }
}
