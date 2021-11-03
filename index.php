<?php 

set_include_path("./src");

require_once("model/PostStorageFile.php");
require_once("Router.php");

# $r = new Router(new PostStorageFile($_SERVER['TMPDIR'].'/post_db.txt'));
$r = new Router(new PostStorageFile($_SERVER['DOCUMENT_ROOT'].'/post_db.txt'));
$r->main();

?>