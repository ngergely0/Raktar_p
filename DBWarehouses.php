<?php

require_once 'WarehousesInterface.php';
require_once 'DB.php';


class DBWarehouses extends DB implements WarehousesInterface
{

    public function createTable(){
        $query = 'CREATE TABLE IF NOT EXISTS warehouses (id int NOT NULL, name varchar(50) NOT NULL)';
        return $this->mysqli->query($query);
    }

    public function create(array $data): ?int
    {
        $sql = 'INSERT INTO warehouses (%s) VALUES (%s)';
        $fields = '';
        $values = '';
        foreach ($data as $field => $value) {
            if ($fields > '') {
                $fields .= ',' . $field;
            } else
                $fields .= $field;

            if ($values > '') {
                $values .= ',' . "'$value'";
            } else
                $values .= "'$value'";
        }
        $sql = sprintf($sql, $fields, $values);
        $this->mysqli->query($sql);

        $lastInserted = $this->mysqli->query("SELECT LAST_INSERT_ID() id;")->fetch_assoc();

        return $lastInserted['id'];
    }

}

