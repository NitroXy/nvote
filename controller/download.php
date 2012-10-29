<?php

BasicObject::$output_htmlspecialchars = false; /* want raw filenames */
$entry_id = $_GET['arg'];
$entry = Entry::from_id($entry_id);
if ( !$entry ){
	return;
}

$entry->final_filename($location, $dst);
$src = "$dir/$location";
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $src);
finfo_close($finfo);

$m = explode('/', $mime);
header("Content-type: $mime");

if ( $m[0] != 'image' ){
	header("Content-Disposition: attachment; filename=\"$dst\"");
} else {
	header("Content-Disposition: inline; filename=\"$dst\"");
}

readfile($src);
exit;
