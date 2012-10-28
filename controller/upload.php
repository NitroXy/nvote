<?php

need_login();

$upload_errors = array(
	UPLOAD_ERR_OK          => "No errors.",
	UPLOAD_ERR_INI_SIZE    => "Larger than upload_max_filesize.",
	UPLOAD_ERR_FORM_SIZE   => "Larger than form MAX_FILE_SIZE.",
	UPLOAD_ERR_PARTIAL     => "Partial upload.",
	UPLOAD_ERR_NO_FILE     => "No file.",
	UPLOAD_ERR_NO_TMP_DIR  => "No temporary directory.",
	UPLOAD_ERR_CANT_WRITE  => "Can't write to disk.",
	UPLOAD_ERR_EXTENSION   => "File upload stopped by extension.",
);

$accepted = array(
	'application/gzip',
	'application/rar',
	'application/zip',
	'audio/mpeg',
	'audio/x-wav',
	'video/mp4',
);

$method = $_SERVER['REQUEST_METHOD'];
if ( $method == 'POST' ){
	if ( !$u ){
		redirect();
	}

	$from = "edit/{$entry_id}";
	$file = false;

	if ( !isset($_POST['entry_id']) ){
		$file = $_FILES['file'];
		$error = $file['error'];
		$mime = $file['type'];
	}

	$_SESSION['category'] = $_POST['category'];
	$_SESSION['title'] = $_POST['title'];
	$_SESSION['author'] = $_POST['author'];
	$_SESSION['description'] = $_POST['description'];

	if ( $file ){
		/* check for error indicator */
		if ( $error > 0 ){
			$_SESSION['flash'] = array('error' => $upload_errors[$error]);
			redirect('upload');
		}

		/* ensure a file was uploaded */
		if ( $file['size'] == 0 ){
			$_SESSION['flash'] = array('error' => 'Ingen fil.');
			redirect('upload');
		}

		/* validate mime-types */
		if ( !in_array($mime, $accepted) ){
			$_SESSION['flash'] = array('error' => "Filformatet \"$mime\" accepterades inte.");
			redirect('upload');
		}
	}

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
	redirect('entry');
} else if ( $method == 'GET' ){
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
