<?php
class WarehousesDbTools {
    const DBTABLE = 'warehouses';
    private $mysqli;

    function __construct($host = 'localhost', $user = 'root', $password = null, $db = 'registry_db') {
        $this->mysqli = new mysqli($host, $user, $password, $db);
        if ($this->mysqli->connect_errno) {
            throw new Exception($this->mysqli->connect_errno);
        }
    }

    function __destruct() {
        $this->mysqli->close();
    }

    function createWarehouses($warehouseId,$warehouse)
    {
        $sql = "INSERT INTO " . self::DBTABLE . " (id,name) VALUES (?, ?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("is", $warehouseId, $warehouse);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt a leltár beszúrása közben";
            return false;
        }
        return true;
    }

    function truncateWarehouses()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

}
