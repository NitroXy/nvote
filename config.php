<?php

class Config {
	private static $db_host = "127.0.0.1";
	private static $db_name = "nvote";
	private static $db_user = "nvote";
	private static $db_password = "9sd8fg7h9087sdfg";

	public static function fix_database($username=null) {
		if(is_null($username)) {
			$username = self::$db_user;
			$password = self::$db_password;
		} else {
			$password = ask_for_password();
		}
		return new MySQLi(self::$db_host, $username, $password, self::$db_name);
	}
}

require_once('model/BasicObject.php');
require_once('model/ValidatingBasicObject.php');
BasicObject::$output_htmlspecialchars = true;

$db = Config::fix_database();
$event = 'nx15';
$dir = dirname(__FILE__);
