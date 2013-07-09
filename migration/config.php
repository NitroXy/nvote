<?php

$dir = dirname(__FILE__) . "/../";

$keep_settings = true;

require("$dir/includes.php");
$db->close();
unset($db);

class Config {
	public static function fix_database($username=null) {
		global $settings;
		$db_settings = $settings['database'];
		if(is_null($username)) {
			$username = $db_settings['user'];
			$password = $db_settings['password'];
		} else {
			$password = ask_for_password();
		}
		return new MySQLi($db_settings['host'], $username, $password, $db_settings['name']);
	}
}
