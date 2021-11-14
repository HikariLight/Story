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

            $this->content = "
            <h1 class='title'>Tell a Story.</h1>";
            $this->content .= "<button class='coloredBackgroundButton'><a href='.?action=newAccount'>Sign up</a></button>";
            $this->content .= "<button class='coloredTextButton'><a href='.?action=loginPage'>Login</a></button>";
            // $this->content .= "<button class='coloredBackgroundButton'><a href='.?action=newPost'>New Post</a></button>";
        }

        public function makeAboutPage(){
            $this->title = "About";

            // $this->style = "body{text-align: center}";

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

        public function makeSignUpPage(AccountBuilder $builder){
            $this->title = "Sign Up";

            $this->content = "<h1 class='title'>Sign Up</h1>";
            $this->content .= '<form action="'.$this->router->saveNewAccount().'" method="POST">'."\n";
            $this->content .= self::getAccountFormFields($builder);
            $this->content .= "<button class='coloredBackgroundButton'>Sign Up</button>\n";
            $this->content .= "</form>\n";
        }

        public function makeLoginPage(){
            $this->title = "Login";

            $this->content = "<h1 class='title'>Login</h1>";
            $this->content .= "<form action='".$this->router->login()."' method='POST'>";
            $this->content .= "Username: <input type='text' name='username'>";
            $this->content .= "Password: <input type='password' name='password'>";
            $this->content .= "<input class='coloredBackgroundButton' type='submit' name='submit' value='Submit'>";
            $this->content .= "</form>";
        }

        public function makeGalleryPage($data){
            $this->title = "Gallery";

            $this->content = "";

            $this->content .= "
            <div class='posts'>";
            foreach($data as $row){
                $borderColor = $this->getBorderColor($row);
                $this->content .= "<div class='post $borderColor'>".$row->Setup."..."."<button class='readMoreButton'><a href='.?action=unauthenticated'>Read more</a></button></div>";
            }
            $this->content .= "</div>";
        }

        public function makeAccountCreatedPage(){
            $this->title = "Welcome!";

            $this->content = "<h1 class='title'>Account Created. Welcome to the website!</h1>";
        }

        public function makeUnauthenticatedPage(){
            $this->title = "Unauthenticated";

            $this->content = "<h1 class='title'>Unauthenticated.</h1>";
            $this->content .= "<p>Only authenticated people may read the punchline of the stories. Please sign up or login to see the rest.</p>";
            $this->content .= "<button class='coloredBackgroundButton'><a href='.?action=login'>Login</a></button>";
            $this->content .= "<button class='coloredTextButton'><a href='.?action=newAccount'>Sign Up</a></button>";

        }

        public function makeErrorPage($errorLocation=""){
            $this->title = "Yo, what?";

            // $this->style = "body{text-align: center}";

            $this->content = "<h1 class='title'>Stuff went down bruv, Idk what to tell you.</h1><br>";
            $this->content .= "<p>".$errorLocation."</p>";
        }

        // ------------ Non-Page stuff ------------
        protected function getMenu() {
            return array(
                "Home" => $this->router->homePage(),
                "Browse Posts" => $this->router->galleryPage(),
                "About" => $this->router->aboutPage(),
            );
        }

        public function getBorderColor($row){
            $type = $row->Type;
            $borderColor = "";

            switch($type){
                case "Short story":
                    $borderColor = "shortStory";
                    break;
                case "Short horror story":
                    $borderColor = "shortHorrorStory";
                    break;
                case "Joke":
                    $borderColor = "joke";
                    break;
            }

            return $borderColor;
        }

        protected function getAccountFormFields(AccountBuilder $builder) {
            $loginRef = $builder->getLoginRef();
            $s = "";
    
            $s .= '<p><label>Username: <input type="text" name="'.$loginRef.'" value="" required>';
            $err = $builder->getErrors($loginRef);
            if ($err !== null)
                $s .= ' <span class="error">'.$err.'</span>';
            $s .="</label></p>\n";
    
            $passwordRef = $builder->getpasswordRef();
            $s .= '<p><label>Password: <input type="password" name="'.$passwordRef.'" value="" required>';
            $err = $builder->getErrors($passwordRef);
            if ($err !== null)
                $s .= ' <span class="error">'.$err.'</span>';
            $s .= '</label></p>'."\n";
            return $s;
        }

        public static function htmlesc($str) {
            return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE| ENT_HTML5, 'UTF-8');
        }

        public function render(){
            if ($this->title === null || $this->content === null) {
                $this->makeErrorPage("View Empty Error");
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