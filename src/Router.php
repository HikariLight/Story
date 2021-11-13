<?php 

require_once("model/PostStorage.php");
require_once("view/View.php");
require_once("ctl/Controller.php");

class Router {
    
    public function __construct(PostStorage $postDB) {
        $this->postDB = $postDB;
    }

    // Main function
    public function main() {
        $view = new View($this);
        $controller = new Controller($view, $this->postDB);

        $postId = key_exists('post', $_GET) ? $_GET['post'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        if ($action === null) {
            $action = ($postId === null) ? 'home' : 'showPost';
        }

        try {
            switch ($action) {
                case 'showPost' : 
                    if ($postId === null) {
                        $view->makeUnknowActionPage();
                    } else {
                        $controller->postPage($postId);
                    }
                    break;

                case 'home' : 
                    $view->makeHomePage();
                    break;
                
                case 'gallery' :
                    $controller->galleryPage();
                    break;
                
                case 'about' : 
                    $view->makeAboutPage();
                    break; 

                case 'createPost' :
                    $controller->newPost();
                    break;

                case 'deletePost' : 
                    if ($postId == null) {
                        $view->makeUnknownActionPage();
                    } else {
                        $controller->deletePost($postId);
                    }
                    break;

                case 'modifyPost' : 
                    if ($postId == null) {
                        $view->makeUnknownActionPage();
                    } else {
                        $controller->modifyPost($postId);
                    }
                    break;

                case 'createAccount' : 
                    $view->makeLoginFormPage();
                    break;

                case 'login' : 
                    $view->makeLoginPage();
                    break;
                
                case 'signup':
                    $view->makeSignUpPage();
                    break;

                default : 
                    $view->makeUnknownActionPage();
                    break;
            }
        } catch (Exception $e) {
            $view->makeUnexpectedErrorPage($e);
        }

        $view->render();
    }

    // URL Methods
    public function homePage() {
        return ".";
    }

    public function aboutPage() {
        return ".?action=about";
    }

    public function galleryPage() {
        return ".?action=gallery";
    }

    public function postPage($id) {
        return ".?post=$id";
    }

    public function createPostPage() {
        return ".?action=createPost";
    }

    public function modifyPostPage($id) {
        return ".?post=$id&amp;action=modifyPost";
    }

    public function deletePostPage($id) {
        return ".?post=$id&amp;action=deletePost";
    }
}

?>