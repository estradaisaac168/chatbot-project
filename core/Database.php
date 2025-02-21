<?php

namespace Core;

class Database
{
    private static $connection;

    public static function getConnection()
    {
        if (!self::$connection) {
            $host = 'b8sc95ra9lhcy4qfsyip-mysql.services.clever-cloud.com';
            $dbname = 'b8sc95ra9lhcy4qfsyip';
            $username = 'udhjkhuwjyqzydyf';
            $password = 'sXhc78bGr3UD5kpgkvjK';
            $uri = 'mysql://udhjkhuwjyqzydyf:sXhc78bGr3UD5kpgkvjK@b8sc95ra9lhcy4qfsyip-mysql.services.clever-cloud.com:3306/b8sc95ra9lhcy4qfsyip';
            $dsn = "mysql:host=" . $host . ";dbname=" . $dbname . ";charset=utf8";

            try {
                self::$connection = new \PDO($dsn, $username, $password);
                self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                // echo("OK");
            } catch (\PDOException $e) {
                die("Error de conexión: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
