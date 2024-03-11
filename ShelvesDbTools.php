<?php

class ShelvesDbTools {
    const DBTABLE = 'shelves';

    private $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'registry_db')
    {
        $this->mysqli = new mysqli($host, $user, $password, $db);
        if ($this->mysqli->connect_errno){
            throw new Exception($this->mysqli->connect_errno);
        }
    }

    function __destruct()
    {
        $this->mysqli->close();
    }

    function createShelves($shelves)
    {
        $result = $this->mysqli->query("INSERT INTO " . self::DBTABLE . " (shelf_line) VALUES ('$shelves')");
        if (!$result) {
            echo "Hiba történt a $shelves beszúrása közben";

        }
        return $result;
    }

    function truncateShelves()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

    function updateShelves($warehouseIds, $shelves)
    {
    $query = "UPDATE shelves SET warehouse_id = ? WHERE shelf_line = ?";
    $stmt = $this->mysqli->prepare($query);
    if ($stmt) {
        foreach ($warehouseIds as $warehouseId) {
            foreach ($shelves as $shelf) {
                $stmt->bind_param("is", $warehouseId, $shelf);
                $stmt->execute();
            }
        }
        $stmt->close();
    } else {
        echo "Hiba történt a frissítés közben: " . $this->mysqli->error;
    }
    }


}
