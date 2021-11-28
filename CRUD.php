<?php

namespace sd;
require_once 'DB.php';

use PDOException;

class CRUD {
	protected $pdo;

	public function __construct() {
		$this->pdo = DB::getPdo();
	}

	public function create() {
		$message       = 'Post created';
		$data          = [];
		$title         = $_REQUEST['title'];
		$title         = filter_input( INPUT_GET, 'title', FILTER_SANITIZE_STRING );
		$data['title'] = $title;
		try {
			$this->pdo->prepare( "INSERT INTO `posts`(`title`) VALUES(:title)" )->execute( $data );
		}
		catch ( PDOException $e ) {
			$message = $e->getMessage();
		}

		return $message;
	}
}