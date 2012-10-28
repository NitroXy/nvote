<?php

need_admin();

function open_votes(){
	global $category;

	foreach ( $category as $cur ){
		$cur->vote_open = isset($_POST[$cur->category_id]);
		$cur->commit();
	}
	flash('success', 'Ändringarna sparade');
}

$method = $_SERVER['REQUEST_METHOD'];
$category = Category::selection(array('event' => $event));

if ( $method == 'POST' ){
	$arg = $_GET['arg'];
	if ( $arg == 'open' ){
		open_votes();
		redirect('admin');
	}
	die("invalid subfunc");
} else if ( $method == 'GET' ){

}
