<?php

class Database
{
    /**
     * @return PDO
     */
    public static function getPdo(): PDO
    {
        try {
            return new PDO('mysql:host=nakayoicej028.mysql.db;dbname=nakayoicej028', 'nakayoicej028', '5V6ZLRy5CX3mks7', [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
