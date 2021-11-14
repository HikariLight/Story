<?php

require_once("model/Post.php");
require_once("View.php");
require_once("Router.php");

    class AuthView extends View{

        public function makeAuthGalleryPage($data){
            $this->title = "Gallery";

            $this->style = "body{text-align: center;}";

            $this->content = "";

            // $this->content .= "<button class='firstCornerButton coloredBackgroundButton'><a href='.?action=newPost'>Post</a></button>";
            
            $this->content .= "
            <div class='posts'>";
            foreach($data as $row){
                $borderColor = $this->getBorderColor($row);
                $this->content .= "<div class='post $borderColor'>".$row->Setup."...</div>";
            }
            $this->content .= "</div>";

            $this->content .= "<button class='coloredBackgroundButton'><a href='.?action=newPost'>Post</a></button>";

        }

        public function makePostPage($data){
            $this->title = getPostTitle($data->Setup);

            $borderColor = $this->getBorderColor($row);

            $this->content = "
            <article class='$borderColor'>
                <p>$data->Setup;</p><br>
                <p>$data->Punchline;</p>
            </article>
            ";
        }

        public function makeCreatePostPage(PostBuilder $builder){
            $this->title = "Create a post";

            $this->content = "<h1 class='title'>Create a Post</h1>";
            $this->content .= '<form action="'.$this->router->saveNewPost().'" method="POST">'."\n";
            $this->content .= self::getPostFormFields($builder);
            $this->content .= "<button class='coloredBackgroundButton'>Post</button>\n";
            $this->content .= "</form>\n";
        }

        public function makeModifyPostPage(){
            $this->title = "Modify";

            $this->content = "<h1 class='title'>Work in progress</h1>";
        }

        public function makePostCreatedPage(){
            $this->title = "Post Created";

            $this->content = "<h1 class='title'>The post was successfully created.</h1>";       
        }

        public function makePosModifiedPage(){
            $this->title = "Post modified";

            $this->content = "<h1 class='title'>The post was successfully modified.</h1>";       
        }

        public function makePostDeletedPage(){
            $this->title = "Post deleted";

            $this->content = "<h1 class='title'>The post was successfully deleted.</h1>";
        }

        // REMEMBER: Put back $data as a parameter
        public function makeProfilePage(){
            $this->title = "My Profile";

            $this->content = "";

            // $this->content .= "<div class='posts'>";
            // foreach($data as $row){
            //     $borderColor = $this->getBorderColor($row);
            //     $this->content .= "<div class='post $borderColor'>".$row->Setup."...</div>";
            // }
            // $this->content .= "</div>";

            $this->content .= "<button class='coloredBackgroundColor'><a href=''>Log Out</a></button>";
        }

        // ------------ Non-Page stuff ------------

        protected function getMenu() {
            return array(
                "Profile" => $this->router->profilePage(),
                "Browse Posts" => $this->router->galleryPage(),
                "About" => $this->router->aboutPage(),
            );
        }

        public function getPostTitle($setup){
            $pieces = explode(" ", $setup);
            $postTitle = implode(" ", array_splice($pieces, 0, 3));

            return $postTitle . "...";
        }

        protected function getPostFormFields(PostBuilder $builder) {
            $setupRef = $builder->getSetupRef();
            $s = "";
    
            $s .= '<p><label>Setup: <input type="text" name="'.$setupRef.'" value="';
            $s .= self::htmlesc($builder->getData($setupRef));
            $s .= "\" />";
            $err = $builder->getErrors($setupRef);
            if ($err !== null)
                $s .= ' <span class="error">'.$err.'</span>';
            $s .="</label></p>\n";
    
            $punchlineRef = $builder->getPunchlineRef();
            $s .= '<p><label>Punchline: <input type="text" name="'.$punchlineRef.'" value="';
            $s .= self::htmlesc($builder->getData($punchlineRef));
            $s .= '" ';
            $s .= '	/>';
            $err = $builder->getErrors($punchlineRef);
            if ($err !== null)
                $s .= ' <span class="error">'.$err.'</span>';
            $s .= '</label></p>'."\n";
            return $s;
        }

        public function render(){
            if ($this->title === null || $this->content === null) {
                $this->makeErrorPage("AuthView Error");
            }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="skin/style.css">

    <style>
        <?php echo $this->style; ?>
    </style>

    <title>
        <?php echo $this->title; ?>
    </title>
</head>
<body>

    <nav>
        <ul>
            <?php
                foreach ($this->getMenu() as $text => $link) {
                    echo "<li><a href=\"$link\">$text</a></li>";
                }
            ?>
        </ul>
    </nav>

    <main>
        <?php echo $this->content; ?>
    </main>

    <footer>
        <p>Groupe 3. All Rights Reserved.</p>
    </footer>
    
</body>
</html>

<?php 
    // Closing off the class and render() function
    }
}
?>