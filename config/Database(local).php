<?php

namespace Config;
class Database{
    private static $instance = null;
    private static $connection;

    private const DB_HOST = 'localhost';
    private const DB_USER = 'root';
    private const DB_PASS = ''; // Update with your password
    private const DB_NAME = 'product_db';

    // Get the database connection
    public static function getConnection(){
        if (self::$instance === null) {
            self::$connection = new \mysqli(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);

            // Check for connection errors
            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }

            // Mark the instance as created
            self::$instance = true;
        }

        return self::$connection; // Return the connection
    }
}
