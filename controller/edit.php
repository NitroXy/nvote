<?php

$entry_id = (int)$_GET['arg'];
$entry = Entry::from_id($entry_id);

if ( !$entry ){
	$view = '../view/bad_entry.php';
	return;
}

if ( !$entry->Category->entry_open() ){
	$view = '../view/entry_closed.php';
	return;
}

$view = '../view/upload.php';
$title = $entry->title;
$author = $entry->author;
$description = $entry->description;
$allow_file = false;
