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
            self::logout();
        }
    }

    public static function login()
    {
        //Todo..
    }

    public static function register()
    {
        if ($_POST['submit'] != 'register' 
        || empty($_POST['password']) 
        || empty($_POST['username']) 
        || empty($_POST['repassword']) 
        || empty($_POST['email'])
        || empty($_POST['fname'])
        || empty($_POST['lname'])
        || empty($_POST['bday'])
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

        if(!preg_match('/^[0-9A-Z!@#$%^&*()-_+=,.<>?/|{}[]:;~`\\ ]+$/', strtoupper($_POST['password'])))
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
            "INSERT INTO account VALUES(:username, :email, :password, :fname, :lname, :bday, :gender, :regdate, :ip)"
        );

        $query->execute(array(
            ':username' => $_POST['username'],
            ':email' => $_POST['email'],
            ':password' => $_POST['password'],
            ':fname' => $_POST['fname'],
            ':lname' => $_POST['lname'],
            ':bday' => $_POST['bday'],
            ':gender' => $_POST['gender'],
            ':regdate' => date('Y-m-d'),
            ':ip' => getip()
        ));

        success_msg('Your account has been created.');

        header("Location: home.php");
        exit();
    }

    public static function logout()
    {
        if($_POST['submit'] != 'logout')
        {
            return false;
        }

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
