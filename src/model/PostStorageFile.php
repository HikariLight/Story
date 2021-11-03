<?php 

require_once("lib/ObjectFileDB.php"); // replace by Database
require_once("model/Post.php");
require_once("model/PostStorage.php");

class PostStorageFile implements PostStorage {

    private $db;

    public function __construct($file) {
        $this->db = new ObjectFileDB($file); // replace by Database
    }

    public function create(Post $p) {
        return $this->db->insert($p);
    }

    public function read($id) {
        if ($this->db->exists($id)) {
            return $this->db->fetch($id);
        } else {
            return null;
        }
    }

    public function readAll() {
        return $this->db->fetchAll();
    }

    public function update($id, Post $p) {
        if ($this->db->exists($id)) {
            $this->db->update($id, $p);
            return true;
        }
        return false;
    }

    public function delete($id) {
        if ($this->db->exists($id)) {
            $this->db->delete($id);
            return true;
        }
        return false;
    }

    public function deleteAll() {
        $this->db->deleteAll();
    }

}

?>