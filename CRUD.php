<?php

namespace sd;
require_once 'DB.php';

use PDO;
use PDOException;

class CRUD {
	protected $pdo;

	public function __construct() {
		$this->pdo = DB::getPdo();
	}

	public function create( $title, $content ) {
		$message         = 'Post created';
		$data            = [];
		$data['title']   = $title;
		$data['content'] = $content;
		try {
			$this->pdo->prepare( "INSERT INTO `posts`(`title`, `content`) VALUES(:title, :content)" )->execute( $data );
		}
		catch ( PDOException $e ) {
			$message = $e->getMessage();
		}

		return $message;
	}

	public function index( $count, $order ) {
		try {
			$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE deleted_at IS NULL ORDER BY id {$order} LIMIT 0, {$count}" );
			$sql->execute();

			$result = $sql->fetchAll( PDO::FETCH_ASSOC );
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
			if ( ! $this->isPostExists( $id ) ) {
				return 'Post was not found';
			}
			$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE (id = ? AND deleted_at IS NULL)" );
			$sql->execute( [ $id ] );

			return $sql->fetch( PDO::FETCH_ASSOC );
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
		$data['date']    = date( 'Y-m-d H:i:s' );
		if ( ! $this->isPostExists( $id ) ) {
			return 'Post was not found';
		}
		try {
			$sql = $this->pdo->prepare( "UPDATE `posts` SET title=:title, content=:content, updated_at=:date WHERE (id=:id AND deleted_at IS NULL)" );
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


			if ( ! $this->isPostExists( $id ) ) {
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

	/*
	 * Check is post exists
	 */
	protected function isPostExists( $id ) {
		$sql = $this->pdo->prepare( "SELECT * FROM `posts` WHERE (id = ? AND deleted_at IS NULL)" );
		$sql->execute( [ $id ] );
		$result = $sql->fetch();

		return ! empty( $result );
	}

}