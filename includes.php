<?php
	$dir = dirname(__FILE__);

	require("$dir/autoload.php");
	require("$dir/libs/MC.php");
	require("$dir/libs/nxauth/include.php");
	require_once("$dir/helpers/settings.php");
	require_once('libs/BasicObject/BasicObject.php');
	require_once('libs/BasicObject/ValidatingBasicObject.php');

	Settings::load("$dir/config.php");
	BasicObject::$output_htmlspecialchars = true;

	$db = new MySQLi(Settings::get('database.host'), Settings::get('database.user'), Settings::get('database.password'), Settings::get('database.name'));

	NXAuth::init(Settings::get('cas_config'));

	require("$dir/auth.php");

	try {
		$mc = MC::get_instance();
		BasicObject::enable_structure_cache($mc);
	} catch(Exception $e) {
		trigger_error("Exception when trying to enable BasicObject structure cache: ".$e->getMessage());
		// We can live without memcache
	}

	$event = NXAPI::current_event();
	$event_obj = Event::one(array('short_name' => $event));
	if(!$event_obj) {
		$api_event = NXAPI::event_info(array('event' => $event));
		$event_obj = new Event(array(
			'short_name' => $event,
			'name' => $api_event->name
		));
		$event_obj->commit();
	}

	if ( !isset($keep_settings) || !$keep_settings ){
		Settings::unset_sensitive();
	}
?>
