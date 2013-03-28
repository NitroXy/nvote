<?php

need_admin();

$method = $_SERVER['REQUEST_METHOD'];
$category = Category::selection(array('event' => $event));

if ( $method == 'POST' ){
	$arg = $_GET['arg'];
	switch ( $arg ) {
	case 'category_status':
		$category = Category::from_id($_POST['id']);
		if(!$category) {
			flash_json('error', "Okänt kategori-id");
		}
		$value = $_POST['value'];
		if($_POST['what'] == 'entry') {
			$category->entry_open = $value;
		} else if($_POST['what'] == 'vote') {
			$category->vote_open = $value;
		} else {
			flash_json('error', "Internt fel (unknown 'what')");
		}
		$category->commit();
		flash_json('success', "Ändringarna sparades");
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
