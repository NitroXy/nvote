<?php

class Category extends BasicObject {
	protected static function table_name(){
		return 'category';
	}

	public function dirname() {
		return str_replace( "/", "_", $this->name);
	}
}
