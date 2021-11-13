<?php 

require_once("model/PostStorageDB.php");
require_once("model/AccountStorageDB.php");

require_once("view/View.php");
require_once("view/AuthView.php");

require_once("ctl/Controller.php");

class Router{
    
    public function __construct(PostStorage $postDB, AccountStorage $accountDB) {
        $this->postDB = $postDB;
        $this->accountDB = $accountDB;
    }

    // Main function
    public function main(){
        session_start();

        $feedback = key_exists('feedback', $_SESSION) ? $_SESSION['feedback'] : '';
		$_SESSION['feedback'] = '';

        $view = new View($this);
        $authView = new AuthView($this);

        $controller = new Controller($view, $authView, $this->postDB, $this->accountDB);

        $postId = key_exists('post', $_GET) ? $_GET['post'] : null;
        $accounttId = key_exists('account', $_GET) ? $_GET['account'] : null;
        $action = key_exists('action', $_GET) ? $_GET['action'] : null;

        if ($action === null) {
            $action = ($postId === null) ? 'home' : 'showPost';
        }

        try {
            switch ($action) {

                case 'showPost': 
                    if ($postId === null) {
                        $view->makeErrorPage();
                    } else {
                        $controller->postPage($postId);
                    }
                    break;

                case 'home': 
                    $view->makeHomePage();
                    break;
                
                case 'gallery':
                    $controller->galleryPage();
                    break;
                
                case 'about': 
                    $view->makeAboutPage();
                    break; 

                case 'login': 
                    $view->makeLoginPage();
                    break;

                case 'newAccount':
                    $controller->newAccount();
                    break;
                
                case 'saveNewAccount':
                    $accountID = $controller->saveNewAccount($_POST);
                    break; 
                
                case 'newPost':
                    $controller->newPost();
                    break;
                    
                case 'saveNewPost':
                    $postID = $controller->saveNewPost($_POST);
                    break;    

                case 'modifyPost': 
                    if ($postId == null) {
                        $view->makeUnknownActionPage();
                    } else {
                        $controller->modifyPost($postId);
                    }
                    break;
                
                case 'deletePost': 
                    if ($postId == null) {
                        $view->makeUnknownActionPage();
                    } else {
                        $controller->deletePost($postId);
                    }
                    break;

                default : 
                    $view->makeErrorPage();
                    break;
            }
        } catch (Exception $e) {
            $view->makeErrorPage($e);
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

    public function newAccount(){
        return ".?action=newAccount";
    }

    public function saveNewAccount(){
        return ".?action=saveNewAccount";
    }

    public function newPost(){
        return ".?action=newPost";
    }

    public function saveNewPost(){
        return ".?action=saveNewPost";
    }

    public function postPage($id) {
        return ".?post=$id";
    }

    public function modifyPostPage($id) {
        return ".?post=$id&amp;action=modifyPost";
    }

    public function deletePostPage($id) {
        return ".?post=$id&amp;action=deletePost";
    }
}

?>