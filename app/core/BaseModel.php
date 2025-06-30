<?php

abstract class BaseModel
{
    protected Database $db;
    protected int $queryCount = 0;
    protected float $executionTime = 0;

    protected array $defaultSearchFields = [];
    protected array $defaultExactFields = [];
    protected string $defaultTable = '';

    protected string $defaultSortField = '';
    protected string $defaultSortDirection = '';
    protected array $allowedSortFields = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }




    // Ambil daftar kolom sorting yang diperbolehkan
    protected function getAllowedSortFields(): array
    {
        return $this->allowedSortFields ?? [];
    }

    // Ambil default kolom sorting
    protected function getDefaultSortField(): string
    {
        return $this->defaultSortField ?? 'id';
    }

    // Ambil default arah sorting
    protected function getDefaultSortDirection(): string
    {
        return $this->defaultSortDirection ?? 'DESC';
    }

    protected function logQuery(): void
    {
        $this->queryCount++;
    }

    public function getQueryCount(): int
    {
        return $this->queryCount;
    }

    public function getExecutionTime(): float
    {
        return $this->executionTime;
    }

    protected function insert(string $query, array $params = []): int|false
    {
        try {
            $this->logQuery();
            $start = microtime(true);

            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }
            Logger::logQuery($query, $params);

            $result = $this->db->execute() ? $this->db->lastInserted() : false;
            $this->executionTime += microtime(true) - $start;

            return $result;
        } catch (PDOException $e) {
            Logger::logError($e, 'Insert Query');
            return false;
        }
    }

    protected function autoInsert(string $table, array $data = []): int|false
    {
        $fields = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO {$table} ($fields) VALUES ($placeholders)";
        return $this->insert($query, $data);
    }

    protected function autoUpdate(string $table, string $where, array $data = []): bool
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setClause = implode(", ", $set);
        $query = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        return $this->run($query, $data);
    }

    protected function bulkDelete(string $table, string $idField, array $ids = []): bool
    {
        if (empty($ids)) {
            return false; // Tidak ada yang dihapus
        }

        // Membuat placeholder id seperti :id0, :id1, dst.
        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $placeholder = ":id{$index}";
            $placeholders[] = $placeholder;
            $params[$placeholder] = $id;
        }

        $placeholderString = implode(', ', $placeholders);
        $query = "DELETE FROM {$table} WHERE {$idField} IN ({$placeholderString})";

        return $this->run($query, $params);
    }


    protected function run(string $query, array $params = []): bool
    {
        try {
            $this->logQuery();
            $start = microtime(true);

            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }
            Logger::logQuery($query, $params);

            $result = $this->db->execute();
            $this->executionTime += microtime(true) - $start;

            return $result;
        } catch (PDOException $e) {
            Logger::logError($e, 'Run Query');
            return false;
        }
    }

    protected function fetchAll(string $query, array $params = []): array
    {
        try {
            $this->logQuery();
            $start = microtime(true);

            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }
            Logger::logQuery($query, $params);

            $result = $this->db->resultSet();
            $this->executionTime += microtime(true) - $start;

            return $result;
        } catch (PDOException $e) {
            Logger::logError($e, 'FetchAll Query');
            return [];
        }
    }

    protected function fetchOne(string $query, array $params = []): mixed
    {
        try {
            $this->logQuery();
            $start = microtime(true);

            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }
            Logger::logQuery($query, $params);

            $result = $this->db->single();
            $this->executionTime += microtime(true) - $start;

            return $result;
        } catch (PDOException $e) {
            Logger::logError($e, 'FetchOne Query');
            return null;
        }
    }

    protected function countAll(
        array $filters = [],
        string $customTable = '',
        ?array $searchFields = null,
        ?array $exactFields = null
    ): int {
        $table = $customTable ?: $this->defaultTable;
        $searchFields = $searchFields ?? $this->defaultSearchFields;
        $exactFields = $exactFields ?? $this->defaultExactFields;

        $query = "SELECT COUNT(*) as total FROM {$table} WHERE 1=1";
        $params = [];

        if (!empty($filters['keyword']) && !empty($searchFields)) {
            $likeParts = [];
            foreach ($searchFields as $idx => $field) {
                $paramKey = ":keyword{$idx}";
                $likeParts[] = "{$field} LIKE {$paramKey}";
                $params[$paramKey] = "%" . $filters['keyword'] . "%";
            }
            $query .= " AND (" . implode(' OR ', $likeParts) . ")";
        }

        if (!empty($exactFields)) {
            foreach ($exactFields as $field) {
                if (!empty($filters[$field])) {
                    $paramKey = ":{$field}";
                    $query .= " AND {$field} = {$paramKey}";
                    $params[$paramKey] = $filters[$field];
                }
            }
        }

        try {
            $this->logQuery();
            $start = microtime(true);

            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }
            Logger::logQuery($query, $params);

            $result = $this->db->single();
            $this->executionTime += microtime(true) - $start;

            return (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            Logger::logError($e, 'CountAll Query');
            return 0;
        }
    }

    protected function filterData(
        array $filters = [],
        string $customTable = '',
        ?array $searchFields = null,
        ?array $exactFields = null
    ): array {
        $table = $customTable ?: $this->defaultTable;
        $searchFields = $searchFields ?? $this->defaultSearchFields;
        $exactFields = $exactFields ?? $this->defaultExactFields;

        $query = "SELECT * FROM {$table} WHERE 1=1";
        $params = [];

        // Search LIKE
        if (!empty($filters['keyword']) && !empty($searchFields)) {
            $likeParts = [];
            foreach ($searchFields as $idx => $field) {
                $paramKey = ":keyword{$idx}";
                $likeParts[] = "{$field} LIKE {$paramKey}";
                $params[$paramKey] = '%' . $filters['keyword'] . '%';
            }
            $query .= " AND (" . implode(' OR ', $likeParts) . ")";
        }

        // Exact Match
        if (!empty($exactFields)) {
            foreach ($exactFields as $field) {
                if (!empty($filters[$field])) {
                    $paramKey = ":{$field}";
                    $query .= " AND {$field} = {$paramKey}";
                    $params[$paramKey] = $filters[$field];
                }
            }
        }

        // Handle Sorting
        $sortField = $this->getDefaultSortField();
        $sortDirection = $this->getDefaultSortDirection();
        if (!empty($filters['sort_by'])) {
            $sortParts = explode('_', $filters['sort_by']);
            if (count($sortParts) === 2) {
                $requestedField = $sortParts[0];
                $requestedDirection = strtoupper($sortParts[1]);
                if (in_array($requestedField, $this->getAllowedSortFields()) && in_array($requestedDirection, ['ASC', 'DESC'])) {
                    $sortField = $requestedField;
                    $sortDirection = $requestedDirection;
                }
            }
        }
        $query .= " ORDER BY {$sortField} {$sortDirection}";

        // Handle Pagination
        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 10;
        $offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;
        $query .= " LIMIT {$limit} OFFSET {$offset}";

        return $this->fetchAll($query, $params);
    }
}
