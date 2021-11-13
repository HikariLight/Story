<?php

    require_once("model/Post.php");
    require_once("model/PostBuilder.php");
    require_once("model/PostStorageDB.php");
    require_once("view/View.php");

    class Controller{
        protected $view;
        protected $postDB;

        public function __construct(View $view, PostStorageDB $postDB){
            $this->view = $view;
            $this->postDB = $postDB;
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

        public function deletePost($id){}

        public function modifyPost($id){}

    }
?>