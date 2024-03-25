<?php

require_once 'InventoryInterface.php';
require_once 'DB.php';



class DBInventory extends DB implements InventoryInterface
{

    public function createTable(){
        $query = 'CREATE TABLE IF NOT EXISTS inventory (id int AUTO_INCREMENT PRIMARY KEY, item_name varchar(50), quantity int NOT NULL, min_quantity int NOT NULL)';
        return $this->mysqli->query($query);
    }

    public function create(array $data): ?int
    {
        $sql = 'INSERT INTO inventory (%s) VALUES (%s)';
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
