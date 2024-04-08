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

    function createShelves($shelves,$itemName)
    {
        $sql = "INSERT INTO " . self::DBTABLE . " (shelf_line,item_name) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $shelves, $itemName);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt a leltár beszúrása közben";
            return false;
        }
        return true;
    }

    function truncateShelves()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

    function deleteShelves()
    {
        $result = $this->mysqli->query("DROP TABLE " . self::DBTABLE);
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

            $shelfLine = $shelf[0];
            $stmt->execute();
        }

        return true;
    } 

    private function findWarehouseId($shelf, $warehouseIds)
    {
        $shelfPrefix = substr($shelf[0], 0, 1);

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

    public function searchShelves($needle){
        $sql = "SELECT * FROM  shelves WHERE name LIKE '%$needle%'";
        $stmt = $this->mysqli->prepare($sql);
       
        $result = $this->mysqli->query($sql);
 
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){
                $shelves[] = $row;
            }
        }
 
        return $shelves;
    }
 
    public function getShelvesByWarehouseId($warehouseId) {
        $query = "SELECT shelves.*, warehouses.name AS warehouse_name FROM shelves INNER JOIN warehouses ON shelves.warehouse_id = warehouses.id WHERE shelves.warehouse_id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $warehouseId);
        $stmt->execute();
        $result = $stmt->get_result();
        $shelves = [];
        while ($row = $result->fetch_assoc()) {
            $shelves[] = $row;
        }
        $stmt->close();
        return $shelves;
    }

    public function deleteShelfById($shelfId)
    {
        $sql = "DELETE FROM " . self::DBTABLE . " WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $shelfId);
        $result = $stmt->execute();
        if (!$result) {
            echo "Error deleting shelf: " . $this->mysqli->error;
            return false;
        }
        return true;
    }

    public function modifyShelf($shelfId, $shelfLine, $modifiedshelfId, $itemName)
{
    $sql = "UPDATE " . self::DBTABLE . " SET shelf_line = ?, item_name = ?, id = ? WHERE id = ?";
    $stmt = $this->mysqli->prepare($sql);
    $stmt->bind_param("ssii", $shelfLine, $itemName, $modifiedshelfId, $shelfId);
    $result = $stmt->execute();
    if (!$result) {
        echo "Error updating shelf: " . $this->mysqli->error;
        return false;
    }
    return true;
}


    public function addShelf($itemName, $shelf_line, $shelfId, $warehouseId)
    {
        $sql = "INSERT INTO " . self::DBTABLE . " (item_name, id, shelf_line, warehouse_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sisi", $itemName, $shelfId, $shelf_line, $warehouseId);
        $result = $stmt->execute();
        if (!$result) {
            echo "Error adding shelf: " . $this->mysqli->error;
            return false;
        }
        return true;
    }

    public function getShelfById($shelfId)
    {
        $query = "SELECT * FROM " . self::DBTABLE . " WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $shelfId);
        $stmt->execute();
        $result = $stmt->get_result();
        $shelf = $result->fetch_assoc();
        $stmt->close();
        return $shelf;
    }

    public function getAll(): array
    {
        $query = "SELECT warehouses.name, shelves.shelf_line, shelves.id, shelves.item_name, inventory.quantity FROM shelves INNER JOIN inventory ON inventory.item_name = shelves.item_name INNER JOIN warehouses ON warehouses.id = shelves.warehouse_id  ORDER BY warehouses.name;";
 
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }

}
