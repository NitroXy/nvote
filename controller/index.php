<?php


$selection = array();
if( !admin_mode() ) $selection['status:!='] = Category::$HIDDEN;

$categories = $event->Category($selection);
