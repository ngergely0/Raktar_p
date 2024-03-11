<?php

class DB
{
    protected $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'registry_db')
    {
        $this->mysqli = mysqli_connect($host, $user, $password, $db);
        if (!$this->mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }

    }

    function __destruct()
    {
        $this->mysqli->close();
    }
}


