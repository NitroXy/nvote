<?php
/**
 * Class for controlling rights
 */
class Can {

	public static function vote() {
		global $u;
		if(!$u) return false;
		return $u->has_ticket;
	}

	public static function submit() {
		global $u;
		if(!$u) return false;
		return $u->has_ticket;
	}

	public static function administrate() {
		global $crew_groups, $u;
		if(!$u) return false;
		return self::has_crew_groups($u, $crew_groups);
	}

	private static function has_crew_groups($user, $groups) {
		$user_groups = $user->crew_groups;
		foreach($groups as $g) {
			if(in_array($g, $user_groups)) return true;
		}
		return false;
	}
}
