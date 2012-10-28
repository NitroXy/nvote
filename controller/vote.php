<?php



if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !$category ){
		$view = '../view/bad_cat.php';
		return;
	}
	$entry = Entry::selection(array('category_id' => $cat_id));
	$view = '../view/list_entry.php';
} else {
	$category = Category::selection(array('event' => $event));
	$view = '../view/list_cat.php';
}
