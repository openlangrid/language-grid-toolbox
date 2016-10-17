<?php

function smartyModifierAutoLink($string)
{
	return ereg_replace( '(https|http)://[a-zA-Z0-9.-]{2,}(:[0-9]+)?(/[_.!~*a-zA-Z0-9;/?:@&=+$,%#-]+)?/?', '<a href="\0" target="_blank" title="\0">\0</a>', $string);
}

?>