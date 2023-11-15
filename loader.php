<?php
    ob_start();
    session_start();

    require_once "./include/config.php";
    require_once "./include/functions.php";
    require_once "./include/Database.php";
    require_once "./include/user.php";
    require_once "./include/admin.php";

    Database::db_connection();
    Admin::post_handler();
    User::post_handler();