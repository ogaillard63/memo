<?php
/**
* @project		memo_info
* @author		Olivier Gaillard
* @version		1.0 du 16/02/2017
* @desc			Objet memo
*/

class Memo {

	public $id;
	public $title;
	public $comment;
	public $tags;
	public $doc_link;
	public $last_update;


	public function __construct(array $data) {
		$this->hydrate($data);
	}

	public function hydrate(array $data){
		foreach ($data as $key => $value) {
			if (strpos($key, "_") !== false) {
				$method = 'set';
				foreach (explode("_", $key) as $part) {
					$method .= ucfirst($part);
				}
			}
			else $method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
	}

	/* --- Getters et Setters --- */


	// id;
	public function setId($id) {
		$this->id = (integer)$id;
	}
	public function getId() {
		return $this->id;
	}
	// title;
	public function setTitle($title) {
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	// comment;
	public function setComment($comment) {
		$this->comment = $comment;
	}
	public function getComment() {
		return $this->comment;
	}
	// tags;
	public function setTags($tags) {
		$this->tags = $tags;
	}
	public function getTags() {
		return $this->tags;
	}
	// doc_link;
	public function setDocLink($doc_link) {
		$this->doc_link = $doc_link;
	}
	public function getDocLink() {
		return $this->doc_link;
	}
    // last_update;
	public function setLastUpdate($last_update) {
		$this->last_update = $last_update;
	}
	public function getLastUpdate() {
		return $this->last_update;
	}
}
?>
