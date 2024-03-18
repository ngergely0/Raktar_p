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
        $sql = "INSERT INTO " . self::DBTABLE . " (item_name,quantity) VALUES (?, ?)";
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

    function updateInventory()
    {
        // Fetch shelf data
        $shelvesData = $this->mysqli->query("SELECT id, shelf_line FROM shelves");
        if ($shelvesData === false) {
            echo "Error retrieving shelves data";
            return false;
        }

        // Create a mapping of shelf_line to shelf_id
        $shelfMapping = [];
        while ($shelf = $shelvesData->fetch_assoc()) {
            $shelfMapping[$shelf['shelf_line']] = $shelf['id'];
        }

        // Fetch inventory data
        $inventoryData = $this->mysqli->query("SELECT id, shelf_line FROM inventory");
        if ($inventoryData === false) {
            echo "Error retrieving inventory data";
            return false;
        }

        // Prepare update statement
        $updateStmt = $this->mysqli->prepare("UPDATE inventory SET shelf_id = ? WHERE id = ?");
        if (!$updateStmt) {
            echo "Error preparing update statement";
            return false;
        }

        // Update inventory items using mapping
        while ($item = $inventoryData->fetch_assoc()) {
            $itemId = $item['id'];
            $shelfLine = $item['shelf_line'];

            // Check if shelf_line exists in the mapping
            if (isset($shelfMapping[$shelfLine])) {
                $shelfId = $shelfMapping[$shelfLine];
                $updateStmt->bind_param("ii", $shelfId, $itemId);
                $result = $updateStmt->execute();
                if (!$result) {
                    echo "Error updating inventory item with ID $itemId";
                }
            } else {
                echo "Shelf ID not found for shelf line $shelfLine";
            }
        }

        return true;
    }
    
}
