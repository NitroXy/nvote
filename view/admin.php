<script type='text/javascript' src='/js/admin.js'></script>
<h1>Kategorier</h1>

<form action="/admin/open" method="post">
	<table>
		<thead>
			<tr>
				<th>Kategori</th>
				<th>Inlämning</th>
				<th>Röstning</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($category as $cur){ ?>
			<tr>
				<td><?=$cur->name?></td>
				<td>
					<input type="checkbox" class='cat_status' data-what='submit' data-id='<?=$cur->category_id?>' <?=$cur->entry_open ? ' checked="checked"' : '' ?>/>
				</td>
				<td>
					<input type="checkbox" class='cat_status' what='vote' data-id='<?=$cur->category_id?>' <?=$cur->vote_open  ? ' checked="checked"' : '' ?>/>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>
</form>

<h1>Projektor</h1>
<a href="/generate">Generera slides</a> (processen tar tid, resultatet hamnar i $event/final/$cat)
