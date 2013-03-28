<?php if(Can::administrate() && !$admin_mode) { ?>
	<a href="/vote?admin">Till adminläge</a>
<?php } else if(Can::administrate()) { ?>
	<a href="/vote">Till normalläge</a>
<?php }?>
<h1>Kategorier</h1>

<ul>
<?php foreach ( $category as $cur ){ ?>
	 <li><a href="/vote/<?=$cur->id?>"><?=$cur->name?></a></li>
<?php } ?>
</ul>
