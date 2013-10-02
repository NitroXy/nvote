<?php /* -*- mode: html; -*- */ ?>
<?php

session_start();
require('../includes.php');
require("$dir/auth.php");
/* ensure all directories works properly */
$dst = "$dir/upload/{$event->short_name}";
if(!file_exists($dst)) {
	if(is_writable("$dir/upload")) mkdir($dst);
	else {
		die("\"$dst\" fattas och $dir/upload är inte skrivbar.");
	}
} else if(!is_writable($dst)) {
	die("\"$dst\" är inte skrivbar.");
}

foreach ( $event->Category as $cur ){
	$tmp = "$dst/{$cur->dirname()}";
	if ( !file_exists($tmp) ){
		mkdir($tmp);
	}
}

$main = (isset($_GET['main']) && strlen($_GET['main']) > 0) ? preg_replace('[^a-b]', '', $_GET['main']) : 'index';
$controller = "../controller/$main.php";
$view = "../view/$main.php";

require "../controller/application.php";

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
				<?php echo implode(array_map(function($x){ return "{$x->category_id}: ". json_encode(render_markdown($x->description)) ; }, $event->Category(array('status:!=' => Category::$HIDDEN))), ', ') ?>
			};
			var upload_max_filesize = <?=return_bytes(ini_get('upload_max_filesize'))?>;
		</script>
	</head>
	<body>
		<?php
		if(Can::administrate()) { ?>
			<div id="admin-box">
				Admin mode: <a href="?admin_mode=<?=(admin_mode()?'off':'on')?>" class='admin-toggle admin-toggle-<?=(admin_mode()?'on':'off')?>'><?=admin_mode()?"ON":"OFF"?></a>
			</div>
		<?php } ?>
			<div id="header">
				<div id="page_title">
					NitroXy
					<span class='detail'>Kreativ</span>
				</div>
				<?php if ( $u ){ ?>
				<p id='subtitle'>Inloggad som <?=$u->username?> | <?=$event->name?></p>
				<?php } ?>
				<div id="nav">
					<ul>
						<li><a href="/">Start</a></li>
						<li><a href="/rules">Regler</a></li>
						<?php if ( admin_mode() ) { ?>
							<li><a href="/entries">Bidrag</a></li>
						<?php } else if( Category::count(array('status' => Category::$RESULTS_PUBLIC)) > 0) { ?>
							<li><a href="/entries">Resultat</a></li>
						<?php } ?>
						<?php if ( Category::count(array('status' => Category::$VOTING_OPEN)) > 0) { ?>
							<li><a href="/vote">Rösta</a></li>
						<?php } ?>
						<?php if ( Can::submit() ){ ?>
							<?php if ( Category::count(array('status' => Category::$ENTRY_OPEN)) > 0 || admin_mode()){ ?>
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
				} else if( file_exists("../errors/404.php")) {
					require("../errors/404.php");
				} else {
					echo '<h1>404: Not found</h1>';
				}
				?>
			</div>
			<div id="footer">
			</div>
	</body>
</html>
