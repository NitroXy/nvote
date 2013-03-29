<?php

require_once "$dir/libs/nxauth/include.php";

{

	/* Settings for CAS via NitroXy.com */

	$cas_config = array(
		'site' => "nitroxy.com",
		'port' => 443,
		'key_id' => "nvote",
		'private_key' => dirname(__FILE__) . "/nvote.priv",
		'ca_cert' => "$nxauth_root/certs/GeoTrustGlobalCA.pem", /* If this is null no cert validation will be done */
	);

	NXAuth::init($cas_config);

}

/* Include local user config */
if(file_exists("$dir/nxauth.local.php")) {
	require_once "$dir/nxauth.local.php";
}
