<?php

class Setting extends BasicObject {
	protected static function table_name() {
		return 'setting';
	}

	/**
	 * Get a setting
	 * @param $default default value to return if not found
	 * @param $write_missing write the default value to the database if the setting is not found
	 */
	public static function get($key, $default=null, $write_missing=false) {
		$setting = Setting::one(array('key' => $key));
		if($setting == null) {
			if($write_missing) {
				$s = new Setting(array('key' => $key, 'value' => $default));
				$s->commit();
			}
			return $default;
		}
		return $setting->value;
	}

	public static function set($key, $value) {
		$setting = Setting::one(array('key' => $key));
		if($setting == null) {
			$setting = new Setting(array('key' => $key));
		}
		$setting->value = $value;
		$setting->commit();
		return $setting;
	}
};
