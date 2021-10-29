<?php 

require_once("model/PostStorage.php");
require_once("view/View.php");
require_once("ctl/Controller.php");

class Router {
    
    public function __construct(PostStorage $postDB) {
        $this->postDB = $postDB;
    }

    public function main() {
        $view = new View($this);
        $ctl = new Controller($view, $this->postDB);
        // [...]
    }
}

?>