<?php

class User
{
    private static $handle = new Database();

    public static function post_handler()
    {
        if(!empty($_POST['submit']))
        {
            self::register();
            self::login();
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
            $query = self::$handle->prepare("SELECT ID, Username, Email FROM Account WHERE Email = :email AND Password = :password");
        }
        else
        {
            $query = self::$handle->prepare("SELECT ID, Username, Email FROM Account WHERE Username = :user AND Password = :password");
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
        || empty($_POST['password']) 
        || empty($_POST['username']) 
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

        if(!preg_match('/^[[:alnum:][:punct:]]+$/', strtoupper($_POST['password'])))
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
        $query = self::$handle->prepare(
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

        header("Location: login.php");
        exit();
    }

    public static function logout()
    {
        //destroy session
        session_destroy();

         // Redirect the user to the login page or any other page after logout
        header("Location: login.php");
        exit();

    }

    public static function check_email_exists($email)
    {
        if (!empty($email)) {
            $query = self::$handle->prepare("SELECT email FROM account WHERE email = :email");
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $datas = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($datas[0])) {
                return true;
            }
        }
        return false;
    }

    public static function check_username_exists($username)
    {
        if (!empty($username)) {
            $query = self::$handle->prepare("SELECT username FROM account WHERE username = :username");
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->execute();
            $datas = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($datas[0])) {
                return true;
            }
        }
        return false;
    }
}
