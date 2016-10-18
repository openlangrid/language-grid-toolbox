<?php
require_once XOOPS_ROOT_PATH.'/modules/user/subProfile/class/SubProfileManager.class.php';

function smartyModifierGetUserSubProfile($id, $index) {
	$subMan = new SubProfileManager();
	$d = $subMan->getData($id);
	return $d['sub'.$index.'_value'];
}

?>
