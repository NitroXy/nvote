<?php

$flash = array();
if ( isset($_SESSION['flash']) ){
	$flash = $_SESSION['flash'];
	unset($_SESSION['flash']);
}
