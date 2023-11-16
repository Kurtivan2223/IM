<?php
    include_once "loader.php";

    $data = Admin::getUsers();

    if(!empty($data))
    {
        foreach($data as $row)
        {
            switch($row['Gender'])
            {
                case 'M':
                    $gender = "Male";
                    break;
                case 'F':
                    $gender = "Female";
                    break;
            }

            echo "ID: " . $row['ID'] . " | Username: " . $row['Username'] . " | Full Name: " . $row['Fname'] ." ". $row['Lname'] . " | Gender: " . $gender . "<br />";
        }
    }