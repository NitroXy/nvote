<?php /* -*- mode: html; -*- */ ?>
<h1>Mina inlämnade bidrag</h1>

<?php foreach ( $entry as $cur ){ ?>
<div class="entry">
	<h2 class="title"><?=$cur->title?></h2>
	<span class="author">av <b><?=$cur->author?><b/></span>

	<div class="category">Kategori: <?=$cur->Category->name?></div>
	<div class="description"><?=$cur->description?></div>
	<ul>
		<li><a href="/edit/<?=$cur->entry_id?>">Redigera</a></li>
		<li><a href="/upload/<?=$cur->entry_id?>">Ladda upp ny version</a></li>
	</ul>
</div>
<?php } ?>
