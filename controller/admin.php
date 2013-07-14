<?php

need_admin();

$method = $_SERVER['REQUEST_METHOD'];
$categories = Category::selection(array('event' => $event));

$category = new Category(array('event' => $event));

$event_obj = Event::one(array('short_name' => $event));

$arg = isset($_GET['arg']) ? $_GET['arg'] : null;

if ( $method == 'POST' ) {
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
		try {
			$category = Category::update_attributes($_POST['Category'], array('commit' => false));
			$category->commit();
			flash('success', "Skapade kategori {$category->name}");
			redirect('admin');
		} catch (ValidationException $e) {
			flash('error', "Kunde inte spara kategorin, något fält saknas");
		}
		break;
	case 'delete_category':
		$category = Category::from_id($_POST['id']);
		if(Entry::count(array('category_id' => $category->category_id)) > 0) {
			flash('error', "Kan inte radera kategori med bidrag");
			redirect('admin');
		}
		$category->delete();
		flash('success', "Kategorin togs bort");
		redirect('admin');
		break;
	case 'clone':
		foreach(Category::selection(array('event' => $_POST['event'])) as $cat) {
			$c = $cat->duplicate();
			$c->event = $event;
			$c->commit();
		}
		redirect('admin');
		break;
	default:
		die("Invalid command");
	}
} else {
	switch($arg) {
		case 'categories':
			output_json($categories);
			exit;
	}
}
