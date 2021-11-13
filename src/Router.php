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
        $controller = new Controller($view, $this->postDB, $this->accountDB);

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

                case 'createPost':
                    $controller->createPost($_POST);
                    break;

                case 'createAccount':
                    $controller->newAccount();
                    break;
                
                case 'saveAccount':
                    $accountID = $controller->createAccount($_POST);
                    break;     
                
                case 'deletePost': 
                    if ($postId == null) {
                        $view->makeUnknownActionPage();
                    } else {
                        $controller->deletePost($postId);
                    }
                    break;

                case 'modifyPost': 
                    if ($postId == null) {
                        $view->makeUnknownActionPage();
                    } else {
                        $controller->modifyPost($postId);
                    }
                    break;

                case 'login': 
                    $view->makeLoginPage();
                    break;
                
                case 'signup':
                    $view->makeSignUpPage();
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

    public function postPage($id) {
        return ".?post=$id";
    }

    public function createPostPage() {
        return ".?action=createPost";
    }

    public function createNewAccount(){
        return ".?action=createAccount";
    }

    public function saveNewAccount(){
        return ".?action=saveAccount";
    }

    public function modifyPostPage($id) {
        return ".?post=$id&amp;action=modifyPost";
    }

    public function deletePostPage($id) {
        return ".?post=$id&amp;action=deletePost";
    }
}

?>