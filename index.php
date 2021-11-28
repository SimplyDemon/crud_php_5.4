<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

require_once 'CRUD.php';

$crud   = new sd\CRUD();
$result = 'Params are wrong';

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ( $requestMethod === 'POST' ) {
	$result = 'No title';
	if ( isset( $_REQUEST ) && ! empty( $_REQUEST ) && isset( $_REQUEST['title'] ) && ! empty( $_REQUEST['title'] ) ) {
		$result = $crud->create();
	}


} else if ( $requestMethod === 'GET' && isset( $_REQUEST['id'] ) && ! empty( $_REQUEST['id'] ) ) {
	$result = 'Id is incorrect';
	if ( is_numeric( $_REQUEST['id'] ) ) {
		$result = $crud->show( $_REQUEST['id'] );
	}
} else if ( $requestMethod === 'GET' ) {
	$result = $crud->index();
}

echo json_encode( $result );