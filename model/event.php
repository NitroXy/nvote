<?php

class Event extends BasicObject {
	protected static function table_name(){
		return 'event';
	}

	public function info() {
		return NXAPI::event_info(array('event' => $this->short_name));
	}
}
