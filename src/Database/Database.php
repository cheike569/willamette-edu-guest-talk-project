<?php

namespace Src\Database;

use Medoo\Medoo;

class Database
{
    protected static Medoo|null $database = null;
    public static function getDatabase(): Medoo
    {
        if(self::$database) {
            return self::$database;
        }

        $init = false;
        if(!file_exists(__DIR__ . '/../../database.db')) {
            $init = true;
        }

        // In a real world application, you would not hardcode these values here
        // see https://medoo.in/
        self::$database = new Medoo([
            'type' => 'sqlite',
            'database' => __DIR__ . '/../../database.db'
        ]);

        if($init) {
            // Auto Init DB on first run
            self::resetDatabase();
        }

        return self::$database;
    }

    // Database Structure
    public static function resetDatabase(): void
    {
        self::$database->query('DROP TABLE IF EXISTS images');
        self::$database->query('CREATE TABLE images (id INTEGER PRIMARY KEY, path VARCHAR, thumbnail VARCHAR, filesize INTEGER, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)');
    }
}