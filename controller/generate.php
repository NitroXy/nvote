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
	$cmd = "$app $src $author $title $poster 2>&1";
	echo $cmd . "\n";
	flush();
	ob_flush();
	exec($cmd, $output, $rc);
	if ( $rc != 0 ){
		echo "Command exited with code $rc:\n";
		echo implode("\n", $output);
	}
	return $poster;
}

$categories = $event->Category;

$e = "$dir/upload/{$event->short_name}";
$final = "$e/final";
if ( !file_exists($final) ){
	mkdir($final);
}

header('Content-Type: text/plain');

if ( !file_exists("$e/template.png") ){
	die("$e/template.png missing");
}

foreach ( $categories as $cur ){
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
