<?php

need_admin();

function open_votes(){
	global $category;

	foreach ( $category as $cur ){
		$cur->entry_open = isset($_POST['i'.$cur->category_id]);
		$cur->vote_open  = isset($_POST['r'.$cur->category_id]);
		$cur->commit();
	}
	flash('success', 'Ändringarna sparade');
}

$method = $_SERVER['REQUEST_METHOD'];
$category = Category::selection(array('event' => $event));

if ( $method == 'POST' ){
	$arg = $_GET['arg'];
	switch ( $arg ) {
	case 'category_status':
		$category = Category::from_id($_POST['id']);
		$value = $_POST['value'];
		if($_POST['what'] == 'entry') {
			$category->entry_open = $value;
		} else if($_POST['what'] == 'vote') {
			$category->vote_open = $value;
		} else {
			die("Unknown what");
		}
		$category->commit();
		die("");
		break;
	case 'create_category':
		$category = new Category(array(
			'name'=> $_POST['name'],
			'description' => $_POST['description'],
			'event' => $event,
		));
		$category->commit();
		redirect('admin');
		break;
	case 'clone':
		foreach(Category::selection(array('event' => $_POST['event'])) as $category) {
			$c = $category->duplicate();
			$c->event = $event;
			$c->commit();
		}
		redirect('admin');
		break;
	default:
		die("Invalid command");
	}
}
