<?php

if ( !$u ){
	redirect();
}

$entry = Entry::selection(array('user_id' => $u->user_id, 'event' => $event, '@order' => 'category_id'));
