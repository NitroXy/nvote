<?php

require_once "libs/CAS.php"; //Include phpCAS

phpCAS::client(CAS_VERSION_2_0, "nitroxy.com", 443, "cas");

phpCAS::setDebug();
phpCAS::SetNoCasServerValidation();

if(!phpCAS::isAuthenticated()) {
	phpCAS::forceAuthentication();
}

?>
