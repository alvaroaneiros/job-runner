<?php

namespace App\Runner;

class JobDatabaseManager
{

    const MAX_RETRIES = 3;
    private $pdo;

    public function __construct($dbFile = 'jobs.db')
    {
        $dbFilePath = __DIR__ . DIRECTORY_SEPARATOR . $dbFile;

        // Initialize SQLite connection
        $this->pdo = new \PDO('sqlite:' . $dbFilePath);

        // Ensure the table is created
        $this->createTable();
    }

    private function createTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS jobs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                class TEXT NOT NULL,
                method TEXT NOT NULL,
                args TEXT,
                result TEXT,
                status TEXT NOT NULL DEFAULT 'pending',
                retry_count INTEGER NOT NULL DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $this->pdo->exec($sql);
    }

    public function addJob($class, $method, array $args = [])
    {
        $sql = "
            INSERT INTO jobs (class, method, args, status)
            VALUES (:class, :method, :args, 'pending')
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':class' => $class,
            ':method' => $method,
            ':args' => json_encode($args),
        ]);

        return $this->pdo->lastInsertId();
    }

    public function updateJobStatus(int $id, string $status, ?string $result = null): void
    {
        $sql = "
            UPDATE jobs
            SET status = :status, 
            result = :result,
            updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':result' => $result
        ]);
    }
    
    public function completeJobStatus(int $id, ?string $result = null): void
    {
        $sql = "
            UPDATE jobs
            SET status = 'completed', 
            result = :result, 
            updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ";        

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':result' => $result,
        ]);
    }

    public function getAllJobs()
    {
        $sql = "
            SELECT * FROM jobs
            ORDER BY created_at ASC
        ";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPendingJobs()
    {
        $sql = "
            SELECT * FROM jobs
            WHERE status = 'pending'
            ORDER BY created_at ASC
        ";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getJobById($id)
    {
        $sql = "
            SELECT * FROM jobs
            WHERE id = :id
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function incrementRetryCount($id)
    {
        $sql = "
        UPDATE jobs
        SET retry_count = retry_count + 1, updated_at = CURRENT_TIMESTAMP
        WHERE id = :id
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
    }

    public function canRetry($id)
    {
        $sql = "
            SELECT retry_count
            FROM jobs
            WHERE id = :id
        ";
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $job = $stmt->fetch(\PDO::FETCH_ASSOC);
    
        return $job && $job['retry_count'] < self::MAX_RETRIES;
    }

}
