<?php /* -*- mode: html; -*- */ ?>
<?php

session_start();
require('../config.php');
require('../model/user.php');
require('../auth.php');
require('../functions.php');
require('../model/category.php');
require('../model/entry.php');

$category = Category::selection();

/* ensure all directories works properly */
if ( !(file_exists($dir) && is_writable($dir)) ){
		die("$dir fattas eller är inte skrivbar");
}
foreach ( $category as $cur ){
	$tmp = "$dir/{$cur->name}";
	if ( !file_exists($tmp) ){
		mkdir($tmp);
	}
}
$main = isset($_GET['main']) ? preg_replace('[^a-b]', '', $_GET['main']) : 'index';
$controller = "../controller/$main.php";
$view = "../view/$main.php";

$flash = array();
if ( isset($_SESSION['flash']) ){
	$flash = $_SESSION['flash'];
	unset($_SESSION['flash']);
}

/* execute controller */
if ( file_exists($controller) ){
	require($controller);
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>NVote</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="/style.css" />
		<script type="application/javascript" src="/jquery-1.8.2.min.js"></script>
		<script type="application/javascript" src="/nvote.js"></script>
		<script type="application/javascript">
			var category_desc = {
				<?php echo implode(array_map(function($x){ return "{$x->category_id}: '{$x->description}'"; }, $category), ', ') ?>
			};
			var upload_max_filesize = <?=return_bytes(ini_get('upload_max_filesize'))?>;
		</script>
	</head>
	<body>
		<div id="wrapper">
			<div id="header">
				<h1>NVote</h1>
				<?php if ( $u ){ ?>
				<p>Inloggad som <?=$u->name?>.</p>
				<?php } ?>
			</div>
			<div id="nav">
				<ul>
					<li><a href="/">Start</a></li>
					<li><a href="/rules">Regler</a></li>
					<li><a href="/upload">Inlämning</a></li>
					<?php if ( $u ){ ?>
					<li><a href="/entry">Mina bidrag</a></li>
					<li><a href="/logout">Logga ut</a></li>
					<?php } else { ?>
					<li><a href="/login">Logga in</a></li>
					<?php } ?>
				</ul>
			</div>
			<div id="content">
				<?php foreach ( $flash as $class => $message ){ ?>
				<p id="message" class="<?=$class?>"><?=$message?></p>
				<?php } ?>
				<?php
				/* render view */
				if ( file_exists($view) ){
					require($view);
				} else {
					echo '<h1>404: Not found</h1>';
				}
				?>
			</div>
			<div id="footer">
			</div>
		</div>
	</body>
</html>
