<?php

class MC extends Memcache implements IBasicObjectMemCache {
	private static $instance;
	private $writes=0;
	private $reads=0;
	private static $tried_to_connect=false;

	private function __construct() {
		global $settings;
		$memcache_settings = $settings['memcache'];
		if(!self::$tried_to_connect) {
			self::$tried_to_connect = true;
			if(@$this->connect($memcache_settings['host'], $memcache_settings['port']) === false) {
				trigger_error("Unable to connect to memcache at ".$memcache_settings['host']." on port ".$memcache_settings['port'], E_USER_WARNING);
				throw new Exception("Failed to connect");
			}
		} else {
			throw new Exception("Failed to connect");
		}
	}

	public static function get_instance() {
		if(empty(self::$instance)) {
			self::$instance = new MC();
		}
		return self::$instance;
	}

	public function writes() {
		return $this->writes;
	}

	public function set($key, $var) {
		parent::set($key, $var);
		$this->writes++;
	}

	public function reads() {
		return $this->reads;
	}

	public function get($key, $flags=null) {
		$this->reads++;
		return parent::get($key, $flags);
	}

	public function delete($key) {
		parent::delete($key);
	}
}
