<?php 

require_once("model/Post.php");
require_once("model/PostStorage.php");


class PostStorageDB implements PostStorage {

    // For XAMPP
    protected $host = "localhost";
    protected $db = "story";
    protected $user = "root";
    protected $password = "";

    // For Personal Server
    // protected $host = "mysql.info.unicaen.fr";
    // protected $db = "NUMETU_bd";
    // protected $user = "NUMETU";
    // protected $password = "";

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

    public function create(Post $p) {
        $user_id = $p->getUserId();
        $setup = $p->getSetup();
        $punchline = $p->getPunchline();
        $type = $p->getType();
        $creationDate = $p->getDateCreated();
        $modificationDate = $p->getDateModified();

        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Users` WHERE Users.User_id = ? )");
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("INSERT INTO `Posts` (`User_id`, `Setup`, `Punchline`, `Type`, `Creation_Date`, `Modification_Date`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $setup);
            $stmt->bindParam(3, $punchline);
            $stmt->bindParam(4, $type);
            $stmt->bindParam(5, $creationDate);
            $stmt->bindParam(6, $modificationDate);
            if($stmt->execute()) {
                //$stmt->close();
                return true;
            }
            return null;
        }
        //$stmt->close();
        return null;
    }

    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ? )");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $id);
            if($stmt->execute()) {
                return $stmt->fetchAll();
            }
            //$stmt->close();
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function readAll($reverse=true) {
        $stmt = $this->pdo->prepare("SELECT * FROM `Posts` ORDER BY `Creation_Date` DESC");
        if($reverse == false) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` ORDER BY `Creation_Date` ASC");
        }
        if($stmt->execute()){
            // $stmt->close();
            return $stmt->fetchAll();
        }
        return null;
    }

    public function readUser($username, $reverse=true) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Username` FROM `Users` WHERE Users.Username = ? )");
        $stmt->bindParam(1, $username);
        $stmt->execute();
        if($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.User_id IN ( SELECT User_id FROM `Users` WHERE Users.Username = ?) ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.User_id IN ( SELECT User_id FROM `Users` WHERE Users.Username = ?) ORDER BY `Creation_Date` ASC");
            }
            $stmt->bindParam(1, $username);
            if($stmt->execute()) {
                // $stmt->close();
                return $stmt->fetchAll();
            }
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function selectType($type, $reverse=true) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Type` FROM `Posts` WHERE Posts.Type = ? )");
        $stmt->bindParam(1, $type);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Type = ? ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Type = ? ORDER BY `Creation_Date` ASC");
            }
            $stmt->bindParam(1, $type);
            if($stmt->execute()){
                // $stmt->close();
                return $stmt->fetchAll();
            }
            return null;
        }
        // $stmt->close();
        return null;
    }

    public function update($id, Post $p) {
        $setup = $p->getSetup();
        $punchline = $p->getPunchline();
        $type = $p->getType();
        $modificationDate = $p->getDateModified();

        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ? )");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("UPDATE `Posts` SET `Setup` = ?, `Punchline` = ?, `Type` = ?, `Modification_Date` = ? WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $setup);
            $stmt->bindParam(2, $punchline);
            $stmt->bindParam(3, $type);
            $stmt->bindParam(4, $modificationDate);
            $stmt->bindParam(5, $id);
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
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ?)");
        $stmt->bindParam(1, $id);
        $stmt->execute();
        if ($stmt->fetchColumn()) {
            $stmt = $this->pdo->prepare("DELETE FROM `Posts` WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $id);
            if($stmt->execute()){
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