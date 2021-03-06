<?php

need_right(Can::submit());

$method = $_SERVER['REQUEST_METHOD'];
if ( $method == 'POST' ){
	$entry_id = isset($_POST['entry_id']) ? $_POST['entry_id'] : false;
	$from = "edit/{$entry_id}";
	$file = false;

	if ( !isset($_POST['entry_id']) ){
		$from = "upload";
		if( !isset($_FILES['file'] )) {
			flash('error', 'Kunde inte ladda upp filen. Är den för stor?');
			redirect($from);
		}
		$file = $_FILES['file'];
	}

	if(isset($_POST['category'])) {
		$_SESSION['category'] = $_POST['category'];
	}
	$_SESSION['title'] = $_POST['title'];
	$_SESSION['author'] = $_POST['author'];
	$_SESSION['description'] = $_POST['description'];

	if ( !$entry_id ){
		$entry = new Entry(array(
			'user_id' => $u->user_id,
			'category_id' => $_POST['category']
		));
	} else {
		$entry = Entry::from_id($entry_id);
		if ( $u->user_id != $entry->user_id ){
			flash('error', 'fel användare');
			redirect($from);
		}
	}

	if ( !$entry->Category->entry_open() && !admin_mode() ){
		flash('error', 'Inlämningen har stängt');
		redirect($from);
	}

	$entry->title = $_POST['title'];
	$entry->author = $_POST['author'];
	$entry->description = $_POST['description'];

	$db->autocommit(false);
	$entry->commit();
	if($_FILES['screenshot'] && !$entry->set_screenshot($_FILES['screenshot'])) {
		$db->rollback();
		redirect($from);
	}
	if ( $file && !$entry->upload($file) ){
		$db->rollback();
		redirect($from);
	}
	$entry->commit();
	$db->commit();
	$db->autocommit(true);

	unset($_SESSION['category']);
	unset($_SESSION['title']);
	unset($_SESSION['author']);
	unset($_SESSION['description']);

	flash('success', $entry ? 'Ändringarna sparade' : 'Bidraget uppladdat');
	redirect('my');
} else if ( $method == 'GET' ){
	$filter = array();
	if(!admin_mode()) $filter['status'] = Category::$ENTRY_OPEN;
	$categories = $event->Category($filter);
	$selected_category = sessiondata('category');
	$title = sessiondata('title');
	$author = sessiondata('author', $u->username);
	$description = sessiondata('description');
	$allow_file = true;

	unset($_SESSION['category']);
	unset($_SESSION['title']);
	unset($_SESSION['author']);
	unset($_SESSION['description']);
}
