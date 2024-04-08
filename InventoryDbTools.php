<?php
class InventoryDbTools {
    const DBTABLE = 'inventory';

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

    function createInventory($itemName,$Qty)
    {
        $sql = "INSERT INTO " . self::DBTABLE . " (item_name,quantity,min_quantity) VALUES (?, ?, 10)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("si", $itemName, $Qty);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt a leltár beszúrása közben";
            return false;
        }
        return true;
    }

    function truncateInventory()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

    function deleteInventory()
    {
        $result = $this->mysqli->query("DROP TABLE " . self::DBTABLE);
        return $result;
    }

    
    public function getInventoryByWarehouseId($warehouseId) {
        $query = "SELECT s.item_name, i.quantity 
                  FROM shelves s
                  INNER JOIN inventory i ON s.item_name = i.item_name
                  WHERE s.warehouse_id = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("i", $warehouseId);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $inventory = [];
        while ($row = $result->fetch_assoc()) {
            $inventory[$row['item_name']] = $row['quantity'];
        }
    
        $stmt->close();
        return $inventory;
    }

    /*public function modifyInventory($itemQty, $shelfId)
    {
        $sql = "UPDATE " . self::DBTABLE . " SET quantity = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ii", $itemQty, $shelfId);
        $result = $stmt->execute();
        if (!$result) {
            echo "Error updating shelf: " . $this->mysqli->error;
            return false;
        }
        return true;
    }*/
    
    
    
    
}
