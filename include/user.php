<?php

class User
{
    private $handle;

    public function __construct()
    {
        $this->handle = new Database();
    }

    public function post_handler()
    {
        if(!empty($_POST['submit']))
        {
            if($_POST['submit'] == 'login')
                self::login();
            if($_POST['submit'] == 'register')
                self::register();
        }
    }

    public static function login()
    {
        if(empty($_POST['useroremail'])
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

            $query = $this->handle->prepare("SELECT ID, Username, Email FROM Account WHERE Email = :email AND Password = :password");
        }
        else
        {
            $query = $this->handle->prepare("SELECT ID, Username, Email FROM Account WHERE Username = :user AND Password = :password");
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
        if (empty($_POST['username'])
        || empty($_POST['password']) 
        || empty($_POST['repassword']) 
        || empty($_POST['email'])
        || empty($_POST['fname'])
        || empty($_POST['lname'])
        || empty($_POST['gender']))
        {
            error_msg('Missing Input.');
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

        if(!preg_match('/^[[:alnum:][:punct:]]+$/', $_POST['password']))
        {
            error_msg('Password should have atleast 1 numerical and 1 special character.');
            return false;
        }

        if ($_POST['password'] != $_POST['repassword']) {
            error_msg('Passwords is not equal.');
            return false;
        }

        if (!(strlen($_POST['password']) >= 4 && strlen($_POST['password']) <= 16)) {
            error_msg('Password length is not valid.');
            return false;
        }

        if (!(strlen($_POST['username']) >= 2 && strlen($_POST['username']) <= 16)) {
            error_msg('Username length is not valid.');
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
        $query = $this->handle->prepare(
            "INSERT INTO account VALUES(:username, :email, :password, :fname, :lname, :gender, CURDATE())"
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

    public function check_email_exists($email)
    {
        if (!empty($email)) {
            $query = $this->handle->prepare("SELECT email FROM account WHERE email = :email");
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($data[0])) {
                return true;
            }
        }
        return false;
    }

    public function check_username_exists($username)
    {
        if (!empty($username)) {
            $query = $this->handle->prepare("SELECT Username FROM `Account` WHERE Username = :username");
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($data[0])) {
                return true;
            }
        }

        return false;
    }

    public function sendSupportInquiry($userid, $username, $message)
    {
        if(empty($userid))
        {
            $query = $this->handle->prepare("SELECT ID FROM `Account` WHERE username = :username");
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();
            $userid = $query->fetch(PDO::FETCH_ASSOC);
        }

        $supportID = generateSupportToken();

        $query = $this->handle->prepare("SELECT TicketNo FROM `supportticket` WHERE TicketNo = :id");
        $query->bindParam(':id', $supportID, PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(PDO::FETCH_ASSOC);

        if(empty($data))
        {
            $query = $this->handle->prepare("INSERT INTO `supportticket` VALUES(:id, :uid, :username, :message, CURDATE(), No)");
            $query->execute(array(
                ":id" => $supportID,
                ":uid" => $userid,
                ":username" => $username,
                ":message" => $message
            ));

            success_msg('Inquiry Sent.');
        }
    }
}
