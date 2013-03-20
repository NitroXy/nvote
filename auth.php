<?php

require_once "nxauth.php";

$u = User::from_nxuser(NXAuth::user());
