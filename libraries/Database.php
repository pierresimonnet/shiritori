<?php

class Database
{
    /**
     * @return PDO
     */
    public static function getPdo(): PDO
    {
        try {
            return new PDO('mysql:host=127.0.0.1;dbname=shiritori', 'root', 'root', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
