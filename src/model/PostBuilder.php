<?php 

require_once("model/Post.php");

class PostBuilder {

    protected $data;
	protected $errors;
    
	public function __construct($data=null) {
		if ($data === null) {
			$data = array(
				"title" => "",
				"body" => "",
                "type" => "",
			);
		}
		$this->data = $data;
		$this->errors = array();
	}

    public static function buildFromPost(Post $post) {
		return new PostBuilder(array(
			"title" => $post->getTitle(),
			"body" => $post->getBody(),
            "type" => $post->getType(),
		));
	}

    public function isValid() {
		$this->errors = array();
        // title
		if (!key_exists("title", $this->data) || $this->data["title"] === "") {
            $this->errors["title"] = "Vous devez entrer un titre";
        }
		else if (mb_strlen($this->data["title"], 'UTF-8') >= 30) {
            $this->errors["title"] = "Le titre doit faire moins de 30 caractères";
        }
        // body
        if (!key_exists("body", $this->data) || $this->data["body"] === "") {
            $this->errors["body"] = "Vous devez entrer une histoire";
        }
		else if (mb_strlen($this->data["body"], 'UTF-8') >= 2500) {
            $this->errors["title"] = "Le titre doit faire moins de 2500 caractères";
        }
        // type
		if (!key_exists("type", $this->data) || $this->data["type"] === "") {
            $this->errors["type"] = "Vous devez entrer un type";
        }
		else if ($this->data["type"] === "story" || $this->data["type"] === "horror story" || $this->data["type"] === "joke") {
            $this->errors["type"] = "Vous devez choisir un type dans la liste";
        }
		return count($this->errors) === 0;
	}

    public function getTitleRef() {
		return "title";
	}

	public function getBodyRef() {
		return "body";
	}

    public function getTypeRef() {
        return "type";
    }

	public function getData($ref) {
		return key_exists($ref, $this->data) ? $this->data[$ref] : '';
	}

	public function getErrors($ref) { // Call isValid() before using it
		return key_exists($ref, $this->errors )? $this->errors[$ref] : null;
	}

	public function createPost() {
		if (!key_exists("title", $this->data) || !key_exists("body", $this->data) || !key_exists("type", $this->data)) {
            throw new Exception("Missing fields for post creation");
        }
		return new Post($this->data["title"], $this->data["body"], $this->data["type"]);
	}

	public function updatePost(Post $post) {
		if (key_exists("title", $this->data)) {
            $post->setTitle($this->data["title"]);
        }
		if (key_exists("body", $this->data)) {
            $post->setBody($this->data["body"]);
        }
        if (key_exists("type", $this->data)) {
            $post->setType($this->data["type"]);
        }
	}

}

?>