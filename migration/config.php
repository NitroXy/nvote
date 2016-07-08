<?php

$dir = dirname(__FILE__) . "/../";

$keep_settings = true;

try {
	require("$dir/includes.php");
} catch(Exception $e) {
	echo "Error in include: {$e->getMessage()}, continue anyway? (enter or ctrl+c)";
	fgets(STDIN);
}
$db->close();
unset($db);

class MigrationConfig {
	public static function fix_database($username=null) {
		global $settings;
		$db_settings = $settings['database'];
		if(is_null($username)) {
			$username = $db_settings['user'];
			$password = $db_settings['password'];
		} else {
			$password = ask_for_password();
		}
		return new MySQLi($db_settings['host'], $username, $password, $db_settings['name']);
	}

	private static function clear_cache() {
		echo "Clear BasicObject structure cache\n";
		BasicObject::clear_structure_cache(MC::get_instance(), "nvote_");
	}

	/**
	 * Return an array of RE patterns of files to ignore.
	 */
	public static function ignored(){
		return array();
	}

	/*
	 * These hooks are called in different stages of the update_migration execution:
	 */

	/**
	 * Called before any migrations are run, but after database initialization
	 */
	public static function begin_hook() {

	}

	/**
	 * Called after all migrations are completed
	 */
	public static function end_hook() {
		//Clear basic object after end too (good way to manually clear cache by just running update_database)
		MigrationConfig::clear_cache();
	}

	/**
	 * Called before each migration are run
	 * @param $migration_name The name of the migration to be run
	 */
	public static function pre_migration_hook($migration_name) {

	}

	/**
	 * Called after each migration have succeded
	 * @param $migration_name The name of the migration that succeded
	 */
	public static function post_migration_hook($migration_name) {
		//Clear basic object cache after each migration
		MigrationConfig::clear_cache();
	}
	/*
	 * Called after a migration rollback has occurred, just before exit()
	 * @param $migration_name The name of the migration that caused the rollback
	 */

	public static function post_rollback_hook($migration_name) {

	}
}
