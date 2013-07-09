<?php
	$dir = dirname(__FILE__);

	require("$dir/autoload.php");
	require("$dir/libs/nxauth/include.php");
	require("$dir/config.php");
	require("$dir/auth.php");

	require("$dir/libs/MC.php");

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
?>
