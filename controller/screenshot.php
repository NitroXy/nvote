<?php

BasicObject::$output_htmlspecialchars = false; /* want raw filenames */
$entry_id = $_GET['arg'];
$entry = Entry::from_id($entry_id);
if ( !$entry ){
	return;
}

$src = "$dir/{$entry->screenshot_filename}";
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $src);
finfo_close($finfo);

header("Content-type: $mime");
echo file_get_contents($src);
exit;
