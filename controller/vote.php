<?php

$admin_mode = (isset($_GET['admin']) && Can::administrate() );

if ( isset($_GET['arg']) ){
	$cat_id = $_GET['arg'];
	$category = Category::from_id($cat_id);
	if ( !($category && (Can::administrate() || $category->vote_open)) ){
		$view = '../view/bad_cat.php';
		return;
	}

	if( Can::vote() && isset($_POST['vote']) ) {
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

	if(!$admin_mode) {
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
	} else {
		usort($entry, function($a, $b) {
			return $b->score() - $a->score();
		});
	}

	$view = '../view/list_entry.php';
} else {
	$selection = array('event' => $event);
	if(!$admin_mode) $selection['vote_open'] = true;
	$category = Category::selection($selection);
	$view = '../view/list_cat.php';
}
