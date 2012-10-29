<h1><?=$category->name?></h1>
<?php if($u && $u->admin && !$admin_mode) { ?>
	<a href="/vote/<?=$category->id?>?admin">Till adminläge</a>
<?php } else { ?>
	<a href="/vote/<?=$category->id?>">Till normalläge</a>
<?php }?>

<?php foreach ( $entry as $cur ){ ?>
<div class="entry">
<?php if($cur->has_screenshot()) { ?>
	<img class="screenshot" src="/screenshot/<?=$cur->entry_id?>"/>
<?php } ?>
	<h3 class="title"><a href="/download/<?=$cur->entry_id?>"><?=str_replace(' ', '&nbsp;', $cur->title)?></a></h3>
	<span class="author">av <b><?=$cur->author?></b> (rev <?=$cur->get_revision()?>)</span>
	<div class="description"><?=str_replace("\n", "<br/>", $cur->description)?></div>
	<p class="clear" />
	<?php if($admin_mode) { ?>
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
