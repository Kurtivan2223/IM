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

    //Passenger Seat

    $array = array();
    $letters = range("A", "H");
    $numbers = range(1, 30);

    foreach($letters as $letter)
    {
        foreach($numbers as $number)
        {
            $array[] = $letter.$number;
        }
    }

    foreach($array as $seat)
    {
        $query = Database::$connection->prepare("SELECT COUNT(*) FROM `booking` WHERE FlightID = :id AND PassengerSeatNumber = :seat");
        $query->execute(array(
            ":fid"=> $fid,
            ":seat"=> $seat
        ));

        $count = $query->fetchColumn();

        if($count < 0)
        {
            print_r($seat);
        }
    }