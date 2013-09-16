<?php

need_admin();

$blockify = isset($_POST['blockify']) ? $_POST['blockify'] : false;

echo render_markdown($_POST['markdown'], $blockify);
exit;
