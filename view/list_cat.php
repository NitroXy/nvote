<h1>Kategorier</h1>

<div class='big_links'>
<?php foreach ( $category as $cur ){ ?>
	 <a href="/vote/<?=$cur->id?>"><?=$cur->name?></a>
<?php } ?>
</div>
