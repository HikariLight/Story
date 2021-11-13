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
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Users` WHERE Users.User_id = ? )");
        $stmt->bindParam(1, $p->getUserId());
        
        if (!$stmt->execute()) {
            $stmt = $this->pdo->prepare("INSERT INTO `Posts` (`User_id`, `Setup`, `Punchline`, `Type`, `Creation_Date`, `Modification_Date`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $p->getUserId());
            $stmt->bindParam(2, $p->getSetup());
            $stmt->bindParam(3, $p->getPunchline());
            $stmt->bindParam(4, $p->getType());
            $stmt->bindParam(5, $p->getDateCreated());
            $stmt->bindParam(6, $p->getDateModified());
            $stmt->execute();
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    public function read($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ? )");
        $stmt->bindParam(1, $id);

        if (!$stmt->execute()) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $id);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
        $stmt->close();
        return false;
    }

    public function readAll($reverse=true) {
        $stmt = $this->pdo->prepare("SELECT * FROM `Posts` ORDER BY `Creation_Date` DESC");
        if($reverse == false) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` ORDER BY `Creation_Date` ASC");
        }
        $res = $stmt->execute();
        $stmt->close();
        return $res;
    }

    public function readUser($id, $reverse=true) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `User_id` FROM `Posts` WHERE Posts.User_id = ? )");
        $stmt->bindParam(1, $id);

        if (!$stmt->execute()) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.User_id = ? ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.User_id = ? ORDER BY `Creation_Date` ASC");
            }
            $stmt->bindParam(1, $id);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
        $stmt->close();
        return false;
    }

    public function selectType($type, $reverse=true) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Type` FROM `Posts` WHERE Posts.Type = ? )");
        $stmt->bindParam(1, $type);

        if (!$stmt->execute()) {
            $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Type = ? ORDER BY `Creation_Date` DESC");
            if($reverse == false) {
                $stmt = $this->pdo->prepare("SELECT * FROM `Posts` WHERE Posts.Type = ? ORDER BY `Creation_Date` ASC");
            }
            $stmt->bindParam(1, $type);
            $res = $stmt->execute();
            $stmt->close();
            return $res;
        }
        $stmt->close();
        return false;
    }

    public function update($id, Post $p) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ? )");
        $stmt->bindParam(1, $id);

        if (!$stmt->execute()) {
            $stmt = $this->pdo->prepare("UPDATE `Posts` SET `Setup` = ?, `Punchline` = ?, `Type` = ?, `Modification_Date` = ? WHERE Posts.Post_id = ?");
            $stmt->bindParam(1, $p->getSetup());
            $stmt->bindParam(2, $p->getPunchline());
            $stmt->bindParam(3, $p->getType());
            $stmt->bindParam(4, $p->getDateModified());
            $stmt->execute();
            $stmt->close();
            return true;
        }
        $stmt->close();
        return false;
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("SELECT EXISTS ( SELECT `Post_id` FROM `Posts` WHERE Posts.Post_id = ?)");
        $stmt->bindParam(1, $id);
        if ($stmt->execute()) {
            $stmt = $this->pdo->prepare("DELETE FROM `Posts` WHERE Posts.Post_id = ?");
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