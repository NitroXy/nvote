<?php
	$dir = dirname(__FILE__);

	require("$dir/autoload.php");
	require("$dir/nxauth.php");
	require("$dir/config.php");
	require("$dir/auth.php");

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
