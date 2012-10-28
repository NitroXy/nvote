<?php /* -*- mode: html; -*- */ ?>
<h1>Mina inlämnade bidrag</h1>

<?php foreach ( $entry as $cur ){ ?>
<div class="entry">
	<h2><?=$cur->title?></h2>
	<span class="author"><?=$cur->author?></span>
	<span class="description"><?=$cur->description?></span>
</div>
<?php } ?>
