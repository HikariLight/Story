<?php 

/*
 * Where the password hash is located ?
 */

class Account {

    protected $login;
    protected $password;
    protected $dateCreated;
    protected $status;

    public function __contrust($login, $password, $dateCreated = null, $status = "user") {
        $this->login = $login;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->dateCreated = $dateCreated !== null ? $dateCreated : new DateTime();
        $this->status = $status;
    }

    // Getters
    public function getLogin() {
        return $this->login;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getStatus() {
        return $this->status;
    }

    // Setters
    public function setLogin($login) {
        $this->login = $login;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    // Methods

    //isValid methods
}

?>