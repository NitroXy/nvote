<h1>Kategorier</h1>

<?php if($show_results_txt) { ?>
<div class='block'>
	<a href="/results.txt">Ladda ner resultatet som en textfil</a>
</div>
<?php } ?>

<div class='big_links'>
<?php foreach ( $category as $cur ){ ?>
	<a href="/<?=$main?>/<?=$cur->id?>"><?=$cur->name?></a>
<?php } ?>
</div>
