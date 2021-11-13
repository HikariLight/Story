<?php

    require_once("model/Post.php");
    require_once("model/Account.php");

    require_once("model/PostBuilder.php");
    require_once("model/AccountBuilder.php");

    require_once("model/PostStorageDB.php");
    require_once("model/AccountStorageDB.php");
    
    require_once("view/View.php");
    require_once("view/AuthView.php");

    class Controller{
        protected $view;
        protected $authView;
        protected $postDB;
        protected $accountDB;
        protected $postBuilder;
        protected $accountBuilder;

        public function __construct(View $view, AuthView $authView, PostStorageDB $postDB, AccountStorageDB $accountDB){
            $this->view = $view;
            $this->authView = $authView;
            $this->postDB = $postDB;
            $this->accountDB = $accountDB;

            $this->postBuilder = key_exists('postBuilder', $_SESSION) ? $_SESSION['postBuilder'] : null;
            $this->accountBuilder = key_exists('accountBuilder', $_SESSION) ? $_SESSION['accountBuilder'] : null;

        }

        public function __destruct(){
            $_SESSION['accountBuilder'] = $this->accountBuilder;
		    $_SESSION['postBuilder'] = $this->postBuilder;
        }

        public function postPage($id){
            $post = $this->postDB->read($id);
            if ($post === null) {
                $this->view->makeUnknownActionPage();
            } else {
                $this->view->makePostPage($post);
            }
        }

        public function galleryPage(){
            $data = $this->postDB->readAll();
            $this->view->makeGalleryPage($data);
        }

        public function newPost(){
            if ($this->postBuilder === null) {
                $this->postBuilder = new PostBuilder();
            }
            $this->authView->makeCreatePostPage($this->postBuilder);
        }

        public function saveNewPost(array $data){
            $this->postBuilder = new PostBuilder($data);

            echo "The other how you doing";
            if ($this->postBuilder->isValid()) {
                $post = $this->postBuilder->createPost();
                $postId = $this->postDB->create($post);
                $this->postBuilder = null;
                $this->authView->makePostCreatedPage();
            } else {
                $this->view->makeErrorPage();
            }
        }        

        public function newAccount(){
            if ($this->accountBuilder === null) {
                $this->accountBuilder = new AccountBuilder();
            }
            $this->view->makeSignUpPage($this->accountBuilder);
        }

        public function saveNewAccount(array $data){
            $this->accountBuilder = new AccountBuilder($data);
            if ($this->accountBuilder->isValid()) {
                $account = $this->accountBuilder->createAccount();
                $accountId = $this->accountDB->create($account);
                $this->AccountBuilder = null;
                $this->view->makeAccountCreatedPage();
            } else {
                $this->view->makeErrorPage();
            }
        }

        public function deletePost($id){}

        public function modifyPost($id){}

    }
?>