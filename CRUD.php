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
			$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE deleted_at IS NULL" );
			$sql->execute();

			$result = $sql->fetchAll();
			if ( ! $result ) {
				$result = 'No posts';
			}

			return $result;
		}
		catch ( PDOException $e ) {
			return $e->getMessage();
		}
	}

	public function show( $id ) {
		try {
			$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE (id = ? AND deleted_at IS NULL)" );
			$sql->execute( [ $id ] );
			$result = $sql->fetch();
			if ( ! $result ) {
				$result = 'Post was not found';
			}

			return $result;
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
			$sql = $this->pdo->prepare( "UPDATE `posts` SET title=:title, content=:content WHERE (id=:id AND deleted_at IS NULL)" );
			$sql->execute( $data );

			return 'Post was updated';
		}
		catch ( PDOException $e ) {
			return $e->getMessage();
		}

	}

	/*
	 * Use soft delete
	 */
	public function destroy( $id ) {
		try {
			$data         = [];
			$data['date'] = date( 'Y-m-d H:i:s' );
			$data['id']   = $id;

			/* Check is post exists */
			$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE (id = ? AND deleted_at IS NULL)" );
			$sql->execute( [ $id ] );
			$result = $sql->fetch();
			if ( ! $result ) {
				return 'Post was not found';
			}

			$sql = $this->pdo->prepare( "UPDATE `posts` SET deleted_at=:date WHERE (id=:id AND deleted_at IS NULL)" );
			$sql->execute( $data );

			return 'Post was deleted';
		}
		catch ( PDOException $e ) {
			return $e->getMessage();
		}
	}

}