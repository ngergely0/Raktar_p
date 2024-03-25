<?php

require_once 'ShelvesInterface.php';
require_once 'DB.php';



class DBShelves extends DB implements ShelvesInterface
{

    public function createTable(){
        $query = 'CREATE TABLE IF NOT EXISTS shelves (id int AUTO_INCREMENT PRIMARY KEY, shelf_line varchar(50), warehouse_id int NOT NULL, item_name varchar(50))';
        return $this->mysqli->query($query);
    }

    public function create(array $data): ?int
    {
        $sql = 'INSERT INTO shelves (%s) VALUES (%s)';
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
