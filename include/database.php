<?php

class Database extends PDO
{
    public function __construct()
    {
        parent::__construct("mysql:host=".get_config('db_auth_host').";dbname=".get_config('db_auth_dbname')."", get_config('db_auth_user'), get_config('db_auth_pass'));
        $this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
}
