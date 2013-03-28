<?php

require_once "libs/nxauth/include.php";

{

	/* Settings for CAS via NitroXy.com */

	$cas_config = array(
		'site' => "nitroxy.torandi.com",
		'port' => 443,
		'key_id' => "nvote",
		'private_key' => dirname(__FILE__) . "/cas_key.priv",
		'ca_cert' => null,
	);

	NXAuth::init($cas_config);

}

/* Include local user config */
if(file_exists("$dir/nxauth.local.php")) {
	require_once "$dir/nxauth.local.php";
}
