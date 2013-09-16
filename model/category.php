<?php

class Category extends ValidatingBasicObject {

	public static $entries_show_statuses = array('vote_open', 'vote_closed', 'results_public');

	protected function validation_hooks() {
		$this->validate_presence_of('event_id');
		$this->validate_presence_of('name');
		$this->validate_presence_of('description');
	}

	protected static function table_name(){
		return 'category';
	}

	public function dirname() {
		return str_replace( "/", "_", $this->name);
	}

	public function visible() {
		return $this->status != 'hidden';
	}

	public function voting_open() {
		return $this->status == 'voting_open';
	}

	public function entry_open() {
		return $this->status == 'entry_open';
	}

	public function results_public() {
		return $this->status == 'results_public';
	}

}
