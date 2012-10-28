<?php

need_login();
$entry_id = (int)$_GET['arg'];
$entry = Entry::from_id($entry_id);

if ( !$entry ){
	$view = '../view/bad_entry.php';
	return;
}

$entry->delete();
flash('success', 'Bidraget togs bort');
redirect('my');
