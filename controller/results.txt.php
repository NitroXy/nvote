<?php

$width = 80;

function print_line() {
	global $width;
	for($i=0; $i<$width; ++$i) {
		echo "-";
	}
	echo "\n";
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
		printf("%-39s %-32s %4dpts\n", $entry->title, html_entity_decode($entry->author), $entry->score());
	}
	echo "\n";
}

exit;
