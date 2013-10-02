<h1><?=$category->name?></h1>

<form action="/vote/<?=$category->id?>" method="post" id="vote_form">
<div class='block'>
	<input type="hidden" name="vote" value="do"/>
	<div class="vote vote_header">
		<noscript><p><input type="submit" value="Spara röster" class="vote_button"/></p></noscript>
		<label>Blank: </label>
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
		Rösta genom att ge bidragen 1-5 poäng, 5 poäng är bäst.<br/>
		Du kan inte ge två bidrag samma poäng.<br/>
		<p class="clear"/>
</div>

<?php foreach ( $entry as $cur ){ ?>
<div class="block entry">
<?php if($cur->has_screenshot()) { ?>
	<a href="/download/<?=$cur->entry_id?>"><img class="screenshot" src="/screenshot/<?=$cur->entry_id?>"/></a>
<?php } ?>
	<h3 class="title"><a href="/download/<?=$cur->entry_id?>"><?=str_replace(' ', '&nbsp;', $cur->title)?></a></h3>
	<span class="author">av <b><?=$cur->author?></b> (rev <?=$cur->get_revision()?>)</span>
	<div class="description"><?=str_replace("\n", "<br/>", $cur->description)?></div>

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

	<p class="clear" />
</div>
<?php } ?>

<noscript>
<div class='block'>
		<input type="submit" value="Spara röster" class="vote_button"/>
</div>
</noscript>
</form>

<script type="text/javascript">
	$(function() {
		$(".score").click(function() {
			var sel_val = $(this).val();
			$(".score_for_" + sel_val).each(function(i, v) {
				$(v).attr('checked', false);
			});
			$(this).attr('checked', true);

			vote();
		})
	})

	function vote() {
		$.post('/vote/<?=$category->id?>', $("#vote_form").serialize() + "&ajax=1",
			function(data) {
				flash_data(data)
			}
		);
	}
</script>
