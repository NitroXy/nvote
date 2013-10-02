<?php

$show_results_txt = false;

if(!Can::vote()) {
	$error_title = "Kan inte rösta.";
	$error_msg = "Du har inte rätt att rösta.";
	$view = '../view/error.php';
	return;
}

if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !($category && $category->voting_open() ) ){
		$view = '../view/bad_cat.php';
		return;
	}

	if( Can::vote() && isset($_POST['vote']) ) {
		//handle votes
		//Fulhack: nollställ alla röster i kategorin från användaren först
		$stmt = $db->prepare('update vote set entry_id = NULL where category_id = ? and user_id = ?');
		$cat = $category->category_id();
		$uid = $u->user_id();
		$stmt->bind_param('ii', $cat, $uid);
		$stmt->execute();
		$stmt->close();

		try {
			for($i = 1; $i <= 5; ++$i) {
				if(isset($_POST["score_$i"])) {
					$entry_id = $_POST["score_$i"];
					$entry_id = ($entry_id < 1) ? null : $entry_id;
					$vote = Vote::find_or_new($category, $u, $i);
					$vote->entry_id = $entry_id;
					$vote->commit();
				}
			}

			if(!isset($_POST['ajax'])) {
				$flash['success'] = "Dina röster har sparats";
			} else {
				flash_json('success', "Dina röster har sparats");
			}
		} catch (ValidationException $e) {
			if(!isset($_POST['ajax'])) {
				$e->flash();
			} else {
				flash_json('error', "Ett fel uppstod: ".$e->getMessage());
			}
		}
		redirect("vote/$cat_id");
	}

	$entry = Entry::selection(array('category_id' => $cat_id, 'disqualified' => 0));

	$vote_map = array();
	$blank_votes = array();

	if( $u ) {
		foreach(Vote::selection(array('category_id' => $category->category_id, 'user_id' => $u->user_id)) as $v) {
			$vote_map[$v->entry_id] = $v->score;
			if($v->entry_id == null) $blank_votes[] = $v->score;
		}

		shuffle($entry);
		usort($entry, function($a, $b) {
			global $u;
			return $b->user_vote($u) - $a->user_vote($b);
		});
	}

	$view = '../view/vote.php';
} else {
	$selection = array();
	$category = $event->Category(array('status' => Category::$VOTING_OPEN));
	if(count($category) == 0) redirect("");
	$view = '../view/list_cat.php';
}
