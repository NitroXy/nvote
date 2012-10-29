<?php

$admin_mode = (isset($_GET['admin']) && $u && $u->admin );

if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !($category && (($u && $u->admin) || $category->vote_open)) ){
		$view = '../view/bad_cat.php';
		return;
	}
	$selection = array('category_id' => $cat_id);
	if(! $admin_mode ) $selection['disqualified'] = 0;
	$entry = Entry::selection($selection);
	$view = '../view/list_entry.php';
} else {
	$selection = array('event' => $event);
	if(!$admin_mode) $selection['vote_open'] = true;
	$category = Category::selection($selection);
	$view = '../view/list_cat.php';
}
