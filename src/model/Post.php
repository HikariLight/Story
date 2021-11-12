<?php

class Post {

    protected $setup;
    protected $punchline;
    protected $type;
    protected $dateCreated;
    protected $dateModified;
    protected $userID;

    public function __contrust($setup, $punchline, $type, $userID, $dateCreated = null, $dateModified = null) {
        $this->setup = $setup;
        $this->punchline = $punchline;
        $this->type = $type;
        $this->userID = $userID;
        $this->dateCreated = $dateCreated !== null ? $dateCreated : new DateTime();
        $this->dateModified = $dateModified !== null ? $dateModified : new DateTime();
    }

    // Getters
    public function getSetup() {
        return $this->setup;
    }

    public function getPunchline() {
        return $this->punchline;
    }

    public function getType() {
        return $this->type;
    }

    public function getUserId() {
        return $this->UserID;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getDateModified() {
        return $this->dateModified;
    }

    // Setters
    public function setSetup($setup) {
        $this->setup = $setup;
        $this->dateModified = new DateTime();
    }

    public function setType($type) {
        $this->type = $type;
        $this->dateModified = new DateTime();
    }

    public function setPunchline($punchline) {
        $this->punchline = $punchline;
        $this->dateModified = new DateTime();
    }

    // Methods
/*     public function isTitleValid($title) {

    }

    public function isTypeValid($title) {
        
    }

    public function isBodyValid($title) {
        
    } */

}

?>