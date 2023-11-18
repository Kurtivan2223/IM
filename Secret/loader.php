<?php
    ob_start();
    session_start();

    define('base_path', str_replace('loader.php', '', str_replace("\\", '/', __FILE__)) . 'include/');
    define('vendor_path', str_replace('loader.php', '', str_replace("\\", '/', __FILE__)) . 'vendor/');

    require_once vendor_path . "autoload.php";
    require_once base_path . "config.php";
    require_once base_path . "functions.php";
    require_once base_path . "Database.php";
    require_once base_path . "user.php";
    require_once base_path . "admin.php";

    Database::db_connection();
    User::post_handler();
    Admin::post_handler();