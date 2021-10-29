<?php 

set_include_path("./src");

require_once("model/PostStorageFile.php");
require_once("Routeur.php");

$r = new Routeur(new PostStorageFile($_SERVER['TMPDIR'].'/post_db.txt'));
$r->main();

?>