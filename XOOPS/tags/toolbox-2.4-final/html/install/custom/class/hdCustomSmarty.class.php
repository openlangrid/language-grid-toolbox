<?php
/// only to use in custom installer
class HdCustomSmarty extends Smarty
{
	function HdCustomSmarty()
	{
		parent::Smarty();
		$this->compile_id = null;
		$this->force_compile    = true;
		$this->compile_check = false;
		$this->left_delimiter =  '<{';
		$this->right_delimiter =  '}>';
		$this->cache_dir = XOOPS_TRUST_PATH.'/cache';
		$this->compile_dir = XOOPS_TRUST_PATH.'/templates_c';
		$this->template_dir = dirname(dirname(__FILE__)).'/templates';
		$this->plugins_dir = array(XOOPS_ROOT_PATH.'/class/smarty/plugins');
		$this->use_sub_dirs = false;
	}
}
