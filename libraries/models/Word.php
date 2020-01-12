<?php

namespace Models;

class Word extends Model
{
    protected $table = "word";

    public function findLast()
    {
        return $this->pdo->query("SELECT id, word FROM word ORDER BY id DESC LIMIT 1")->fetch();
    }

    public function insert(string $input): void
    {
        $query = $this->pdo->prepare("INSERT INTO word(word) VALUES (:input)");
        $query->execute(['input' => $input]);
        $query->closeCursor();
    }
}
