<?php

need_admin();

echo MarkdownHelper::parse($_POST['markdown']);
exit;
