<?php
function render_markdown($md, $blockify=false) {
	$content = MarkdownHelper::parse($md);
	$n = 0;
	$content = preg_replace_callback('#<h1>(.*)</h1>#U', function($match) use (&$n){
		$str = '';
		if ( $n++ > 0 ){
			$str = '</div>';
		}
		return $str . "<h1>{$match[1]}</h1><div class=\"block\">";
	}, $content);
	if ( $n > 0 ){
		$content .= '</div>';
	}
	return $content;
}
