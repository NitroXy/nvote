<?php
function __autoload($class)
{
	global $dir;
	$filename = strtolower($class);
	if(file_exists($dir.'/model/'.$filename.'.php')){
		require_once $dir.'/model/'.$filename.'.php';
	}
}
