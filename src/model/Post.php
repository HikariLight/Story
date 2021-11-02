<?php

/* TODO
 * Add title validation (max length ? any specific caractere ?  not empty)
 * Add Type validation (corresponds to one of the two(or more) types)
 * Add Body validation (not empty, max length ? any specific caractere ? any url ?)
 * Add thoses validation to construct and setters
 */

class Post {

    protected $title;
    protected $body;
    protected $type;
    protected $dateCreated;
    protected $dateModified;

    public function __contrust($title, $body, $type, $dateCreated = null, $dateModified = null) {
        $this->title = $title;
        $this->body = $body;
        $this->type = $type;
        $this->dateCreated = $dateCreated !== null ? $dateCreated : new DateTime();
        $this->dateModified = $dateModified !== null ? $dateModified : new DateTime();
    }

    // Getters
    public function getTitle() {
        return $this->title;
    }

    public function getBody() {
        return $this->body;
    }

    public function getType() {
        return $this->type;
    }

    public function getDateCreated() {
        return $this->dateCreated;
    }

    public function getDateModified() {
        return $this->dateModified;
    }

    // Setters
    public function setTitle($title) {
        $this->title = $title;
        $this->dateModified = new DateTime();
    }

    public function setType($type) {
        $this->type = $type;
        $this->dateModified = new DateTime();
    }

    public function setBody($body) {
        $this->body = $body;
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