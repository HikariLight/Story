<?php


require_once("Account.php");

interface AccountStorage {

    public function create(Account $a);

    public function read($id);

    public function checkAuth($login, $password);

    public function readAll();

    public function update($id, Account $a);

    public function delete($id);
}

?>