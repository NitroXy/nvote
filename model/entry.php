<?php

$upload_errors = array(
	UPLOAD_ERR_OK          => "No errors.",
	UPLOAD_ERR_INI_SIZE    => "Larger than upload_max_filesize.",
	UPLOAD_ERR_FORM_SIZE   => "Larger than form MAX_FILE_SIZE.",
	UPLOAD_ERR_PARTIAL     => "Partial upload.",
	UPLOAD_ERR_NO_FILE     => "No file.",
	UPLOAD_ERR_NO_TMP_DIR  => "No temporary directory.",
	UPLOAD_ERR_CANT_WRITE  => "Can't write to disk.",
	UPLOAD_ERR_EXTENSION   => "File upload stopped by extension.",
);

$accepted = array(
	'application/gzip',
	'application/rar',
	'application/zip',
	'audio/mpeg',
	'audio/x-wav',
	'video/mp4',
);

class Entry extends BasicObject {
	protected static function table_name(){
		return 'entry';
	}

	public function upload($file){
		$error = $file['error'];
		$mime = $file['type'];

		/* check for error indicator */
		if ( $error > 0 ){
			global $upload_errors;
			flash('error', $upload_errors[$error]);
			return false;
		}

		/* ensure a file was uploaded */
		if ( $file['size'] == 0 ){
			flash('error', 'Ingen fil.');
			return false;
		}

		/* validate mime-types */
		global $accepted;
		if ( !in_array($mime, $accepted) ){
			flash('error', "Filformatet \"$mime\" accepterades inte.");
			return false;
		}

		$id = $this->entry_id;
		$original = $file['name'];
		$revision = $this->get_revision() + 1;
		$filename = $this->generate_filename($original, $revision);

		/* store uploaded file */
		global $dir;
		$dst = "$dir/$filename";
		if ( !move_uploaded_file($file['tmp_name'], $dst) ){
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
		global $event;
		$ext = pathinfo($original, PATHINFO_EXTENSION);
		$username = preg_replace('/[^a-zA-Z0-9]/', '_', $this->User->username);
		return "upload/{$event}/{$this->Category->dirname()}/{$username}_r{$revision}.{$ext}";
	}

	/**
	 * Fills $location with the local path to the final revision and $filename
	 * with the intended filename.
	 */
	public function final_filename(&$location, &$filename){
		global $db;
		$id = $this->entry_id;
		$stmt = $db->prepare('SELECT `filename` FROM `revision` WHERE `entry_id` = ? ORDER BY `revision` DESC LIMIT 1');
		$stmt->bind_param('i', $id);
		$stmt->execute();
		$stmt->bind_result($location);
		$stmt->fetch();
		$stmt->close();
		$ext = pathinfo($location, PATHINFO_EXTENSION);

		global $dir;
		$author = preg_replace('/[^a-zA-Z0-9]/', '_', $this->author);
		$title = preg_replace('/[^a-zA-Z0-9]/', '_', $this->title);
		$filename = "{$title}_by_{$author}.{$ext}";
	}

	public function get_revision(){
		global $db;
		$id = $this->entry_id;
		$stmt = $db->prepare('SELECT IFNULL(MAX(`revision`),0) FROM `revision` WHERE `entry_id` = ?');
		$stmt->bind_param('i', $id);
		$stmt->bind_result($revision);
		if ( !$stmt->execute() ){
			flash('error', "Failed to execute query: {$stmt->error}");
			return 0;
		}
		$stmt->fetch();
		return (int)$revision;
	}
}
