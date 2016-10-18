<?php
/**
 *	smarty function:labelvalue()
 *
 *	sample:
 *	<code>
 *	{labelvalue name=$form.var}
 *	</code>
 *	<code>
 *	12345
 *	</code>
 */
function smarty_function_xanhte_labelvalue($params, &$smarty)
{
	extract($params);
	
	$c =& Ethna_Controller::getInstance();	
	$af =& $c->getActionForm();
	$form_value = $af->get($name);
	$def = $af->getDef($name);

	/// value from form_options
	if(isset($def['form_options'])){
		if (isset($key_value)){	 /// select ?
			if (isset($form_value[$key_value])){
				$form_value = $form_value[$key_value];
			}
		}
		
		if (is_string($form_value) && isset($def['form_options'][$form_value])){
			return $def['form_options'][$form_value] ;
		}
	}
	
	
	if (is_array($def['form_type'])){
		if (is_array($form_value)){
			$ret = '' ;
			$sep = isset($sep) ? $sep : '<br>';
			$pre = isset($pre) ? $pre : '';
			$post = isset($post) ? $post : '';
			if (!is_array($def['form_options'])) return '' ;
			foreach ($form_value as $k=>$v){
				$ret .= sprintf('%s%s%s%s', $pre, $def['form_options'][$k], $post , $sep) ;
			}
			return $ret ;
		}
	}
	
	
	return '' ;
}

?>