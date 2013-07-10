<?php

$width = 80;

function print_line() {
	global $width;
	for($i=0; $i<$width; ++$i) {
		echo "-";
	}
	echo "\n";
}

function str_limit($str, $max_length) {
	$len = mb_strlen($str, "UTF-8");
	$padding = $max_length - $len;
	if($padding < 0) $str = mb_substr($str, 0, $max_length - 3, "UTF-8") . "...";
	return $str . str_repeat(" ", max($padding, 0));
}

BasicObject::$output_htmlspecialchars = false;

setlocale(LC_CTYPE, "en_US.UTF-8");

need_admin();

$event_obj = Event::one(array('short_name' => $event));

$category = Category::selection(array('event' => $event));

usort($category, function($a, $b) {
	/* Just look at this unoptimal crap
	 * (But at least BO caches it for us)
	 */
	return count($b->Entry(array('disqualified' => '0'))) - count($a->Entry(array('disqualified' => '0'))) ;
});

$info = $event_obj->info();

$year = date("Y", strtotime($info->event_start));

header('Content-Type: text/plain');

echo "{$event_obj->name} ($year)
Sweden, Burseryd
\n";

foreach($category as $cat) {
	echo "{$cat->name}\n";
	print_line();
	foreach($cat->Entry() as $entry) {
		$author = html_entity_decode($entry->author);
		$line = str_limit($entry->title, 39);
		$line .= " " . str_limit(html_entity_decode($entry->author), 32);
		$line .= sprintf(" %4dpts\n", $entry->score());
		echo $line;
		#mb_sprintf($derp,"%-39s %-32s %4dpts\n", $entry->title, html_entity_decode($entry->author), $entry->score());
	}
	echo "\n";
}

exit;
