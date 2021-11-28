<?php


namespace sd;


use PDO;
use PDOException;

class DB {
	protected static $host = 'simplyd.beget.tech';
	protected static $dbName = 'simplyd_crud_5_4';
	protected static $userName = 'simplyd_crud_5_4';
	protected static $userPassword = '9Rckff*M';
	protected static $pdo;

	protected static function createConnection() {

		try {
			self::$pdo = new PDO( 'mysql:dbname=' . self::$dbName . '; host=' . self::$host, self::$userName, self::$userPassword );

			/* Display errors */
			self::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		}
		catch ( PDOException $e ) {
			die( $e->getMessage() );
		}

	}

	public static function getPdo() {
		if ( ! self::$pdo ) {
			self::createConnection();
		}

		return self::$pdo;
	}

}