<?php

if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !($category && $category->vote_open) ){
		$view = '../view/bad_cat.php';
		return;
	}
	$admin_mode = (isset($_GET['admin']) && $u && $u->admin );
	$selection = array('category_id' => $cat_id);
	if(! $admin_mode ) $selection['disqualified'] = 0;
	$entry = Entry::selection($selection);
	$view = '../view/list_entry.php';
} else {
	$category = Category::selection(array('event' => $event, 'vote_open' => true));
	$view = '../view/list_cat.php';
}
