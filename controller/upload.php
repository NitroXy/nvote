<?php

need_login();

$method = $_SERVER['REQUEST_METHOD'];
if ( $method == 'POST' ){
	$from = "edit/{$entry_id}";
	$file = false;

	if ( !isset($_POST['entry_id']) ){
		$file = $_FILES['file'];
		$from = "upload";
	}

	$_SESSION['category'] = $_POST['category'];
	$_SESSION['title'] = $_POST['title'];
	$_SESSION['author'] = $_POST['author'];
	$_SESSION['description'] = $_POST['description'];

	$entry_id = isset($_POST['entry_id']) ? $_POST['entry_id'] : false;
	if ( !$entry_id ){
		$entry = new Entry();
		$entry->user_id = $u->user_id;
		$entry->category_id = $_POST['category'];
		$entry->event = $event;
	} else {
		$entry = Entry::from_id($entry_id);
		if ( $u->user_id != $entry->user_id ){
			flash('error', 'fel användare');
			redirect($from);
		}
	}

	if ( !$entry->Category->entry_open ){
		flash('error', 'Inlämningen har stängt');
		redirect($from);
	}

	$entry->title = $_POST['title'];
	$entry->author = $_POST['author'];
	$entry->description = $_POST['description'];
	$entry->commit();

	if ( $file && !$entry->upload($file) ){
		redirect('upload');
	}

	unset($_SESSION['category']);
	unset($_SESSION['title']);
	unset($_SESSION['author']);
	unset($_SESSION['description']);

	flash('success', $entry ? 'Ändringarna sparade' : 'Bidraget uppladdat');
	redirect('my');
} else if ( $method == 'GET' ){
	$category = $open_cat;
	$selected_category = sessiondata('category');
	$title = sessiondata('title');
	$author = sessiondata('author', $u->name);
	$description = sessiondata('description');
	$allow_file = true;

	unset($_SESSION['category']);
	unset($_SESSION['title']);
	unset($_SESSION['author']);
	unset($_SESSION['description']);
}
