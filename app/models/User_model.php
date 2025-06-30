<?php

class User_model extends BaseModel
{
    private string $table = 'users';

    /**
     * Cari user berdasarkan username
     */
    public function findByUsername(string $username): array|false
    {
        $query = "SELECT * FROM {$this->table} WHERE LOWER(username) = LOWER(:username) LIMIT 1";
        return $this->fetchOne($query, ['username' => $username]);
    }



    /**
     * Ambil user by ID
     */
    public function getUserById(int $id): array|false
    {
        return $this->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    /**
     * Update status login berhasil
     */
    public function updateLoginSuccess(int $id): bool
    {
        $params = [
            'last_login' => date('Y-m-d H:i:s'),
            'last_ip' => $_SERVER['REMOTE_ADDR'],
            'id' => $id
        ];

        $query = "
            UPDATE {$this->table} 
            SET last_login = :last_login, 
                last_ip = :last_ip, 
                login_attempts = 0, 
                locked_until = NULL 
            WHERE id = :id
        ";

        return $this->run($query, $params);
    }

    /**
     * Tambah counter login gagal & lock user jika melebihi batas
     */
    public function incrementLoginAttempts(int $id): void
    {
        // Increment login_attempts
        $this->run(
            "UPDATE {$this->table} SET login_attempts = login_attempts + 1 WHERE id = :id",
            ['id' => $id]
        );

        // Cek apakah perlu lock akun
        $user = $this->getUserById($id);
        if ($user && $user['login_attempts'] >= 5) {
            $lockedUntil = date('Y-m-d H:i:s', strtotime('+15 minutes'));
            $this->run(
                "UPDATE {$this->table} SET locked_until = :locked WHERE id = :id",
                ['locked' => $lockedUntil, 'id' => $id]
            );
        }
    }

    // CRUD Methods

    /**
     * Create a new user
     */
    public function createUser(array $data): int|false
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->autoInsert($this->table, $data);
    }

    /**
     * Update user data by ID
     */
    public function updateUser(int $id, array $data): bool
    {
        // Jika ada password dan tidak kosong, hash passwordnya
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } elseif (isset($data['password'])) {
            // Jika password kosong, hapus dari array supaya tidak update jadi kosong
            unset($data['password']);
        }

        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setClause = implode(", ", $set);
        $query = "UPDATE {$this->table} SET {$setClause} WHERE id = :id";
        $data['id'] = $id;

        return $this->run($query, $data);
    }


    /**
     * Delete a user by ID
     */
    public function deleteUser(int $id): bool
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->run($query, ['id' => $id]);
    }

    /**
     * Get all users
     */
    public function getAllUsers(): array
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->fetchAll($query);
    }

    public function getAllKasir(): array
    {
        $query = "SELECT id, username FROM {$this->defaultTable}
                  WHERE role = 'kasir'
                  ORDER BY username ASC";
        return $this->fetchAll($query);
    }
}
