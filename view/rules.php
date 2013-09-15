<h1>Regler</h1>

<div class='block'>
<h2> Generella regler</h2>
<?= render_markdown($event->general_rules)?>
</div>

<?php foreach($categories as $c) { ?>
<div class='block'>
	<h2><?=$c->name?></h2>
	<?=render_markdown($c->rules)?>
</div>
<?php  }?>
