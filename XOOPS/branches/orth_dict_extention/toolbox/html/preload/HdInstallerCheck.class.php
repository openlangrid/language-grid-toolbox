<?php
/**
 * @brief Assign Extra Admin menu for User module
 */
class HdInstallerCheck extends XCube_ActionFilter
{
	var $record_file = '/cache/hd_just_after_install.check';
	
	/// add delegate
	function preBlockFilter()	
	{
		// module header
		if (!file_exists(XOOPS_TRUST_PATH . $this->record_file)){
			$this->mRoot->mDelegateManager->add('Legacypage.Admin.SystemCheck',
												array($this, 'appendExtraCheck'), XCUBE_DELEGATE_PRIORITY_FINAL);
		}
	}
	
	
	/// append extra admin menu
	function appendExtraCheck(&$menu)
	{
		require_once sprintf('%s/modules/hdLegacy/language/%s/admin.php', 
							 XOOPS_ROOT_PATH, $GLOBALS['xoopsConfig']['language']);
		
		$protector_advisory_url = sprintf('%s/modules/protector/admin/index.php?page=advisory', XOOPS_URL);
		
		if ($this->_checkTrustPathIsUnderDocumentRoot() === false){
			printf('<div class="error">'._AD_HDLEGACY_INSTALLER_CHECK_ERROR_TRUST_PATH_IS_REACHABLE.'</div>',
				   $protector_advisory_url);
		}
		
		printf('<div class="tips">'._AD_HDLEGACY_INSTALLER_CHECK_READ_PROTECTOR_ADVISORY.'</div>', 
			   $protector_advisory_url);
		
		$this->_touchInstallerCheck();
	}
	
	
	
	function _touchInstallerCheck()
	{
		$now  = date('Y-m-d H:i:s');
		$file = XOOPS_TRUST_PATH . $this->record_file;
		
		if ($fp = fopen($file, 'w+')){
			fputs($fp, $now."\n");
			chmod($file, 0666);
			fclose($fp);
			return true;
		}
		
		return false;
	}
	
	function _checkTrustPathIsUnderDocumentRoot()
	{
		require_once sprintf('%s/class/snoopy.php', XOOPS_ROOT_PATH);
		$snoopy = new Snoopy();
		$snoopy->_httpmethod = "HEAD";
		
		// calculate the relative path between XOOPS_ROOT_PATH and XOOPS_TRUST_PATH
		$root_paths = explode( '/' , XOOPS_ROOT_PATH ) ;
		$trust_paths = explode( '/' , XOOPS_TRUST_PATH ) ;
		foreach( $root_paths as $i => $rpath ) {
			if( $rpath != $trust_paths[ $i ] ) break ;
		}
		$relative_path = str_repeat( '../' , count( $root_paths ) - $i ) . implode( '/' , array_slice( $trust_paths , $i ) ) ;

		// the path of XOOPS_TRUST_PATH accessible check
		$pubcheck_url = XOOPS_URL.'/'.htmlspecialchars($relative_path).'/modules/protector/public_check.php';
		$snoopy->fetch($pubcheck_url);
		
		// check
		if (preg_match('@^.+?\s(404|403|400|500)\s.*@', $snoopy->response_code, $m)){
			return true;
		}
		
		return false;
	}
}
