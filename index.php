<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

require_once 'CRUD.php';

$crud = new sd\CRUD();

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ( $requestMethod === 'POST' ) {
	$message = 'No title';
	if ( isset( $_REQUEST ) && ! empty( $_REQUEST ) && isset( $_REQUEST['title'] ) && ! empty( $_REQUEST['title'] ) ) {
		$message = $crud->create();
	}

	return $message;
}