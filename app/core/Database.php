<?php

declare(strict_types=1);

class Database
{
    private static ?Database $instance = null;
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $pass = DB_PASS;
    private string $db_name = DB_NAME;

    private PDO $dbh;
    private ?PDOStatement $stmt = null;
    private string $lastQuery = '';
    private array $lastParams = [];

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name}";
        $options = [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            Logger::logError($e, 'DB Connection Failed');
            die("DB Connection Error: " . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }

    public function query(string $query): void
    {
        $this->lastQuery = $query;
        $this->lastParams = [];
        $this->stmt = $this->dbh->prepare($query);
        // Logger::logQuery($query, []); // Uncomment jika ingin log otomatis
    }

    public function bind(string $param, mixed $value, int $type = PDO::PARAM_STR): void
    {
        $this->lastParams[$param] = $value;
        if ($this->stmt) {
            $this->stmt->bindValue($param, $value, $type);
        }
    }

    /**
     * Helper untuk bind array sekaligus
     */
    public function bindArray(array $params): void
    {
        foreach ($params as $key => $value) {
            $this->bind($key, $value);
        }
    }

    public function execute(array $params = []): bool
    {
        if ($this->stmt === null) return false;

        try {
            $result = $this->stmt->execute($params ?: $this->lastParams);
            // Logger::logQuery($this->lastQuery, $this->lastParams); // Uncomment jika ingin log otomatis saat eksekusi
            return $result;
        } catch (PDOException $e) {
            Logger::logError($e, $this->lastQuery);
            return false;
        }
    }

    public function resultSet(): array
    {
        return $this->execute() ? $this->stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    public function single(): array|false
    {
        return $this->execute() ? $this->stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function rowCount(): int
    {
        return $this->stmt ? $this->stmt->rowCount() : 0;
    }

    public function lastInserted(): int
    {
        return (int)$this->dbh->lastInsertId();
    }

    public function getError(): array
    {
        return $this->stmt ? $this->stmt->errorInfo() : [];
    }

    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->dbh->commit();
    }

    public function rollBack(): bool
    {
        return $this->dbh->rollBack();
    }
}
