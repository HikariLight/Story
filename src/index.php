<?php 

set_include_path("./src");

require_once("model/PostStorageDB.php");
require_once("Router.php");

$r = new Router(new PostStorageDB());
$r->main();

?>