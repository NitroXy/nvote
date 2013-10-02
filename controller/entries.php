<?php

$show_results_txt = true;

if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !($category && ($category->results_public() || admin_mode() ) ) ){
		$view = '../view/bad_cat.php';
		return;
	}

	$selection = array('category_id' => $cat_id);
	if(! admin_mode() ) $selection['disqualified'] = 0;
	$entry = Entry::selection($selection);

	usort($entry, function($a, $b) {
		return $b->score() - $a->score();
	});

	$view = '../view/entries.php';
} else {
	$selection = array();
	if(!admin_mode()) $selection['status'] = Category::$RESULTS_PUBLIC;
	$category = $event->Category($selection);
	$view = '../view/list_cat.php';
}
