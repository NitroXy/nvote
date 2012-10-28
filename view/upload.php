<?php if ( !isset($entry) ){ ?>
<h1>Lämna in bidrag</h1>
<?php } else { ?>
<h1>Redigera bidrag</h1>
<?php } ?>

<form action="/upload" method="post" id="upload" enctype="multipart/form-data">
	<?php if ( isset($entry) ){ ?>
	<input type="hidden" name="entry_id" value="<?=$entry->entry_id?>" />
	<?php } else { ?>
	<div>
		<label for="category">Kategori:</label>
		<select name="category" id="category">
			<?php foreach ( $category as $cur ){ ?>
			<option value="<?=$cur->category_id?>"><?=$cur->name?></option>
			<?php } ?>
		</select>
		<span id="cat_description"></span>
	</div>
	<?php } ?>

	<div>
		<label for="title">Titel:</label>
		<input type="text" name="title" id="title" value="<?=$title?>" />
		<span>Bidragets titel.</span>
	</div>

	<div>
		<label for="author">Skapare:</label>
		<input type="text" name="author" id="author" value="<?=$author?>" />
		<span>Uppge grupp eller alla de som bidragit till skapandet</span>
	</div>

	<div>
		<label for="description">Beskrivning:</label>
		<textarea name="description" id="description"><?=$description?></textarea>
		<span></span>
	</div>

	<?php if ( $allow_file ){ ?>
	<div>
		<label for="filename">Filnamn:</label>
		<input type="file" name="file" id="filename" />
		<span>Max filstorlek <?=ini_get('upload_max_filesize')?>.</span>
	</div>
	<?php } ?>

	<input type="submit" name="upload" id="upload" value="Ladda upp" />
</form>
<?php

unset($_SESSION['title']);
unset($_SESSION['author']);
unset($_SESSION['description']);
