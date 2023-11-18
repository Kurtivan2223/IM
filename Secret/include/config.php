<?php
/*===================================================================
Debug Control for website
debug_mode
    Boolean True or False
=====================================================================*/
$config['debug_mode'] = false;
/*===================================================================
Your Auth MySQL information.
db_auth_host
    Auth Database Host
db_auth_port
    Auth Database Port
db_auth_user
    Auth Database Username
db_auth_pass
    Auth Database Password
db_auth_dbname
    Auth Database DBName
=====================================================================*/
$config['db_auth_host'] = '127.0.0.1';
$config['db_auth_port'] = '3306'; 
$config['db_auth_user'] = 'root';
$config['db_auth_pass'] = '';
$config['db_auth_dbname'] = 'System';
/*===================================================================
Your Registration and login Control
md5_password
    set the password to md5 or not
=====================================================================*/
$config['md5_password'] = false;
/*===================================================================
SMTP config.
We need this part to send an email. (used for restore password and 2FA)
You can use your own SMTP or Gmail/Yahoo/Hotmail and etc
enable_mail
    Enables the Usage of Send mail
smtp_host
    Specify main and backup SMTP servers
smtp_port
    TCP port to connect to
smtp_auth
    Enable SMTP authentication
smtp_user
    SMTP username
smtp_pass
    SMTP password
smtp_secure
    Enable TLS encryption, `ssl` also accepted
smtp_mail
    Send emails by ...
=====================================================================*/
$config['enable_mail'] = false;
$config['smtp_host'] = 'smtp1.example.com';
$config['smtp_port'] = 587;
$config['smtp_auth'] = true;
$config['smtp_user'] = 'user@example.com';
$config['smtp_pass'] = 'SECRET';
$config['smtp_secure'] = 'tls';
$config['smtp_mail'] = 'no-reply@example.com';
