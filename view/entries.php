<h1><?=$category->name?></h1>

<?php foreach ( $entry as $cur ){ ?>
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

	<div class="score_display"><?=$cur->score()?></div>

	<p class="clear" />
	<?php if(admin_mode()) { ?>
	<form action="/disqualify" method="post">
		<input type="hidden" name="entry_id" value="<?=$cur->id?>"/>
		<input type="hidden" name="cat_id" value="<?=$category->id?>"/>
		<?php if(!$cur->disqualified) { ?>
			<input type="hidden" name="new_value" value="1"/>
			<label for="reason">Anledning: </label>
			<input type="text" name="reason" id="reason"/>
			<input type="submit" value="Diskvalifiera"/>
		<?php } else { ?>
			<input type="hidden" name="new_value" value="0"/>
			<input type="submit" value="Ta bort diskvalifiering"/>
		<?php } ?>
	</form>
	<?php } ?>
</div>
<?php } ?>
