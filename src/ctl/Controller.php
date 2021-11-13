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
        protected $postDB;
        protected $accountDB;
        protected $postBuilder;
        protected $accountBuilder;

        public function __construct(View $view, PostStorageDB $postDB, AccountStorageDB $accountDB){
            $this->view = $view;
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

        public function createPost(){}

        public function newAccount(){
            if ($this->accountBuilder === null) {
                $this->accountBuilder = new AccountBuilder();
            }
            $this->view->makeSignUpPage($this->accountBuilder);
        }

        public function createAccount(array $data){
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