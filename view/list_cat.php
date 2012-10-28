<h1>Kategorier</h1>

<ul>
<?php foreach ( $category as $cur ){ ?>
	 <li><a href="/vote/<?=$cur->id?>"><?=$cur->name?></a></li>
<?php } ?>
</ul>
