<?php

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

	$file = $_FILES['file'];
	$error = $file['error'];
	$mime = $file['type'];

	$_SESSION['title'] = $_POST['title'];
	$_SESSION['author'] = $_POST['author'];
	$_SESSION['description'] = $_POST['description'];

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

	$entry_id = isset($_POST['entry']) ? $_POST['entry'] : false;
	if ( !$entry_id ){
		$entry = new Entry();
		$entry->user_id = $u->user_id;
		$entry->category_id = $_POST['category'];
	} else {
		$entry = Entry::from_id($entry_id);
		if ( $u->user_id != $entry->user_id ){
			flash('error', 'fel användare');
			redirect('upload');
		}
	}

	$entry->title = $_POST['title'];
	$entry->author = $_POST['author'];
	$entry->description = $_POST['description'];
	$entry->commit();

	if ( !$entry->upload($file) ){
		redirect('upload');
	}

	unset($_SESSION['title']);
	unset($_SESSION['author']);
	unset($_SESSION['description']);

	redirect('upload_done');
}
