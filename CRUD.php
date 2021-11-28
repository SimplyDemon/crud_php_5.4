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

	public function index() {
		try {
			$sql = $this->pdo->prepare( "SELECT * FROM `posts`" );
			$sql->execute();

			return $sql->fetchAll();
		}
		catch ( PDOException $e ) {
			return $e->getMessage();
		}
	}

	public function show( $id ) {
		try {
			$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE id = ?" );
			$sql->execute( [ $id ] );

			return $sql->fetch();
		}
		catch ( PDOException $e ) {
			return $e->getMessage();
		}
	}

	public function update( $id, $title, $content ) {
		$data            = [];
		$data['title']   = $title;
		$data['content'] = $content;
		$data['id']      = $id;
		try {
			$sql = $this->pdo->prepare( "UPDATE `posts` SET title=:title, content=:content WHERE id=:id" );
			$sql->execute( $data );

			return 'Post has changed';
		}
		catch ( PDOException $e ) {
			return $e->getMessage();
		}


	}

}