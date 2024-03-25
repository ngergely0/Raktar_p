<?php
class DB
{
    protected $mysqli;

    static function databaseExists()
    {
        $mysqli = mysqli_connect($host = 'localhost', $user = 'root', $password = null, 'mysql');
        $query = "SELECT SCHEMA_NAME
                    FROM INFORMATION_SCHEMA.SCHEMATA
                    WHERE SCHEMA_NAME = 'registry_db';";
 
        return $mysqli->query($query)->num_rows > 0;
    }
 
    static function createDatabase()
    {
        $mysqli = mysqli_connect($host = 'localhost', $user = 'root', $password = null, 'mysql');
        if (!$mysqli) {
            die("Connection failed: " . mysqli_connect_error());
        }
 
        $sql = "CREATE DATABASE registry_db DEFAULT CHARACTER SET utf8 ;";
        $mysqli->query($sql);
    }

    function deleteDatabase()
    {
        $result = $this->mysqli->query("DROP DATABASE registry_db;");
        return $result;
    }


    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'registry_db')
    {
        if (!self::databaseExists()) {
            self::createDatabase();
        }
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
