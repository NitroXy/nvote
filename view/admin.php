<h1>Kategorier</h1>

<form action="/admin/open" method="post">
	<p>Kryssa i för att aktivera röstning.</p>
	<ul>
		<?php foreach ($category as $cur){ ?>
		<li><input type="checkbox" name="<?=$cur->category_id?>" <?=$cur->vote_open ? ' checked="checked"' : '' ?>/><?=$cur->name?></li>
		<?php } ?>
	</ul>
	<input type="submit" value="spara" />
</form>
