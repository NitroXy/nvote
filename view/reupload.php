<h1>Ladda upp ny version</h1>

<div class='block'>
<form action="/reupload" method="post" id="upload" enctype="multipart/form-data">
	<input type="hidden" name="entry_id" value="<?=$entry_id?>" />
	<div>
		<label for="filename">Filnamn:</label>
		<input type="file" name="file" id="filename" />
		<span class="help">Max filstorlek <?=ini_get('upload_max_filesize')?>.</span>
	</div>

	<input type="submit" name="upload" id="upload" value="Ladda upp" />
</form>
</div>
