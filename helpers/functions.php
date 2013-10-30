<?php

function redirect($main=''){
	header("Location: /$main");
	exit;
}

/* pasta from php.net to convert upload_max_filesize to bytes */
function return_bytes($val){
	$val = trim($val);
	$last = strtolower($val[strlen($val)-1]);
	switch($last) {
		// The 'G' modifier is available since PHP 5.1.0
	case 'g':
		$val *= 1024;
	case 'm':
		$val *= 1024;
	case 'k':
		$val *= 1024;
	}

	return $val;
}

function flash($class, $message){
	global $flash;
	$_SESSION['flash'] = array($class => $message);
	$flash[$class] = $message;
}

function flash_validation_errors($exception, $message) {
	$msg = "$message<br/>";
	$errors = $exception->errors;
	if(isset($errors['base'])) {
		$msg .= implode(", ",['base'])."<br/>";
	}
	unset($errors['base']);
	foreach($errors as $field => $err) {
		$msg .= "<strong>$field: </strong> ".implode(", ",$err)."<br/>";
	}
	flash('error', $msg);
}

function sessiondata($key, $default=null){
	return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
}

function need_login(){
	global $u;
	if ( !$u ){
		flash('error', "Du måste vara inloggad");
		redirect();
	}
}

function need_right($right) {
	if( !$right ) {
		redirect();
	}
}

function need_admin(){
	if ( !Can::administrate() ){
		flash('error', 'Måste vara admin');
		redirect();
	}
}

function admin_mode() {
	return Can::administrate() && isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] == true;
}

function output_json($data) {
	header("Content-Type: text/json");
	echo json_encode($data);
	exit();
}

function flash_json($class, $message) {
	output_json(array($class => $message));
}
