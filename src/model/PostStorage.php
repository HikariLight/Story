<?php

/*
 *
 */

require_once("Post.php");

interface PostStorage {

    public function create(Post $p);

    public function read($id);

    // public function readUser($id); return all the post of one single dude ?

    public function readAll();

    public function update($id, $p);

    public function detele($id);

    public function deleteAll(); // really need to include this ? someone can delete everyting

}

?>