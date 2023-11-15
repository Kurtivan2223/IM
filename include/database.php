<?php
class Database
{
    public static $connection;

    public static function db_connection()
    {
        self::$connection = new PDO("mysql:host=".get_config('db_auth_host').";dbname=".get_config('db_auth_dbname')."", get_config('db_auth_user'), get_config('db_auth_pass'));
        self::$connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}