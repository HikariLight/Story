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
        $title = $p->getTitle();
        $body = $p->getBody();
        $type = $p->getType();
        $date = $p->getDateCreated();

        $sql = "INSERT INTO `Posts` (`Post_ID`, `User_ID`, `Title`, `Body`, `Type`, `Creation_Date`) VALUES (1, 1, $title, $body, $type, $date)";
        $this->pdo->query($sql);
    }

    public function read($id) {
        $sql = "SELECT * FROM `Posts` WHERE Posts.Post_ID = $id";
        return $this->pdo->query($sql);
    }

    public function readAll() {
        $sql = "SELECT * FROM `Posts`;";
        $data = $this->pdo->query($sql)->fetchAll();
        return $data;
    }

    // public function readUser($id){
    //     echo "This is something".$id;
    // }

    // public function update($id, Post $p) {
    //     if ($this->db->exists($id)) {
    //         $this->db->update($id, $p);
    //         return true;
    //     }
    //     return false;
    // }

    // public function delete($id) {
    //     if ($this->db->exists($id)) {
    //         $this->db->delete($id);
    //         return true;
    //     }
    //     return false;
    // }

    // public function deleteAll() {
    //     $this->db->deleteAll();
    // }
}
?>