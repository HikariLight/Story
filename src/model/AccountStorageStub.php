<?php 

# require_once("DATABASE");
require_once("model/Account.php");
require_once("model/AccountStorage.php");

class AccountStorageStub implements AccountStorage {

    private $db;

/*     public function __construct($file) {
        $this->db = new ObjectFileDB($file); // replace by Database
    } */

    public function create(Account $a) {
        return $this->db->insert($a);
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

    public function update($id, Account $a) {
        if ($this->db->exists($id)) {
            $this->db->update($id, $a);
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

    public function checkAuth($login, $password) {
        if($this->db->exists($login)) {
            if(password_verify($this->db->fetch($login)["password"], $password)) {
                return $this->db->fetch($login);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

}

?>