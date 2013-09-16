<?php if ( !isset($entry) ){ ?>
<h1>Lämna in bidrag</h1>
<?php } else { ?>
<h1>Redigera bidrag</h1>
<?php } ?>

<div class='block'>
<form action="/upload" method="post" id="upload" enctype="multipart/form-data">
	<?php if ( isset($entry) ){ ?>
	<input type="hidden" name="entry_id" value="<?=$entry->entry_id?>" />
	<?php } else { ?>
	<div>
		<label for="category">Kategori:</label>
		<select name="category" id="category">
			<?php foreach ( $categories as $cur ){ ?>
			<option value="<?=$cur->category_id?>"<?=$cur->category_id == $selected_category ? ' selected="selected"' : ''?>><?=$cur->name?></option>
			<?php } ?>
		</select>
		<p id="cat_description" class="help"></p>
	</div>
	<?php } ?>

	<div>
		<label for="title">Titel:</label>
		<input type="text" name="title" id="title" value="<?=$title?>" />
		<span class="help">Bidragets titel.</span>
	</div>

	<div>
		<label for="author">Skapare:</label>
		<input type="text" name="author" id="author" value="<?=$author?>" />
		<span class="help">Uppge grupp eller alla de som bidragit till skapandet</span>
	</div>

	<div>
		<label for="description">Beskrivning:</label><br/>
		<textarea name="description" id="description"><?=$description?></textarea>
		<span class="help"></span>
	</div>

	<?php if ( $allow_file ){ ?>
	<div>
		<label for="filename">Filnamn:</label>
		<input type="file" name="file" id="filename" />
		<span class="help">Max filstorlek <?=ini_get('upload_max_filesize')?>.</span>
	</div>
	<?php } ?>

	<div>
		<label for="filename">Screenshot:</label>
		<input type="file" name="screenshot" id="screenshot" />
		<span class="help">Genereras automatiskt för bilder.</span>
	</div>

	<input type="submit" name="upload" id="upload" value="Ladda upp" />
</form>
</div>
