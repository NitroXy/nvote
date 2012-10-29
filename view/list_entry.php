<h1><?=$category->name?></h1>
<p class="vote_info">
Rösta genom att ge bidragen 1-5 poäng, 5 poäng är bäst.<br/>
Du kan inte ge två bidrag samma poäng.<br/>
När du är klar, kom ihåg att klicka "Spara röster"<br/>
</p>
<?php if($u && $u->admin && !$admin_mode) { ?>
	<a href="/vote/<?=$category->id?>?admin">Till adminläge</a>
<?php } else if($u && $u->admin) { ?>
	<a href="/vote/<?=$category->id?>">Till normalläge</a>
<?php }?>

<?php if(!$admin_mode) { ?>
<form action="/vote/<?=$category->id?>" method="post">
	<input type="hidden" name="vote" value="do"/>
	<input type="submit" value="Spara röster" class="vote_button"/>

	<div class="vote vote_header">
		<strong>Blank: </strong>
		<table class="vote_table">
			<tr>
				<th>5</th>
				<th>4</th>
				<th>3</th>
				<th>2</th>
				<th>1</th>
			</tr>
			<tr>
				<td><input type="radio" name="score_5" value="-1" <?=in_array(1, $blank_votes)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_4" value="-1" <?=in_array(2, $blank_votes)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_3" value="-1" <?=in_array(3, $blank_votes)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_2" value="-1" <?=in_array(4, $blank_votes)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_1" value="-1" <?=in_array(5, $blank_votes)?'checked="checked"':''?>/></td>
			</tr>
		</table>
		</div>
		<p class="clear"/>
<?php } ?>

<?php foreach ( $entry as $cur ){ ?>
<div class="entry <?=($cur->disqualified?'disqualified':'')?>">
	<?php if($cur->disqualified) { ?>
		<h1>Diskvalifierad</h1>
		<strong>Anledning: <?=$cur->disqualified_reason?></strong>
		<p class="clear"/>
	<?php } ?>
<?php if($cur->has_screenshot()) { ?>
	<a href="/download/<?=$cur->entry_id?>"><img class="screenshot" src="/screenshot/<?=$cur->entry_id?>"/></a>
<?php } ?>
	<h3 class="title"><a href="/download/<?=$cur->entry_id?>"><?=str_replace(' ', '&nbsp;', $cur->title)?></a></h3>
	<span class="author">av <b><?=$cur->author?></b> (rev <?=$cur->get_revision()?>)</span>
	<div class="description"><?=str_replace("\n", "<br/>", $cur->description)?></div>

	<?php if(!$admin_mode) { ?>
	<div class="vote">
		<table class="vote_table">
			<tr>
				<th>5</th>
				<th>4</th>
				<th>3</th>
				<th>2</th>
				<th>1</th>
			</tr>
			<tr>
				<td><input type="radio" name="score_5" value="<?=$cur->entry_id?>" class="score score_for_<?=$cur->entry_id?>" <?=($cur->user_vote($u) == 5)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_4" value="<?=$cur->entry_id?>" class="score score_for_<?=$cur->entry_id?>" <?=($cur->user_vote($u) == 4)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_3" value="<?=$cur->entry_id?>" class="score score_for_<?=$cur->entry_id?>" <?=($cur->user_vote($u) == 3)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_2" value="<?=$cur->entry_id?>" class="score score_for_<?=$cur->entry_id?>" <?=($cur->user_vote($u) == 2)?'checked="checked"':''?>/></td>
				<td><input type="radio" name="score_1" value="<?=$cur->entry_id?>" class="score score_for_<?=$cur->entry_id?>" <?=($cur->user_vote($u) == 1)?'checked="checked"':''?>/></td>
			</tr>
		</table>
	</div>
	<?php } else { ?>
		<div class="score_display"><?=$cur->score()?></div>
	<?php } ?>

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

<?php if(!$admin_mode) { ?>
		<input type="submit" value="Spara röster" class="vote_button"/>
	</form>
<?php } ?>

<script type="text/javascript">
	$(function() {
		$(".score").click(function() {
			var sel_val = $(this).val();
			$(".score_for_" + sel_val).each(function(i, v) {
				$(v).attr('checked', false);
			});
			$(this).attr('checked', true);

		})
	})
</script>
