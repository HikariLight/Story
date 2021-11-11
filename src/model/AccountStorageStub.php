<?php 

require_once("model/Account.php");
require_once("model/AccountStorage.php");

class AccountStorageStub implements AccountStorage {

    protected $db = "story";
    protected $host = "localhost";
    protected $user = "root";
    protected $password = "";
    protected $pdo;


    public function __construct($file) {
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
        $login = $a->getLogin();
        $password = $a->getPassword();
        $status = $a->getStatus();
        $date = $a->getDateCreated();

        $exist = "SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = $login )";
        if (!$this->pdo->query($exist)) {
            // need to insert user_id ? not generated automatically ?
            $sql = "INSERT INTO `Users` (`Username`, `Password`, `Status`, `Registry_Date`) VALUES ($login, $password, $status, $date)";
            $this->pdo->query($sql);
            return true;
        }
        return false;
        
        
    }

    public function read($id) {
        $exist = "SELECT EXISTS ( SELECT * FROM `Users` WHERE Users.User_ID = $id )";
        if ($this->pdo->query($exist)) {
            $sql = "SELECT * FROM `Users` WHERE Users.User_ID = $id";
            return $this->pdo->query($sql);
        } else {
            return null;
        }
    }

    public function checkAuth($login, $password) {
        $exist = "SELECT EXISTS ( SELECT * FROM `Users` WHERE Users.Username = $login )";
        if($this->pdo->query($exist)) {
            $isCorrect = "SELECT `Password` FROM `Users` WHERE Users.Username = $login";
            if(password_verify($this->pdo->query($isCorrect), $password)) {
                $sql = "SELECT * FROM `Users` WHERE Users.Username = $login";
                $this->pdo->query($sql);
                return true;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public function update($id, Account $a) {
        $exist = "SELECT EXISTS ( SELECT * FROM `Users` WHERE Users.User_ID = $id )";
        if ($this->pdo->query($exist)) {
            $pwd = $a->getPassword();
            $sql = "UPDATE `Users` SET `Password` = $pwd WHERE Users.User_ID = $id";
            $this->pdo->query($sql);
            return true;
        }
        return false;
    }

    public function delete($id) {
        $exist = "SELECT EXISTS ( SELECT * FROM `Users` WHERE Users.User_ID = $id )";
        if ($this->pdo->query($exist)) {
            $sql = "DELETE FROM `Users` WHERE Users.User_ID = $id";
            $this->pdo->query($sql);
            return true;
        }
        return false;
    }



}

?>