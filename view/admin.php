<script type='text/javascript' src='/js/admin.js'></script>
<noscript>
	<p class='error'>Slå på javascript eller gå och lek med en katt.</p>
</noscript>

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
	<?php foreach ($category as $cur){ ?>
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
	<form method='post' action='/admin/create_category'>
		<p>
			<label for='new_name'>Namn:</label>
			<input type='text' id='new_name' name='name'/>
		</p>
		<p>
			<label for='new_description'>Beskrivning:</label><br/>
			<textarea name='description' id='new_description' cols='80' rows = '5'></textarea>
		</p>

		<input type='submit' value='Skapa'/>
	</form>
<h2>Klona</h2>
<form method='post' action='/admin/clone'>
	<p>
		<i>Kopiera alla kategorier från tidigare event</i>
	</p>
	<select name='event'>
<?php
foreach(Event::selection() as $e) { ?>
	<option value='<?=$e->short_name?>'><?=$e->name?></option>
<?php } ?>
</select>
<input type='submit' value='Klona'/>
</form>
</div>

<h1>Maintainance</h1>
<div class='block'>
	<h2>Slides</h2>
	<p style="margin-bottom: 20px"><a href="/generate">Generera slides</a> (processen tar tid, resultatet hamnar i <?=$event?>/final/$cat)</p>
	<h2>Results.txt</h2>
	<p><a href="/results.txt">results.txt</a></p>
</div>
