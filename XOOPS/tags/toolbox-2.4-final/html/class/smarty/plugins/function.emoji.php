<?php
/**
 *	smarty function : emoji
 *
 *	sample:
 *	<code>
 *	{emoji code="F9A3"}
 *	</code>
 *  
 *  @deprciate
 *	@param	string	Ethna_ActionName
 *	@return	string	HTML
 */
require_once 'emoji/MobileClass.php';
function smarty_function_emoji($params, &$smarty)
{
	static $mc;
	
	extract($params); // ex: get $name = 'namae';
	
	if (!isset($code)) return '' ;
	
	if (!isset($mc)){
		$mc =& new MobileClass();
	}
	
	return $mc->Convert($code);
}
?>
