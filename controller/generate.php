<?php

$output_htmlspecialchars = false;
setlocale(LC_CTYPE, "en_US.UTF-8");

function create_slide(array $argv){
	$app = realpath(dirname(__FILE__) . "/../create_slide");
	$cmd = implode(' ', array_map(function($x){ return escapeshellarg($x); }, array_merge(array($app), $argv))) . ' 2>&1';
	echo "$cmd\n";

	flush();
	ob_flush();
	exec($cmd, $output, $rc);
	if ( $rc != 0 ){
		echo "Command exited with code $rc:\n";
		echo implode("\n", $output);
	}
}

function create_entry_poster($dst, $entry){
	global $e;
	$src = "$e/template.png";
	create_slide(array(
		'-i', $src,
		'-a', $entry->author,
		'-t', $entry->title,
		$dst));
	return $dst;
}

function create_category_poster($dst, $title){
	global $e;
	$src = "$e/template.png";
	create_slide(array(
		'-i', $src,
		'-c', $title,
		$dst));
	return $dst;
}

function resize_image($dst, $entry){
	global $e;
	$src = "$e/template.png";
	create_slide(array(
		'-i', $src,
		'-s', $entry->source_filename(),
		$dst));
	return $dst;
}

function create_music_poster($dst, $entry){
	global $e;
	$src = "$e/template.png";
	create_slide(array(
		'-i', $src,
		'-a', $entry->author,
		'-t', $entry->title,
		'-m', $entry->source_filename(),
		$dst));
	return $dst;
}

function create_entry_video($dst, $entry){
	if ( $entry->is_image() ){
		return resize_image($dst, $entry);
	} else if ( $entry->is_video() ){
		if ( !file_exists($dst) ) {
			link($entry->source_filename(), $dst);
		}
		return $dst;
	} else if ( $entry->is_music() ){
		return create_music_poster($dst, $entry);
	} else {
		throw new Exception('Unknown format');
	}
}

$category = Category::selection(array('event' => $event));

$e = "$dir/upload/$event";
$final = "$e/final";
if ( !file_exists($final) ){
	mkdir($final);
}

header('Content-Type: text/plain');

if ( !file_exists("$e/template.png") ){
	die("$e/template.png missing");
}

$playlist = array();
foreach ( $category as $cur ){
	$d = "$final/{$cur->dirname()}";
	if ( !file_exists($d) ){
		mkdir($d);
	}

	$playlist[] = create_category_poster("$d.mkv", $cur->name);
	foreach ( Entry::selection(array('category_id' => $cur->category_id, 'disqualified' => false)) as $n => $entry ){
		try {
			$playlist[] = create_entry_poster(sprintf('%s/%03d-0.mkv', $d, $n), $entry);
			$playlist[] = create_entry_video (sprintf('%s/%03d-1.mkv', $d, $n), $entry);
		} catch ( Exception $e ){
			echo "Cannot create slide from {$entry->source_filename()}: {$e->getMessage()}\n";
		}
	}
}

$filename = "$final/playlist";
echo "Creating playlist at $filename\n";
$fp = fopen("$final/playlist", "w");
foreach ( $playlist as $row ){
	fwrite($fp, "$row\n");
	echo "  -> $row\n";
}
fclose($fp);

exit;
