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


$image_types = array(
	'image/png',
	'image/jpeg',
	'image/gif',
);

$video_types = array(
	'video/mp4',
	'video/avi',
	'video/x-matroska',
	'video/mpeg',
);

$misc_types = array(
	'application/gzip',
	'application/rar',
	'application/zip',
	'application/x-bzip2',
	'application/x-tar',
	'application/x-zip-compressed',
	'application/ogg',
	'audio/mpeg',
	'audio/x-wav',
	'audio/x-flac',
	'text/plain',
);

$accepted = array_merge($image_types, $video_types, $misc_types);

$imagemagick_convert = "convert";

class Entry extends BasicObject {
	protected static function table_name(){
		return 'entry';
	}

	/*protected static function default_order() {
		return ''; //random
	}*/

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
			flash('error', "Filformatet \"$mime\" accepterades inte (prata med scene-crew om du tycker att du borde få ladda upp detta).");
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

		$this->autogenerate_screenshot($dst, $mime);

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

	private function autogenerate_screenshot($original, $mime){
		global $image_types;
		global $video_types;
		global $dir;

		if(in_array($mime, $image_types)) {
			$screenshot_filename = $this->generate_screenshot_filename($original);
			$screenshot_dst = "$dir/$screenshot_filename";

			copy($original, $screenshot_dst);
			$this->screenshot_filename = $screenshot_filename;
			$this->resize_screenshot();
			$this->commit();
		} else if(in_array($mime, $video_types)){
			$screenshot_filename = $this->generate_screenshot_filename($original, "gif");
			$screenshot_dst = "$dir/$screenshot_filename";

			$tmp = "/tmp/nvote-" . getmypid() . "-" . time();
			mkdir($tmp);
			exec("ffmpeg -i " . escapeshellarg($original) . " -bt 15M -s 200x200 -f image2 -r 1/20 $tmp/img%03d.jpg");
			exec("convert -delay 100 -loop 0 $tmp/img00* $screenshot_dst");
			foreach ( glob($tmp. '/*') as $file ) {
				unlink($file);
			}
			rmdir($tmp);
			$this->screenshot_filename = $screenshot_filename;
			$this->resize_screenshot();
			$this->commit();
		}
	}

	private function generate_filename($original, $revision, $ext=null){
		global $event;
		if ( $ext == null ){
			$ext = pathinfo($original, PATHINFO_EXTENSION);
		}
		$username = preg_replace('/[^a-zA-Z0-9]/', '_', $this->User->username);
		return "upload/{$event}/{$this->Category->dirname()}/{$username}_{$this->entry_id}_r{$revision}.{$ext}";
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

	/**
	 * filename for screenshot
	 */
	private function generate_screenshot_filename($original, $ext=null) {
		return str_replace("rscreenshot","screenshot",$this->generate_filename($original, "screenshot", $ext));
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

	public function set_screenshot($file) {
		$error = $file['error'];
		$mime = $file['type'];

		/* ensure a file was uploaded */
		if ( $file['size'] == 0 ){
			return true; //Don't fail, since this is allowed
		}

		/* check for error indicator */
		if ( $error > 0 ){
			global $upload_errors;
			flash('error', $upload_errors[$error]);
			return false;
		}


		/* validate mime-types */
		global $image_types;
		if ( !in_array($mime, $image_types) ){
			flash('error', "Filformatet \"$mime\" accepterades inte som screenshot.");
			return false;
		}

		$id = $this->entry_id;
		$original = $file['name'];
		$filename = $this->generate_screenshot_filename($original);
		/* store uploaded file */
		global $dir;
		$dst = "$dir/$filename";
		if ( !move_uploaded_file($file['tmp_name'], $dst) ){
			flash('error', 'Misslyckades att spara filen, försök igen och kontakta crew om du forfarande misslyckas.');
			return false;
		}

		$this->screenshot_filename = $filename;

		$this->resize_screenshot();

		return true;
	}

	public function has_screenshot() {
		global $dir;
		return (strlen($this->screenshot_filename) > 0 && file_exists("$dir/{$this->screenshot_filename}"));
	}

	public function resize_screenshot() {
		global $imagemagick_convert, $dir;
		system("$imagemagick_convert $dir/{$this->screenshot_filename} -resize 200x200 -background transparent -gravity center -extent 200x200 $dir/{$this->screenshot_filename}");
	}

	public function user_vote($user) {
		$vote = Vote::one(array('user_id' => $user->user_id, 'entry_id' => $this->entry_id));
		if($vote != null) {
			return $vote->score;
		} else {
			return 0;
		}
	}

	public function score() {
		if($this->disqualified) return -1;
		return Vote::sum('score', array('entry_id' => $this->entry_id)) + 0;
	}

	public function mimetype(){
		global $dir;
		$this->final_filename($location, $dst);
		$src = "$dir/$location";
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $src);
		finfo_close($finfo);
		return $mime;
	}
}
