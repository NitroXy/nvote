<?php

if ( !$u ){
	redirect();
}

$entry = Entry::selection(array('user_id' => $u->user_id, 'category.event_id' => $event->id, '@order' => array('disqualified','category_id')));
