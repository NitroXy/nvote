<?php

class Entry extends BasicObject {
	protected static function table_name(){
		return 'entry';
	}

	public function upload($file){
		$id = $this->entry_id;
		$original = $file['name'];
		$revision = $this->get_revision() + 1;
		$filename = $this->generate_filename($original, $revision);

		/* store uploaded file */
		if ( !move_uploaded_file($file['tmp_name'], $filename) ){
			flash('error', 'Misslyckades att spara filen, försök igen och kontakta crew om du forfarande misslyckas.');
			return false;
		}

		global $db;
		$stmt = $db->prepare('INSERT INTO `revision` (`entry_id`, `revision`, `filename`, `original`) VALUES (?, ?, ?, ?)');
		if ( !$stmt ){
			flash('error', "Failed to prepare query: {$db->error}");
			return false;
		}
		$stmt->bind_param('iiss', $id, $revision, $filename, $original);
		if ( !$stmt->execute() ){
			flash('error', "Failed to execute query: {$stmt->error}");
			return false;
		}
		$stmt->close();
		return true;
	}

	private function generate_filename($original, $revision){
		global $dir;
		$ext = pathinfo($original, PATHINFO_EXTENSION);
		$username = preg_replace('/[^a-zA-Z0-9]/', '_', $this->User->username);
		return "{$dir}/{$this->Category->name}/{$this->title}_{$username}_r{$revision}.{$ext}";
	}

	private function get_revision(){
		global $db;
		$id = $this->entry_id;
		$stmt = $db->prepare('SELECT IFNULL(MAX(`revision`)+1,1) FROM `revision` WHERE `entry_id` = ?');
		$stmt->bind_param('i', $id);
		$stmt->bind_result($revision);
		if ( !$stmt->execute() ){
			flash('error', "Failed to execute query: {$stmt->error}");
			return false;
		}
		return $revision;
	}
}
