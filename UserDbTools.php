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

    function createUsers($name, $email, $password, $privilege)
    {
        $token = $this->getNewToken();
        $date = $this->getValidUntil();
        $sql = "INSERT INTO " . self::DBTABLE . " (name,email,password,token,token_valid_until,privilege) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssssss", $name, $email, $password, $token, $date, $privilege);
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

    function getUserByEmail($email)
    {
       
            $query = "SELECT token FROM " . self::DBTABLE . " WHERE email = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($token);
            $stmt->fetch();
            $stmt->close();
            return $token;
        
    }

    public function updateUsers($registrationDate, $token)
    {
        $sql = "UPDATE users SET registration_date=?, is_active=true WHERE token=?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $registrationDate, $token);
        $stmt->execute();
    }

    function getUserPasswordByEmail($email)
    {
        $query = "SELECT users.password FROM users WHERE email = ?";
        $stmt = $this->mysqli->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $login = $result->fetch_assoc();
        $stmt->close();
        $password = '';
        if(!empty($login['password']))
        {
            $password = $login['password'];
        }
        return $password;
    }

    function getUserPrivilegeByEmail($email)
    {
        $query = "SELECT privilege FROM " . self::DBTABLE . " WHERE email = ?";
            $stmt = $this->mysqli->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($privilege);
            $stmt->fetch();
            $stmt->close();
            return $privilege;
    }

}