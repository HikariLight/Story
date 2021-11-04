<?php

require_once("model/Post.php");
require_once("Router.php");

    class View{
        protected $title;
        protected $content;
        protected $style;
        protected $router;

        public function __construct(Router $router){
            $this->router = $router;
            $this->style = "";
            $this->title = null;
            $this->content = null;
        }

        public function makeHomePage(){
            $this->title = "Home";

            $this->style = "body{text-align: center}";

            $this->content = "
            <h1 class='title'>Tell a Story.</h1>
            <button class='coloredBackgroundButton'>Sign up</button>
            <button class='coloredTextButton'>Login</button>
            ";
        }

        public function makeAboutPage(){
            $this->title = "About";

            $this->style = "body{text-align: center}";

            $this->content = "
            <h1 class='title'>Project Idea</h1>
            <p>A platform where users can share short stories or jokes.
            The title of each post is the setup for the story or the joke, and the detailed page reveals the punch line.</p>
            
            <h1 class='title'>Group Members</h1>
            <ul>
                <li>MERZOUGUI Dhia Eddine</li>
                <li>MERCIER Julien</li>
            </ul>

            <h1 class='title'>Add-ons Developed</h1>
            <ul>
                <li>Website is responsive</li>
                <li>Website has a search function</li>
                <li>Website has a sorting function</li>
            </ul>
            ";
        }

        public function makeSignUpPage(){
            $this->title = "Sign Up";

            $this->content = "<h1 class='title'>Work in progress</h1>";
        }

        public function makeLoginPage(){
            $this->title = "Login";

            $this->content = "<h1 class='title'>Work in progress</h1>";
        }

        public function allUsersPost(){
            $this->title = "All Posts";

            $this->content = "<h1 class='title'>Work in progress.</h1>";
        }

        public function makePostPage(Post $post){
            $this->title = getPostTitle($post);

            $className = $this->getBorderColor($post);

            $this->content = "
            <article class=$className>
                <p>$post->getTitle();</p>
                <br>
                <p>$post->getBody();</p>
            </article>
            ";
        }

        public function makeProfile(){
            $this->title = "Login";

            $this->content = "<h1 class='title'>Work in progress</h1>";
        }

        public function makeUnknownActionPage(){
            $this->title = "Yo, what?";

            $this->content = "<h1 class='title'>Sorry, G. This wasn't the move.</h1>";
        }

        public function makeUnexpectedErrorPage(){
            $this->title = "Yo, what?";

            $this->content = "<h1 class='title'>Stuff went down bruv, Idk what to tell you.</h1>";
        }

        // Non-Page stuff
        protected function getMenu() {
            return array(
                "Home" => $this->router->homePage(),
                "Browse Posts" => $this->router->allUsersPostPage(),
                "About" => $this->router->aboutPage(),
            );
        }

        public function getPostTitle(Post $post){
            $pieces = explode(" ", $post->getTitle());
            $postTitle = implode(" ", array_splice($pieces, 0, 3));

            return $postTitle . "...";
        }

        public function getBorderColor(Post $post){
            $type = $post->getType();
            $borderColor = "";

            switch($type){
                case "Short story":
                    $borderColor = ".shortStory";
                    break;
                case "Short horror story":
                    $borderColor = ".shortHorrorStory";
                    break;
                case "joke":
                    $borderColor = ".joke";
                    break;
            }

            return $borderColor;
        }

        public function render(){
            if ($this->title === null || $this->content === null) {
                $this->makeUnexpectedErrorPage();
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
