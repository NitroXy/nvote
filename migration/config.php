<?php

$dir = dirname(__FILE__) . "/../";

$keep_settings = true;

require("$dir/includes.php");
$db->close();
unset($db);

class Config {
	public static function fix_database($username=null) {
		global $settings;
		if(is_null($username)) {
			$username = Settings::get('database.user');
			$password = Settings::get('database.password');
		} else {
			$password = ask_for_password();
		}
		return new MySQLi(Settings::get('database.host'), $username, $password, Settings::get('database.name'));
	}

	private static function clear_cache() {
		echo "Clear BasicObject structure cache\n";
		BasicObject::clear_structure_cache(MC::get_instance());
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
		Config::clear_cache();
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
		Config::clear_cache();
	}
	/*
	 * Called after a migration rollback has occurred, just before exit()
	 * @param $migration_name The name of the migration that caused the rollback
	 */

	public static function post_rollback_hook($migration_name) {

	}
}
