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
			));
			$user->commit();
		}
		return $user;
	}

	public function __get($attr) {
		global $event;
		switch($attr) {
		case "crew_groups":
			return NXAPI::crew_groups(array('event' => $event, 'user' => $this->user_id));
		case "has_ticket":
			return (NXAPI::ticket_status(array('event' => $event, 'user' => $this->user_id)) == 'paid');
		case "is_crew":
			return NXAPI::is_crew(array('event' => $event, 'user' => $this->user_id));
		default:
			return parent::__get($attr);
		}
	}
}
