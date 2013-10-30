<?php

need_admin();

$method = $_SERVER['REQUEST_METHOD'];

$global_event = $event;
if(isset($_SESSION['admin_selected_event'])) {
	$event = Event::from_id($_SESSION['admin_selected_event']);
	if($event == null) {
		unset($_SESSION['admin_selected_event']);
		flash('error', "Ogiltligt event valt för redigering, återställer till global inställning");
		$event = $global_event;
	}
}

$categories = $event->Category;

$new_category = new Category(array('event_id' => $event->id));

$arg = isset($_GET['arg']) ? $_GET['arg'] : null;

if ( $method == 'POST' ) {
	if(isset($_POST['select_event'])) {
		$_SESSION['admin_selected_event'] = $_POST['select_event'];
		unset($_POST['select_event']);
	}
	switch ( $arg ) {
	case 'category_status':
		$category = Category::from_id($_POST['id']);
		if(!$category) {
			flash_json('error', "Okänt kategori-id");
		}
		$category->status = $_POST['value'];
		$category->commit(false);
		flash_json('success', "Ändringarna sparades");
		break;
	case 'category/create':
		try {
			$category = Category::update_attributes($_POST['Category'], array('commit' => false));
			$category->commit();
			flash('success', "Skapade kategori {$category->name}");
			redirect('admin');
		} catch (ValidationException $e) {
			flash('error', "Kunde inte spara kategorin, något fält saknas");
		}
		break;
	case 'category/update':
		try {
			$category = Category::update_attributes($_POST['Category'], array('commit' => false));
			$category->commit();
			flash('success', "Ändringarna sparades");
			redirect('admin');
		} catch (ValidationException $e) {
			flash_validation_errors($e, "Kunde inte spara ändringarna");
		}
		break;
	case 'category/delete':
		$category = Category::from_id($_POST['id']);
		if(Entry::count(array('category_id' => $category->category_id)) > 0) {
			flash('error', "Kan inte radera kategori med bidrag");
			redirect('admin');
		}
		$category->delete();
		flash('success', "Kategorin togs bort");
		redirect('admin');
		break;
	case 'event/change':
		/* Validate that the event exist: */
		$event = Event::from_id($_POST['event']);
		if($event != null) {
			Setting::set('event', $event->short_name);
			flash('success', "Ändrade aktivt event");
		} else {
			flash('error', "Kunde inte hitta valt event");
		}
		break;
	case 'event/update':
		try {
			$event = Event::update_attributes($_POST['Event'], array('commit' => false));
			$event->commit();
			flash('success', "Ändringarna sparades");
			redirect('admin');
		} catch (ValidationException $e) {
			flash_validation_errors($e, "Kunde inte spara ändringarna");
		}
		break;
	case 'event/create':
		$api_event = NXAPI::event_info(array('event' => $_POST['event']));
		if($api_event == null) {
			flash('error', "Kan inte hitta eventet ".$_POST['event']." via api:et");
			redirect('admin');
		}
		$new_event = new Event(array('short_name' => $_POST['event'], 'name' => $api_event->name));
		$new_event->location = null; //TODO: Read this data from event_info for the event

		if(trim($_POST['clone_event']) != false) {
			$clone_event = Event::from_id($_POST['clone_event']);
			if(!$clone_event) {
				flash('error', "Kunde inte hitta eventet att klona");
				redirect('admin');
			}
		} else {
			$clone_event = null;
		}

		if($clone_event) {
			$new_event->general_rules = $clone_event->general_rules;
			$new_event->frontpage_text = $clone_event->frontpage_text;
			if($new_event->location == null) $new_event->location = $clone_event->location;
		}
		try {
			$new_event->commit();
			flash('success', "Skapade eventet {$new_event->name}");
		} catch (ValidationException $e) {
			flash_validation_errors($e, "Kunde inte skapa eventet:");
		}

		if($clone_event) {
			foreach(Category::selection(array('event' => $clone_event->short_name)) as $cat) {
				$c = $cat->duplicate();
				$c->event = $new_event->short_name;
				$c->commit(false);
			}
		}
		redirect('admin');
		break;
	case '':
		redirect('admin');
		break;
	default:
		flash('error', "Invalid command $arg");
		redirect('admin');
	}
} else {
	switch($arg) {
		case 'categories':
			output_json($categories);
			exit;
		case 'category/edit':
			$selected_category = Category::from_id($_GET['id']);
			break;
	}
}
