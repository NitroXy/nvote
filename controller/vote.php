<?php

$admin_mode = (isset($_GET['admin']) && $u && $u->admin );

if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !($category && (($u && $u->admin) || $category->vote_open)) ){
		$view = '../view/bad_cat.php';
		return;
	}

	if( $u && isset($_POST['vote']) ) {
		//handle votes
		//Fulhack: nollställ alla röster i kategorin från användaren först
		$stmt = $db->prepare('update vote set entry_id = NULL where category_id = ? and user_id = ?');
		$stmt->bind_param('ii', $category->category_id(), $u->user_id());
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

			$flash['success'] = "Rösterna sparades";
		} catch (ValidationException $e) {
			$e->flash();
		}
		redirect("vote/$cat_id");
	}

	$selection = array('category_id' => $cat_id);
	if(! $admin_mode ) $selection['disqualified'] = 0;
	$entry = Entry::selection($selection);

	$vote_map = array();

	$user_votes = array_map(function($v) {
		global $vote_map;
		$vote_map[$v->entry_id] = $v->score;
		return $v->entry_id;
	}, Vote::selection(array('category_id' => $category->category_id, 'user_id' => $u->user_id)));

	$entries_unvoted = array();
	$entries_voted = array();
	foreach($entry as $e) {
		if(in_array($e->entry_id, $user_votes)) {
			$entries_voted[$vote_map[$e->entry_id]] = $e;
		} else {
			$entries_unvoted[] = $e;
		}
	}

	ksort($entries_voted);

	shuffle($entries_unvoted);

	$entry = array_merge($entries_voted, $entries_unvoted);

	$view = '../view/list_entry.php';
} else {
	$selection = array('event' => $event);
	if(!$admin_mode) $selection['vote_open'] = true;
	$category = Category::selection($selection);
	$view = '../view/list_cat.php';
}
