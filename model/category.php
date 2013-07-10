<?php

class Category extends ValidatingBasicObject {
	protected function validation_hooks() {
		$this->validate_presence_of('event');
		$this->validate_presence_of('name');
		$this->validate_presence_of('description');
		$this->validate_presence_of('rules');
	}

	protected static function table_name(){
		return 'category';
	}

	public function dirname() {
		return str_replace( "/", "_", $this->name);
	}
}
