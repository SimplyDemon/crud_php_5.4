<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

require_once 'CRUD.php';

$crud   = new sd\CRUD();
$result = 'Params are wrong';

$requestMethod = $_SERVER['REQUEST_METHOD'];
if ( $requestMethod === 'POST' ) {
	$result = 'No title or content';
	if ( isset( $_REQUEST ) && ! empty( $_REQUEST ) &&
	     isset( $_REQUEST['title'] ) && ! empty( $_REQUEST['title'] ) &&
	     isset( $_REQUEST['content'] ) && ! empty( $_REQUEST['content'] ) ) {

		$title   = $_REQUEST['title'];
		$title   = filter_input( INPUT_GET, 'title', FILTER_SANITIZE_STRING );
		$content = $_REQUEST['content'];
		$content = filter_input( INPUT_GET, 'content', FILTER_SANITIZE_STRING );
		$result  = $crud->create( $title, $content );
	}

} else if ( $requestMethod === 'GET' && isset( $_REQUEST['id'] ) ) {
	$result = 'Id is incorrect';
	if ( ! empty( $_REQUEST['id'] ) && is_numeric( $_REQUEST['id'] ) ) {
		$result = $crud->show( $_REQUEST['id'] );
	}
} else if ( $requestMethod === 'PUT' || $requestMethod === 'PATCH' ) {
	$result = 'Data is incorrect';
	if ( isset( $_REQUEST ) && ! empty( $_REQUEST ) &&
	     isset( $_REQUEST['title'] ) && ! empty( $_REQUEST['title'] ) &&
	     isset( $_REQUEST['content'] ) && ! empty( $_REQUEST['content'] ) &&
	     isset( $_REQUEST['id'] ) && ! empty( $_REQUEST['id'] ) ) {

		$result = $crud->update( $_REQUEST['id'], $_REQUEST['title'], $_REQUEST['content'] );
	}

} else if ( $requestMethod === 'DELETE' ) {
	$result = 'Id is incorrect';
	if ( isset( $_REQUEST['id'] ) && ! empty( $_REQUEST['id'] ) && is_numeric( $_REQUEST['id'] ) ) {
		$result = $crud->destroy( $_REQUEST['id'] );
	}
} else if ( $requestMethod === 'GET' ) {
	$result = $crud->index();
}

echo json_encode( $result );