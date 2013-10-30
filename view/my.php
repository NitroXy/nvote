<?php /* -*- mode: html; -*- */ ?>
<h1>Mina inlämnade bidrag</h1>

<?php
foreach ( $entry as $cur ){
	if ( $category != $cur->category_id ){
?>
<h2><?=$cur->Category->name?></h2>
<?php $category = $cur->category_id; } ?>

<div class="block entry <?=($cur->disqualified?'disqualified':'')?>">
	<?php if($cur->disqualified) { ?>
		<h2>Diskvalifierad</h2>
		<strong>Anledning: <?=$cur->disqualified_reason?></strong>
		<p class="clear"/>
	<?php } ?>
	<?php if($cur->has_screenshot()) { ?>
	<a href="/download/<?=$cur->entry_id?>"><img class="screenshot" src="/screenshot/<?=$cur->entry_id?>"/></a>
	<?php } ?>
	<h3 class="title"><a href="/download/<?=$cur->entry_id?>"><?=str_replace(' ', '&nbsp;', $cur->title)?></a></h3>
	<span class="author">av <b><?=$cur->author?></b> (rev <?=$cur->get_revision()?>)</span>
	<div class="description"><?=str_replace("\n", "<br/>", $cur->description)?></div>

	<?php if ( $cur->Category->entry_open() ){ ?>
	<ul>
		<li><a href="/edit/<?=$cur->entry_id?>">Redigera</a></li>
		<li><a href="/reupload/<?=$cur->entry_id?>">Ladda upp ny version</a></li>
		<li><a href="/remove/<?=$cur->entry_id?>">Ta bort</a></li>
	</ul>
	<?php } ?>
	<p class="clear"/>
</div>
<?php
}

if ( count($entry) == 0 ) { ?>
	<div class='block'>Inga bidrag inlämnade.</div>
<?php } ?>
