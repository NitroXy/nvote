<?php if(Can::administrate() ) { ?>
<div id='admin_toggle'>
<?php if(!$admin_mode) { ?>
		<a href="/vote?admin">Till adminläge</a>
	<?php } else { ?>
		<a href="/vote">Till normalläge</a>
	<?php }?>
</div>
<?php }?>
<h1>Kategorier</h1>

<div class='big_links'>
<?php foreach ( $category as $cur ){ ?>
	 <a href="/vote/<?=$cur->id?>"><?=$cur->name?></a>
<?php } ?>
</div>
