<?php

$flash = array();
if ( isset($_SESSION['flash']) ){
	$flash = $_SESSION['flash'];
	unset($_SESSION['flash']);
}

$open_cat = $event->Category(array('entry_open' => true));
