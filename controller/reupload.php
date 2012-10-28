<?php

need_login();

$method = $_SERVER['REQUEST_METHOD'];
if ( $method == 'POST' ){
	$entry_id = (int)$_POST['entry_id'];
	$entry = Entry::from_id($entry_id);
	if ( !($entry && $entry->upload($_FILES['file'])) ){
		redirect("reupload/{$entry_id}");
	}
	redirect('my');
} else {
	$entry_id = (int)$_GET['arg'];
	$entry = Entry::from_id($entry_id);
	if ( !$entry ){
			$view = '../view/bad_entry.php';
			return;
	}

	$view = '../view/reupload.php';
}
