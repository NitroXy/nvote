<?php

class User extends BasicObject {
	protected static function table_name(){
		return 'user';
	}

	public static function from_nxuser($nxuser) {
		if($nxuser == null) return null;

		$user = static::one(array("user_id" => $nxuser->user_id));
		if(!$user) {
			$user = new User(array(
				'user_id' => $nxuser->user_id,
				'username' => $nxuser->username,
				'name' => $nxuser->fullname,
				'admin' => false
			));
			$user->commit();
		}
		return $user;
	}
}
