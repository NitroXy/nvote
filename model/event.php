<?php

class Event extends ValidatingBasicObject {

	protected function validation_hooks() {
		$this->validate_presence_of('name');
		$this->validate_presence_of('short_name');
		$this->validate_uniqueness_of('short_name');
	}

	protected static function table_name(){
		return 'event';
	}

	public function info() {
		return NXAPI::event_info(array('event' => $this->short_name));
	}

	public static function uncreated() {
		$events = array_map(function($e) {
			return $e->short_name;
		}, Event::selection());

		return array_filter(NXAPI::events(), function($e) use ($events) {
			return !in_array($e->short_name, $events);
		});
	}
}
