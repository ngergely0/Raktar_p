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
        $sql = "UPDATE " . self::DBTABLE . " SET warehouse_id = ? WHERE shelf_line = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $warehouseId, $shelfLine);

        foreach ($shelves as $shelf) {
            
            $warehouseId = $this->findWarehouseId($shelf, $warehouseIds);
            if ($warehouseId === false) {
                continue;
            }

            $shelfLine = $shelf;
            $stmt->execute();
        }

        return true;
    }

    private function findWarehouseId($shelf, $warehouseIds)
    {
        $shelfPrefix = substr($shelf, 0, 1);

        $warehouseMapping = [
            'T' => 1,
            'H' => 2,
            'F' => 3,
            'B' => 4
        ];

        if (array_key_exists($shelfPrefix, $warehouseMapping)) {
            $warehouseId = $warehouseMapping[$shelfPrefix];
            if (in_array($warehouseId, $warehouseIds)) {
                return $warehouseId;
            }
        }

        return false;
    }

}
