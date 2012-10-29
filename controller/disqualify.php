<?php

need_admin();

if(isset($_POST['entry_id'])) {
	$entry = Entry::from_id($_POST['entry_id']);
	$entry->disqualified = $_POST['new_value'];
	if(isset($_POST['reason'])) $entry->disqualified_reason = $_POST['reason'];
	$entry->commit();
	if($entry->disqualified) {
		flash('success', "Diskvalifierade bidrag {$entry->title}");
	} else {
		flash('success', "Av-diskvalifierade bidrag {$entry->title}");
	}
	redirect("vote/{$_POST['cat_id']}");
}
