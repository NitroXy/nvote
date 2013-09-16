<?=render_markdown($event->frontpage_text, true)?>

<h1>Kategorier</h1>
<?php foreach($categories as $c) { ?>
<div class='block'>
	<h2><?=$c->name?></h2>
	<?=render_markdown($c->description)?>
</div>
<?php  }?>
