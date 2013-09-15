<script type='text/javascript' src='/js/admin.js'></script>
<script type='text/javascript' src='/js/preview.js'></script>
<noscript>
	<p class='error'>Slå på javascript eller gå och lek med en katt.</p>
</noscript>

<h1>Event</h1>

<div class='block'>
	<h2><?=$event->name?></h2>
<?php
Form::from_object($event, function($f) {
	$f->checkbox('visible', "Synlig:");
	$f->textarea('frontpage_text', "Text på förstasidan:", array(
		'cols' => 80, 'rows'=>5, 'class' => 'preview blockify', 'hint' => "<div class='preview_target'/>"
	));
	$f->textarea('general_rules', "Generella regler:", array(
		'cols' => 80, 'rows'=>5, 'class' => 'preview', 'hint' => "<div class='preview_target'/>"
	));
	$f->submit("Spara ändringar");
}, array('action' => "/admin/event/update"));
?>
</div>
<div class='block'>
	<!--
	<h2>Byt aktivt event</h2>
<?php
Form::from_array("change_active", array('event' => $event->id), function($f) {
	$f->select(FormSelect::from_array_callback($f, 'event', Event::selection(), function($e) {
		return array($e->id, $e->name);
	}, "Event"));
	$f->submit("Ändra");
}, array('action'=> "/admin/event/change", 'layout' => 'plain'));
?>
<hr/>
<br/>
-->
	<h2>Skapa nytt event</h2>
<?php
Form::from_array("new_event", array(), function($f) {
	$f->select(FormSelect::from_array_callback($f, 'event', Event::uncreated(), function($e) {
		return array($e->short_name, $e->name);
	}, "Event"));

	$f->select(FormSelect::from_array_callback($f, 'clone_event', array_merge(
		array(
			new Event(array('name' => "Inget"))
		), Event::selection()), function($e) {
		return array($e->id, $e->name);
	}, "Kopiera event:"));

	$f->submit("Skapa event");
}, array('action' => '/admin/event/create'));
?>

</div>

<h1>Kategorier</h1>
<div class='block'>
<h2>Befintliga</h2>
<table>
	<thead>
		<tr>
			<th>Kategori</th>
			<th>Inlämning</th>
			<th>Röstning</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($categories as $cur){ ?>
		<tr>
			<td><?=$cur->name?></td>
			<td>
				<form><input type="checkbox" class='cat_status' data-what='entry' data-id='<?=$cur->category_id?>' <?=$cur->entry_open ? ' checked="checked"' : '' ?>/></form>
			</td>
			<td>
				<form><input type="checkbox" class='cat_status' data-what='vote' data-id='<?=$cur->category_id?>' <?=$cur->vote_open  ? ' checked="checked"' : '' ?>/></form>
			</td>
			<td style="text-align: right;">
				<?php if(Entry::count(array('category_id' => $cur->category_id)) == 0) {
					echo simple_action("/admin/category/delete", "Radera", array('id' => $cur->category_id), array('confirm' => "Är du säker på att du vill radera kategorin?"));
					echo " | ";
				} ?>
				<a href='/admin/category/edit?id=<?=$cur->category_id?>#edit_category'>Ändra</a>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<?php if(isset($selected_category)) { ?>
<a name="edit_category"/>
<h2>Redigera kategori: <?=$selected_category->name?></h2>
	<?php
		Form::from_object($selected_category, function($f) {
			$f->text_field('name', "Namn:");
			$f->textarea('description', "Beskrivning:", array(
				'cols' => 80, 'rows'=>5, 'class' => 'preview', 'hint' => "<div class='preview_target'/>"
			));
			$f->textarea('rules', "Regler:", array(
				'cols' => 80, 'rows'=>5, 'class' => 'preview', 'hint' => "<div class='preview_target'/>"
			));
			$f->submit("Spara ändringar");
		}, array(
			'action' => '/admin/category/update'
		));
	?>
	</div>
<?php } ?>

<h2>Ny kategori</h2>
<?php
	Form::from_object($new_category, function($f) use ($event) {
		$f->hidden_field('event_id', $event->id);
		$f->text_field('name', "Namn:");
		$f->textarea('description', "Beskrivning:", array(
			'cols' => 80, 'rows'=>5, 'class' => 'preview', 'hint' => "<div class='preview_target'/>"
		));
		$f->textarea('rules', "Regler:", array(
			'cols' => 80, 'rows'=>5, 'class' => 'preview', 'hint' => "<div class='preview_target'/>"
		));
		$f->submit("Skapa");
	}, array(
		'action' => '/admin/category/create'
	));
?>
</div>

<h1>Maintainance</h1>
<div class='block'>
	<h2>Slides</h2>
	<p style="margin-bottom: 20px"><a href="/generate">Generera slides</a> (processen tar tid, resultatet hamnar i <?=$event->short_name?>/final/$cat)</p>
	<h2>Results.txt</h2>
	<p><a href="/results.txt">results.txt</a></p>
</div>
