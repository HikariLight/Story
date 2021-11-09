<?php

    require_once("model/Post.php");
    require_once("model/PostStorageDB.php");
    require_once("view/View.php");

    class Controller{
        protected $view;
        protected $postdb;

        public function __consutrct(View $view, PostStorage $postdb){
            $this->view = $view;
            $this->postdb = $postdb;
        }

        public function postPage($id){
            $post = $this->postdb->read($id);
            if ($post === null) {
                $this->view->makeUnknownActionPage();
            } else {
                $this->view->makePostPage($post);
            }
        }

        public function allPostsPage(){}
    
        public function createPost(){}

        public function deletePost($id){}

        public function modifyPost($id){}

    }
?>