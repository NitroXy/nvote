<h1>Kategorier</h1>

<form action="/admin/open" method="post">
	<p><b>I</b>nlämning - <b>R</b>östning</p>
	<p><span title="Inlämning öppen">I</span>&nbsp;&nbsp;<span title="Röstning öppen">R</span></p>
	<ul>
		<?php foreach ($category as $cur){ ?>
		<li>
			<input type="checkbox" name="i<?=$cur->category_id?>" <?=$cur->entry_open ? ' checked="checked"' : '' ?>/>
			<input type="checkbox" name="r<?=$cur->category_id?>" <?=$cur->vote_open  ? ' checked="checked"' : '' ?>/>
			<?=$cur->name?>
		</li>
		<?php } ?>
	</ul>
	<input type="submit" value="spara" />
</form>

<h1>Projektor</h1>
<a href="/generate">Generera slides</a> (processen tar tid, resultatet hamnar i $event/final/$cat)
