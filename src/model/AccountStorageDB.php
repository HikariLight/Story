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
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
        $login = $a->getLogin();
        $password = $a->getPassword();
        $date = $a->getDateCreated();

        $stmt->bindParam(1, $login);
        $stmt->execute();
        if (!$stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("INSERT INTO `Users` (`Username`, `Password`, `Creation_Date`) VALUES (?, ?, ?)");
            $stmt->bindParam(1, $login);
            $stmt->bindParam(2, $password);
            $stmt->bindParam(3, $date);
            $stmt->execute();
            // $stmt->close();
            return true;
        }
        // $stmt->close();
        return false;
    }

    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Users` WHERE Users.User_id = ?)");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            $stmt = $this->pdo->prepare("SELECT `User_id`,`Username`,`Creation_Date` FROM `Users` WHERE Users.User_id = ?");
            $stmt->bindParam(1,$id);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
        $stmt->close();
        return null;
    }

    public function checkAuth($login, $password) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
        $stmt->bindParam(1, $login);
        if($stmt->execute()) {
            $stmt = $this->pdo->prepare("SELECT `Password` FROM `Users` WHERE Users.Username = ?");
            $stmt->bindParam(1, $login);
            if(password_verify($stmt->execute(), $password)) {
                $stmt->close();
                return true;
            }
            $stmt->close();
            return false;
        }
        $stmt->close();
        return null;
    }

    public function update($id, Account $a) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.User_id = ?)");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            $stmt = $this->pdo->prepare("UPDATE `Users` SET `Password` = ? WHERE Users.User_id = ?");
            $stmt->bindParam(1, $a->getPassword());
            $stmt->bindParam(2, $id);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.User_id = ?)");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            $stmt = $this->pdo->prepare("DELETE FROM `Users` WHERE Users.User_id = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

}

?>