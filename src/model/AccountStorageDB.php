<?php 

require_once("model/Account.php");
require_once("model/AccountStorage.php");

class AccountStorageDB implements AccountStorage {

    protected $db = "story";
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $pdo;

    public function __construct() {
        $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db;
        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }
        catch (PDOException $e) {
            echo "Nope, something happened. " . $e;
        }
    }

    public function create(Account $a) {
        $username = $a->getLogin();
        $password = $a->getPassword();
        $creationDate = $a->getDateCreated();
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        if (!$stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("INSERT INTO `Users` (`Username`, `Password`, `Creation_Date`) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $username);
            $stmt->bindParam(2, $password);
            $stmt->bindParam(3, $creationDate);
            if($stmt->execute()) {
                // $stmt->close();
                return true;
            }
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Users` WHERE Users.User_id = ?)");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("SELECT `User_id`,`Username`,`Creation_Date` FROM `Users` WHERE Users.User_id = ?");
            $stmt->bindParam(1, $id);
            if($stmt->execute()) {
                // $stmt->close();
                return $stmt->fetchAll();
            }
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function checkAuth($login, $password) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
        $stmt->bindParam(1, $login);
        $stmt->execute();
        if($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("SELECT `Password` FROM `Users` WHERE Users.Username = ?");
            $stmt->bindParam(1, $login);
            $stmt->execute();
            if(password_verify($stmt->fetchColumn(), $password)) {
                // $stmt->close();
                return true;
            }
            // $stmt->close();
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function update($id, Account $a) {
        $password = $a->getPassword();
        
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.User_id = ?)");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("UPDATE `Users` SET `Password` = ? WHERE Users.User_id = ?");
            $stmt->bindParam(1, $password);
            $stmt->bindParam(2, $id);
            if($stmt->execute()) {
                // $stmt->close();
                return true;
            }
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.User_id = ?)");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("DELETE FROM `Users` WHERE Users.User_id = ?");
            $stmt->bindParam(1, $id);
            if($stmt->execute()) {
                // $stmt->close();
                return true;
            }
            return null;
        }
        // $stmt->close();
        return null;
    }

}

?>