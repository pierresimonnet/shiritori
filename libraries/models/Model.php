<?php

namespace Models;

use Database;

abstract class Model
{
    protected $pdo;
    protected $table;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $response = $this->pdo->query($sql);
        return $response->fetchAll();
    }

    public function reset(): void
    {
        $this->pdo->exec("TRUNCATE TABLE {$this->table}");
    }
}
