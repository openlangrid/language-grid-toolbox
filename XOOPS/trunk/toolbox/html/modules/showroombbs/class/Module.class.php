<?php
if (!defined('XOOPS_ROOT_PATH')) exit();

class ShowroomBBS_Module extends Legacy_ModuleAdapter {
	
	function ShowroomBBS_Module(&$xoopsModule) {
		parent::Legacy_ModuleAdapter($xoopsModule);
	}
	
	function hasAdminIndex() {
		return true;
	}
	
	function getAdminIndex() {
		return XOOPS_MODULE_URL.'/'.$this->mXoopsModule->get('dirname').'/admin/index.php';
	}
}
?>