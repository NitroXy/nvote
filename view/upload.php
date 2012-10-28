<h1>Lämna in bidrag</h1>

<form action="/upload" method="post" id="upload" enctype="multipart/form-data">
	<div>
		<label for="category">Kategori:</label>
		<select name="category" id="category">
			<?php foreach ( $category as $cur ){ ?>
			<option value="<?=$cur->category_id?>"><?=$cur->name?></option>
			<?php } ?>
		</select>
		<span id="cat_description"></span>
	</div>

	<div>
		<label for="title">Titel:</label>
		<input type="text" name="title" id="title" value="<?=sessiondata('title')?>" />
		<span>Bidragets titel.</span>
	</div>

	<div>
		<label for="author">Skapare:</label>
		<input type="text" name="author" id="author" value="<?=sessiondata('author', $u->name)?>" />
		<span>Uppge grupp eller alla de som bidragit till skapandet</span>
	</div>

	<div>
		<label for="description">Beskrivning:</label>
		<textarea name="description" id="description"><?=sessiondata('description')?></textarea>
		<span></span>
	</div>

	<div>
		<label for="filename">Filnamn:</label>
		<input type="file" name="file" id="filename" />
		<span>Max filstorlek <?=ini_get('upload_max_filesize')?>.</span>
	</div>

	<input type="submit" name="upload" id="upload" value="Ladda upp" />
</form>
<?php

unset($_SESSION['title']);
unset($_SESSION['author']);
unset($_SESSION['description']);
