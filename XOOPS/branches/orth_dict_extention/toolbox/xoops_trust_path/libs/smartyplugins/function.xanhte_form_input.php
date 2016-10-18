<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {counter} function plugin
 * when installing or updating modules, this function is required.
 */
// {{{ smarty_function_form_input
function smarty_function_xanhte_form_input($params, &$smarty)
{

	static $key_value_key ;
	
	$c =& Ethna_Controller::getInstance();
	
	extract($params);	// $name = input_name , $attr = input_attributes
	
	
	$app = $smarty->get_template_vars('app');
	if (isset($app['__def__']) && $app['__def__'] != null) {
		if (isset($app['__def__'][$name])) {
			$def = $app['__def__'][$name];
		}
		$af =& $c->getActionForm();
	} else {
		$af =& $c->getActionForm();
		$def = $af->getDef($name);
	}
	
	if (isset($def['form_type']) == false) {
		$def['form_type'] = FORM_TYPE_TEXT;
	}
	
	!isset($attr) and $attr = '';
	!isset($key_value) and $key_value = '';
	!isset($id) and $id = '';
	!isset($value) and $value = '';
	!isset($delimiter) and $delimiter = '&nbsp;';
	!isset($postfix) and $postfix = '&nbsp;';
	
	$form_value = $af->get($name);	
	if (is_array($def['type']) && is_null($form_value)){
		$form_value = array();
	}
	
	$form_type = to_array($def['form_type']);
	$form_type = current($form_type);
	
	switch($form_type){
	  case FORM_TYPE_RADIO :
		if ($key_value){
			!$id and $id = $name."_".$key_value;
			$attr .= sprintf(' id="%s"', $id);
			$label = $def['form_options'] && isset($def['form_options'][$key_value]) ? $def['form_options'][$key_value] : '' ;
			if ($label){
				$label = sprintf('<label for="%s">%s</label>', $id, $label);
			}
			$checked = $form_value==$key_value ? "checked" : "";
			$input   = sprintf('<input type="radio" name="%s" value="%s" %s %s />%s%s%s',
							   $name, $key_value, $attr, $checked, $prefix, $label, $postfix) ;
		} else {
			$input = "";
			if(isset($def['form_options']) && is_array($def['form_options'])){
				foreach($def['form_options'] as $key_value=>$label){
					$id = $name."_".$key_value;
					$checked = $form_value==$key_value ? "checked" : "";
					$input .= sprintf('<input type="radio" name="%s" id="%s" value="%s" %s %s />',
									  $name, $id, $key_value, $attr, $checked);
					$input .= sprintf('%s<label for="%s">%s</label>%s',
									  $prefix, $id, $label, $postfix);
				}
			}
		}
		break;
		
		
	  case FORM_TYPE_CHECKBOX :
		if (!is_array($def['type']) || $key_value){
			if ($key_value){
				$name = $name."[".$key_value."]";
				!$id and $id = $name."_".$key_value ;
			} else {
				!$id and $id = substr(md5(mt_rand()), 2,10) ;
			}
			$attr .= sprintf(' id="%s"', $id);
			$checked = $form_value ? 'checked' : "";
			$input = sprintf('<input type="checkbox" name="%s" value="1" %s %s />%s<label for="%s">%s</label>%s',
							 $name, $attr, $checked, $prefix, $id, $def['name'], $postfix);
		} else {
			$input = "";
			if(isset($def['form_options']) && is_array($def['form_options'])){
				foreach ($def['form_options'] as $key_value => $label){
					$id = substr(md5(mt_rand()), 2,10) ;
					$checked = in_array($key_value, array_keys($form_value)) ? 'checked' : '';
					$input .= sprintf('<input type="checkbox" name="%s[%s]" value="1" id="%s" %s %s />%s<label for="%s">%s</label>%s',
									  $name, $key_value, $id, $attr, $checked, $prefix, $id, $label, $postfix);
				}
			}
		}
		break;
		
		
	  case FORM_TYPE_SELECT :
		 
		// multiple
		if (is_array($def['form_type'])){
			$multiple = 'multiple="multiple"';
			!isset($size) and $size = 3;
			if (!is_array($def['type'])){
				return sprintf("[ERROR] You must set ActionForm type to array() when you want to use multiple select. %s. %s:%d ", $def['name'], __FILE__, __LINE__);
			}
			$key_value = false;
		} else {
			$multiple = "";
			$size = 1;
		}
		
		// value type
		if (is_array($def['type'])){
			if ($key_value || $key_value!==""){
				$name .= '['.$key_value.']';
			} else {
				// only for multiple
				if (is_array($def['form_type'])){
					$name .= '[]';
				} else {
					// need key_value
					return sprintf("[ERROR] no key_value set at form %s. %s:%d ", $def['name'], __FILE__, __LINE__);
				}
				
			}
		}
		 
		// value
		$form_value = to_array($form_value);
		if (is_array($def['form_type'])){
			// multiple select...pass thru
		} else {
			if (is_array($def['type'])){
				// single & form_name[key]
				$form_value = $form_value[$key_value];
			} else {
				$form_value = current($form_value);
			}
		}

		$input = sprintf('<select name="%s" size="%d" %s %s>', $name, $size, $attr, $multiple)."\n" ;
		if(isset($def['form_options']) && is_array($def['form_options'])){
			foreach($def['form_options'] as $option_value=>$option_label){
				if (is_array($form_value)){
					$selected = in_array($option_value, $form_value) ? ' selected' : '';
				} else {
					$selected = $option_value==$form_value ? ' selected' : '';
				}
				$input .= sprintf('<option value="%s" %s>%s</option>'."\n",
								  $option_value, $selected ,$option_label) ;
			}
		}
		$input .= "</select>\n";
		break;
		
		
	  case FORM_TYPE_FILE:
		is_array($def['type']) and 	$name = $name."[".$key_value."]";
		$input = sprintf('<input type="file" name="%s"', $name);
		if ($attr) {
			$input .= " $attr";
		}
		$input .= " />";
		break;
		
		
	  case FORM_TYPE_TEXTAREA:
		is_array($def['type']) and 	$name = $name."[".$key_value."]";
		$input = sprintf('<textarea name="%s"', $name);
		if ($attr) {
			$input .= " $attr";
		}
		$input .= sprintf('>%s</textarea>', htmlspecialchars($af->get($name), ENT_QUOTES));
		break;
		
		
	  case FORM_TYPE_TEXT:
	  case FORM_TYPE_PASSWORD:
	  default:
		if ($key_value){
			$form_value = $form_value[$key_value];
		}
		is_array($def['type']) and 	$name = $name."[".$key_value."]";
		$input = sprintf('<input type="%s" name="%s" value="%s"',
						 $form_type==FORM_TYPE_PASSWORD ? 'password' : 'text',
						 $name, htmlspecialchars($form_value));
		if ($attr) {
			$input .= " $attr";
		}
		if (isset($def['max']) && $def['max']) {
			$input .= sprintf(' maxlength="%d"', $def['max']);
		}
		$input .= " />";
		
		break;
		
	}
	return $input ;
}
/* vim: set expandtab: */

?>
