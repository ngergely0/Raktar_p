<?php
class UserDbTools {
    const DBTABLE = 'users';
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

    function getNewToken(){
        return str_replace(["-","+"], ["",""], base64_encode(random_bytes(160/8)));
    }

    
    function getValidUntil(){
        $validUntil = new DateTime();
        $validUntil->add(new DateInterval('PT1H'));
        return $validUntil->format("Y-m-d H:i:s");
    }

    function createUsers($name, $email, $password)
    {
        $token = $this->getNewToken();
        $date = $this->getValidUntil();
        $sql = "INSERT INTO " . self::DBTABLE . " (name,email,password,token,token_valid_until) VALUES (?, ?, ?, '$token', '$date')";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        $result = $stmt->execute();
        if (!$result) {
            echo "Hiba történt!";
            return false;
        }
        return true;
    }

    function truncateUsersTable()
    {
        $result = $this->mysqli->query("TRUNCATE TABLE " . self::DBTABLE);
        return $result;
    }

    function deleteUsers()
    {
        $result = $this->mysqli->query("DROP TABLE " . self::DBTABLE);
        return $result;
    }

    /*public function getUserByEmail($email) {
        $query = "SELECT id, warehouses.name AS warehouse_name FROM shelves INNER JOIN warehouses ON shelves.warehouse_id = warehouses.id WHERE shelves.warehouse_id = ?";
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
    }*/
}