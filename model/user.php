<?php

class User extends BasicObject {
	protected static function table_name(){
		return 'user';
	}

	public static function find_or_create($user) {
		$user = static::one(array("username" => $user));
		if(!$user) {
			
		} else {
			return $user;
		}
	}

	//private static function fetch_user_data(
}
