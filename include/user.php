<?php

class User
{
    public static function post_handler()
    {
        if(!empty($_POST['submit']))
        {
            self::login();
            self::register();
            self::bookflight();
        }
    }

    public static function login()
    {
        if($_POST['submit'] != 'login'
        || empty($_POST['useroremail'])
        || empty($_POST['password']))
        {
            return false;
        }

        if(str_contains($_POST['useroremail'], '@'))
        {
            if (!filter_var($_POST['useroremail'], FILTER_VALIDATE_EMAIL)) {
                error_msg('Invalid email.');
                return false;
            }

            $query = Database::$connection->prepare("SELECT ID, Username, Email FROM Account WHERE Email = :email AND Password = :password");
        }
        else
        {
            $query = Database::$connection->prepare("SELECT ID, Username, Email FROM Account WHERE Username = :user AND Password = :password");
        }

        $query->execute(array($_POST['useroremail'], $_POST['password']));

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if(empty($result[0]))
        {
            error_msg('Username/Email or Password is Incorrect. Try again.');
            return false;
        }

        $result['ID'] = $_SESSION['ID'];
        $result['Username'] = $_SESSION['Username'];
        $result['Email'] = $_SESSION['Email'];

        success_msg('Successfully Logged in.');

        header("Location: home.php");
        exit();
    }

    public static function register()
    {
        //Checks wether submit post is equal to register
        //Still checks if post are empty becuase even if the html form has required tag it can still bypass by editing using inspect element so it really is a good practice to add a validation in the server side
        if ($_POST['submit'] != 'register'
        || empty($_POST['username'])
        || empty($_POST['password']) 
        || empty($_POST['repassword']) 
        || empty($_POST['email'])
        || empty($_POST['fname'])
        || empty($_POST['lname'])
        || empty($_POST['gender']))
        {
            return false;
        }

        if (!preg_match('/^[0-9A-Z-_]+$/', strtoupper($_POST['username']))) {
            error_msg('Use valid characters for username.');
            return false;
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            error_msg('Use valid characters for email.');
            return false;
        }

        if(!preg_match('/^[a-zA-Z0-9]+$/', $_POST['password']))
        {
            error_msg('Password should have atleast 1 Lower/Upper Case and 1 Numerical.');
            return false;
        }

        if ($_POST['password'] != $_POST['repassword']) {
            error_msg('Passwords is not equal.');
            return false;
        }

        if (!(strlen($_POST['password']) >= 4 && strlen($_POST['password']) <= 16)) {
            error_msg('Password length Should be greater than 4 and less than 16.');
            return false;
        }

        if (!(strlen($_POST['username']) >= 2 && strlen($_POST['username']) <= 16)) {
            error_msg('Username length Should be greater than 2 and less than 16.');
            return false;
        }

        if (!self::check_email_exists(strtoupper($_POST['email']))) {
            error_msg('Email already exists.');
            return false;
        }

        if (!self::check_username_exists(strtoupper($_POST['username']))) {
            error_msg('Username already exists.');
            return false;
        }

        //if all requirement are met
        $query = Database::$connection->prepare(
            "INSERT INTO `Account`(Username, Email, Password, Fname, Lname, Gender, RegisterDate) VALUES(:username, :email, :password, :fname, :lname, :gender, CURDATE())"
        );

        $query->execute(array(
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':password' => $_POST['password'],
            ':fname' => $_POST['fname'],
            ':lname' => $_POST['lname'],
            ':gender' => $_POST['gender']
        ));

        success_msg('Your account has been created.');
    }

    public function logout()
    {
        //destroy session
        session_destroy();

         // Redirect the user to the login page or any other page after logout
        header("Location: login.php");
        exit();

    }

    public static function searchFlight()
    {
        $query = Database::$connection->prepare("SELECT * FROM `Flight` WHERE Origin = :origin AND Distination = :distination");
        $query->execute(array(
            ":origin" => $_POST['origin'],
            ":distination" => $_POST['distination']
        ));
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if(!empty($data)) {
            return $data;
        }

        return false;
    }

    public static function bookflight()
    {
        if($_POST['submit'] != 'book')
        {
            return false;
        }

        if(empty($_POST['cabinclass']))
        {
            error_msg('Please Select Cabin Class');
            return false;
        }

        do
        {
            $bookid = bin2hex(random_bytes(15));
            $query = Database::$connection->prepare("SELECT BookID FROM `Booking` WHERE BookID = :id");
            $query->bindParam(':id', $bookid, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }while(!empty($data));

        do
        {
            $ticketNum = bin2hex(random_bytes(10));
            $query = Database::$connection->prepare("SELECT TicketNumber FROM `Booking` WHERE TicketNumber = :id");
            $query->bindParam(':id', $ticketNum, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);

        }while(!empty($data));

        $query = Database::$connection->prepare("INSERT INTO `Booking` VALUES(:id, :uid, :fid, CURDATE(), :seatnum, :class, :ticketnum, :TicketFare)");
        $query->execute(array(
            ":id"=> $bookid,
            ":uid"=> $_SESSION['ID'],
            ":fid" => $_POST['fid'],
            //":seatnum" => //Todo..,
            ":ticketnum" => $ticketNum,
            "ticketFare" => $_POST['fare']
        ));

        $query = Database::$connection->prepare("UPDATE `Flight` SET PassengerCount += 1 WHERE ID = :id");
        $query->bindParam(":id", $_POST['fid'], PDO::PARAM_STR);
        $query->execute();

        success_msg('Successfully Book the Flight.s');
    }

    public static function cancelBook()
    {
        if(empty($_GET['bookid']))
        {
            return false;
        }

        $query = Database::$connection->prepare("DELETE FROM `Booking` WHERE BookID = :id");
        $query->bindParam(":id", $_GET['bookid'], PDO::PARAM_STR);
        $query->execute();

        success_msg('Book Cancelled!');
    }

    public static function check_email_exists($email)
    {
        if (!empty($email)) {
            $query = Database::$connection->prepare("SELECT email FROM account WHERE email = :email");
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($data[0])) {
                return true;
            }
        }
        return false;
    }

    public static function check_username_exists($username)
    {
        if (!empty($username)) {
            $query = Database::$connection->prepare("SELECT Username FROM `Account` WHERE Username = :username");
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($data[0])) {
                return true;
            }
        }

        return false;
    }

    public static function sendSupportInquiry($userid, $username, $message)
    {
        if(empty($userid))
        {
            $query = Database::$connection->prepare("SELECT ID FROM `Account` WHERE username = :username");
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();
            $userid = $query->fetch(PDO::FETCH_ASSOC);
        }

        do
        {
            $supportID = bin2hex(random_bytes(20));
            $query = Database::$connection->prepare("SELECT TicketNo FROM `supportticket` WHERE TicketNo = :id");
            $query->bindParam(':id', $supportID, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetch(PDO::FETCH_ASSOC);
        }while(!empty($data));
        
        $query = Database::$connection->prepare("INSERT INTO `supportticket` VALUES(:id, :uid, :username, :message, CURDATE(), No)");
        $query->execute(array(
            ":id" => $supportID,
            ":uid" => $userid,
            ":username" => $username,
            ":message" => $message
        ));

        success_msg('Inquiry Sent.');
    }

    public static function getSeat($fid)
    {
        $array = array();
        $data = array();
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

            if($count < 1)
            {
                $data[] = $seat;
            }
        }

        return $data;
    }
}