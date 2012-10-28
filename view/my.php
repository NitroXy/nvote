<?php /* -*- mode: html; -*- */ ?>
<h1>Mina inlämnade bidrag</h1>

<?php $category = null; ?>
<?php foreach ( $entry as $cur ){ ?>

<?php if ( $category != $cur->category_id ){ ?>
<h2><?=$cur->Category->name?></h2>
<?php $category = $cur->category_id; } ?>

<div class="entry">
	<h3 class="title"><a href="/download/<?=$cur->entry_id?>"><?=str_replace(' ', '&nbsp;', $cur->title)?></a></h3>
	<span class="author">av <b><?=$cur->author?></b> (rev <?=$cur->get_revision()?>)</span>
	<div class="description"><?=str_replace("\n", "<br/>", $cur->description)?></div>
	<ul>
		<li><a href="/edit/<?=$cur->entry_id?>">Redigera</a></li>
		<li><a href="/reupload/<?=$cur->entry_id?>">Ladda upp ny version</a></li>
		<li><a href="/remove/<?=$cur->entry_id?>">Ta bort</a></li>
	</ul>
</div>
<?php } ?>
<?php if ( count($entry) == 0 ){ ?>
<p>Inga bidrag inlämnade.</p>
<?php } ?>
