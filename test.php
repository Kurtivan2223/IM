<?php

    include_once "./include/config.php";
    include_once "./include/functions.php";
    include_once "./include/Database.php";
    include_once "./include/admin.php";
    include_once "./include/user.php";

    $admin = new Admin();
    $user = new User();

    echo (!$user->check_username_exists("kurtivan23")) ? "true" : "false";

    echo "<br/>";

    $data = $admin->getUsers();

    foreach($data as $row)
    {
        echo "ID: " . $row['ID'] . " Username: " . $row['Username'] . "<br />";
    }