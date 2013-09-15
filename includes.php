<?php
	$dir = dirname(__FILE__);

	require("$dir/autoload.php");
	require("$dir/libs/MC.php");
	require("$dir/libs/nxauth/include.php");
	require("$dir/config.php");
	require_once('libs/BasicObject/BasicObject.php');
	require_once('libs/BasicObject/ValidatingBasicObject.php');
	require_once("$dir/libs/php-markdown/MarkdownHelper.php");

	BasicObject::$output_htmlspecialchars = true;

	$db_settings = $settings['database'];
	$db = new MySQLi($db_settings['host'], $db_settings['user'], $db_settings['password'], $db_settings['name']);

	unset($db_settings);

	$crew_groups = array('Kreativ'); /* Crew groups that are admins */

	NXAuth::init($settings['cas_config']);

	require("$dir/auth.php");

	try {
		$mc = MC::get_instance();
		BasicObject::enable_structure_cache($mc, "nvote_");
	} catch(Exception $e) {
		trigger_error("Exception when trying to enable BasicObject structure cache: ".$e->getMessage());
		// We can live without memcache
	}

	$event_short_name = Setting::get('event', NXAPI::current_event(), true);

	$event = Event::one(array('short_name' => $event_short_name));

	if(!$event) {
		$api_event = NXAPI::event_info(array('event' => $event_short_name));
		$event = new Event(array(
			'short_name' => $event_short_name,
			'name' => $api_event->name
		));
		$event->commit();
	}

	if(!isset($keep_settings) || !$keep_settings) unset($settings);

	/* Include helpers */
	$helper_dirs = dir("$dir/helpers");
	while(($f = $helper_dirs->read()) !== false ) {
		$f = "$dir/helpers/$f";
		if(is_file($f) && pathinfo($f, PATHINFO_EXTENSION) == "php") {
			require $f;
		}
	}
?>
