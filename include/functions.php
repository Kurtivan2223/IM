<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error_msg = "";
$success_msg = "";

/**
 * return $ip
 */
function getip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];

    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function get_config($name)
{
    global $config;

    if (!empty($name))
    {
        if (isset($config[$name]))
        {
            return $config[$name];
        }
    }

    return false;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////           BOOTSTRAP        ////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////

function error_msg($input = false)
{
    global $error_error;

    if (!empty($error_error))
    {
        echo "<p class=\"alert alert-danger\">$error_error</p>";
    } elseif (!empty($input))
    {
        $error_error = $input;
    }
}

function success_msg($input = false)
{
    global $success_msg;

    if (!empty($success_msg))
    {
        echo "<p class=\"alert alert-success\">$success_msg</p>";
    } elseif (!empty($input))
    {
        $success_msg = $input;
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////


function send_phpmailer($email, $subject, $message)
{
    try {
        $mail = new PHPMailer(true);

        if (get_config('debug_mode'))
        {
            $mail->SMTPDebug = 2;
        }
        
        $mail->isSMTP();
        $mail->Host = get_config('smtp_host');
        $mail->SMTPAuth = get_config('smtp_auth');
        $mail->Username = get_config('smtp_user');
        $mail->Password = get_config('smtp_pass');
        $mail->SMTPSecure = get_config('smtp_secure');
        $mail->Port = get_config('smtp_port');

        //Recipients
        $mail->setFrom(get_config('smtp_mail'));
        $mail->addAddress($email);     // Add a recipient
        $mail->addReplyTo(get_config('smtp_mail'));

        // Content
	$mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
    } 
    catch (Exception $e)
    {
        if (get_config('debug_mode'))
        {
            echo 'Message: ' . $e->getMessage();
        }
    }
    return true;
}
