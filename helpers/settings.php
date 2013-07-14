<?php

class Settings {
	static private $settings = array();
	static private $sensitive = array();

	static public function load($filename){
		include($filename);

		/* test config version */
		if ( !isset($version) ){
			trigger_error("$filename is missing \$version, defaulting to 0");
			$version = 0;
		}

		/* store configuration */
		foreach($settings as $key => $value){
			if ( array_key_exists($key, static::$settings) ){
				static::$settings[$key] = array_merge(static::$settings[$key], $value);
			} else {
				static::$settings[$key] = $value;
			}
		}

		/* store sensitive keys */
		if ( isset($sensitive) ){
			static::$sensitive = array_merge(static::$sensitive, $sensitive);
		}
	}

	static public function unset_sensitive(){
		foreach ( static::$sensitive as $key ){
			unset(static::$settings[$key]);
		}
	}

	static public function get($key, $default=null){
		$invalid = func_num_args() == 1 ? (function($str){ throw new Exception($str); }) : (function($str) use ($default) { return $default; });
		$path = explode('.', $key);
		$cur = static::$settings;
		foreach ( $path as $segment ){
			if ( !is_array($cur) ){
				return $invalid("$key doesn't exist");
			}

			if ( array_key_exists($segment, $cur) ){
				$cur = $cur[$segment];
			} else {
				return $invalid("$key doesn't exist");
			}
		}
		return $cur;
	}
};
