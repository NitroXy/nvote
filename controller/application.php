<?php

if( isset($_GET['admin_mode']) ) {
	need_admin();
	$_SESSION['admin_mode'] = $_GET['admin_mode'] == "on";
	unset($_GET['admin_mode']);
	redirect($_GET['main'] . (isset($_GET['arg']) ? "/" . $_GET['arg'] : ""));
}

$flash = array();
if ( isset($_SESSION['flash']) ){
	$flash = $_SESSION['flash'];
	unset($_SESSION['flash']);
}
