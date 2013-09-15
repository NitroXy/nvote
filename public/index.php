<?php /* -*- mode: html; -*- */ ?>
<?php

session_start();
require('../includes.php');
require("$dir/auth.php");
/* ensure all directories works properly */
$dst = "$dir/upload/$event";
if(!file_exists($dst)) {
	if(is_writable("$dir/upload")) mkdir($dst);
	else {
		die("\"$dst\" fattas och $dir/upload är inte skrivbar.");
	}
} else if(!is_writable($dst)) {
	die("\"$dst\" är inte skrivbar.");
}

foreach ( Category::selection(array('event' => $event)) as $cur ){
	$tmp = "$dst/{$cur->dirname()}";
	if ( !file_exists($tmp) ){
		mkdir($tmp);
	}
}

$main = (isset($_GET['main']) && strlen($_GET['main']) > 0) ? preg_replace('[^a-b]', '', $_GET['main']) : 'index';
$controller = "../controller/$main.php";
$view = "../view/$main.php";

$flash = array();
if ( isset($_SESSION['flash']) ){
	$flash = $_SESSION['flash'];
	unset($_SESSION['flash']);
}

$open_cat = Category::selection(array('event' => $event, 'entry_open' => true));

/* execute controller */
if ( file_exists($controller) ){
	require($controller);
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>NitroXy Kreativ</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="/style.css" />
		<script type="application/javascript" src="/js/jquery-1.8.2.min.js"></script>
		<script type="application/javascript" src="/js/nvote.js"></script>
		<script type="application/javascript">
			var category_desc = {
				<?php echo implode(array_map(function($x){ return "{$x->category_id}: '{$x->description}'"; }, $open_cat), ', ') ?>
			};
			var upload_max_filesize = <?=return_bytes(ini_get('upload_max_filesize'))?>;
		</script>
	</head>
	<body>
			<div id="header">
				<div id="page_title">
					NITROXY
					<span id='kreativ'>KREATIV</span>
				</div>
				<?php if ( $u ){ ?>
				<p id='login_info'>Inloggad som <?=$u->username?>.</p>
				<?php } ?>
				<div id="nav">
					<ul>
						<li><a href="/">Start</a></li>
						<li><a href="/rules">Regler</a></li>
						<?php if ( Category::count(array('vote_open' => 1)) > 0 || (Can::administrate())) { ?>
							<li><a href="/vote">Rösta</a></li>
						<?php } ?>
						<?php if ( Can::submit() ){ ?>
							<?php if ( count($open_cat) > 0 ){ ?>
								<li><a href="/upload">Inlämning</a></li>
							<?php } ?>
							<li><a href="/my">Mina bidrag</a></li>
						<?php } ?>


						<?php if ( Can::administrate() ){ ?>
							<li><a href="/admin">Admin</a></li>
						<?php } ?>

						<?php if( $u ) { ?>
							<li><a href="/logout">Logga ut</a></li>
						<?php } else { ?>
							<li><a href="/login">Logga in</a></li>
						<?php } ?>
					</ul>
				</div>
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
	</body>
</html>
