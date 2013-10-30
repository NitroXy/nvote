<?php

class Category extends ValidatingBasicObject {
	/* statuses */
	public static $HIDDEN = 'hidden';
	public static $VISIBLE = 'visible';
	public static $ENTRY_OPEN = 'entry_open';
	public static $ENTRY_CLOSED = 'entry_closed';
	public static $VOTING_OPEN = 'voting_open';
	public static $VOTING_CLOSE = 'voting_closed';
	public static $RESULTS_PUBLIC = 'results_public';

	public static $entries_show_statuses = array('voting_open', 'voting_closed', 'results_public');

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
		return $this->status != Category::$HIDDEN;
	}

	public function voting_open() {
		return $this->status == Category::$VOTING_OPEN;
	}

	public function entry_open() {
		return $this->status == Category::$ENTRY_OPEN;
	}

	public function results_public() {
		return $this->status == Category::$RESULTS_PUBLIC;
	}

}
