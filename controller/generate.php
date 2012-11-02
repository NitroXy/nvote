<?php

$output_htmlspecialchars = false;
setlocale(LC_CTYPE, "en_US.UTF-8");

function create_poster($n, $entry){
	global $e, $d;
	$app = realpath(dirname(__FILE__) . "/../create_slide");
	$src = escapeshellarg("$e/template.png");
	$dst = sprintf('%s/%03d', $d, $n);
	$author = escapeshellarg($entry->author);
	$title = escapeshellarg($entry->title);
	$poster = escapeshellarg("$dst-0.mkv");
	$cmd = "$app $src $author $title $poster";
	echo $cmd . "\n";
	flush();
	ob_flush();
	exec($cmd);
	return $poster;
}

$category = Category::selection(array('event' => $event));

$e = "$dir/upload/$event";
$final = "$e/final";
if ( !file_exists($final) ){
	mkdir($final);
}

header('Content-Type: text/plain');

foreach ( $category as $cur ){
	$d = "$final/{$cur->dirname()}";
	if ( !file_exists($d) ){
		mkdir($d);
	}

	$playlist = array();
	foreach ( Entry::selection(array('category_id' => $cur->category_id, 'disqualified' => false)) as $n => $entry ){
		$playlist[] = create_poster($n, $entry);
	}
}

exit;