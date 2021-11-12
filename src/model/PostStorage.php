<?php

require_once("Post.php");

interface PostStorage {
    public function create(Post $p);

    public function read($id);

    // public function readUser($id); 

    public function readAll();

    // public function update($id, Post $p);

    // public function delete($id);

    // public function deleteAll(); 

}
?>