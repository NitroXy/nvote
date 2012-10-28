<?php

require_once "nxauth.php";

class UserMockup {
	public $user_id = 1;
	public $username = 'Test user';
	public $name = 'Herr Test An. vändare';
	public $admin = false;
};

$u = new UserMockup();

echo "Auth: ". phpCAS::getUser() . "<br/>";
foreach(phpCAS::getAttributes() as $k => $v) {
	echo "$k: $v<br/>";	
}
