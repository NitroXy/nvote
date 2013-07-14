<script type='text/javascript' src='/js/admin.js'></script>
<noscript>
	<p class='error'>Slå på javascript eller gå och lek med en katt.</p>
</noscript>

<h1>Event</h1>
<div class='block'>
	<h2>Byt aktivt event</h2>
<?php
Form::from_array("change_active", array('event' => $event_obj->id), function($f) {
	$f->select(FormSelect::from_array_callback($f, 'event', Event::selection(), function($e) {
		return array($e->id, $e->name);
	}, "Event"));
	$f->submit("Ändra");
}, array('action'=> "/admin/change", 'layout' => 'plain'));
?>
<hr/>
<br/>
	<h2>Skapa nytt event</h2>
<?php
Form::from_array("new_event", array(), function($f) {
	$f->select(FormSelect::from_array_callback($f, 'event', Event::uncreated(), function($e) {
		return array($e->short_name, $e->name);
	}, "Event"));

	$f->select(FormSelect::from_array_callback($f, 'clone_event', array_merge(
		array(
			new Event(array('id' => -1,'name' => "Inget"))
		), Event::selection()), function($e) {
		return array($e->id, $e->name);
	}, "Kopiera event:"));

	$f->submit("Skapa event");
}, array('action' => '/admin/new'));
?>
<hr/>
<br/>

</div>
<div class='block'>
	<h2><?=$event_obj->name?></h2>
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
			<th>Radera</th>
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
			<td style="text-align: center;">
				<?php if(Entry::count(array('category_id' => $cur->category_id)) == 0) {
					echo simple_action("/admin/delete_category", "Radera", array('id' => $cur->category_id), array('confirm' => "Är du säker på att du vill radera kategorin?"));
				} else { ?>
				<span title="Kan ej radera, har kategorier">-</span>
				<?php } ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<h2>Ny kategori</h2>
<?php
	Form::from_object($category, function($f) use ($event) {
		$f->hidden_field('event', $event);
		$f->text_field('name', "Namn:");
		$f->textarea('description', "Beskrivning:", array('cols' => 80, 'rows'=>5, 'tworows' => true));
		$f->textarea('rules', "Regler:", array('cols' => 80, 'rows'=>5, 'tworows' => true));
		$f->submit("Skapa");
	}, array(
		'layout' => 'p',
		'action' => '/admin/create_category'
	));
?>
</div>

<h1>Maintainance</h1>
<div class='block'>
	<h2>Slides</h2>
	<p style="margin-bottom: 20px"><a href="/generate">Generera slides</a> (processen tar tid, resultatet hamnar i <?=$event?>/final/$cat)</p>
	<h2>Results.txt</h2>
	<p><a href="/results.txt">results.txt</a></p>
</div>
