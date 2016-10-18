<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
require_once XOOPS_ROOT_PATH.'/api/class/client/ProfileClient.class.php';
require_once dirname(__FILE__).'/config.php';
require_once dirname(__FILE__).'/class/permission/permission.php';
function getLoginUserUid() {
	return XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid');
}

function getCurrentUserLoginId() {
	$pc = new ProfileClient();
	$response = $pc -> getCurrentUserID();
	return $response['contents'];
}

function require_all_classes($module) {
	$target_dir = opendir( XOOPS_TRUST_PATH.'/modules/'.$module.'/class' );
	while( $file_name = readdir( $target_dir ) ){
		if(ereg("\.php$", $file_name)) {
			require_once XOOPS_TRUST_PATH."/modules/{$module}/class/{$file_name}";
		}
	}
	closedir( $target_dir );
}
	
function unescape_magic_quote($string) {
		return get_magic_quotes_gpc() ? stripslashes($string) : $string;
}

function get_delegator($allow_actions, $default = "index") {
	$module_name = basename(dirname( __FILE__ ));
	
	$script_name = dirname($_SERVER['SCRIPT_NAME']);
	$regex = ".+\/{$module_name}(?![a-z])\/?";
	$submodule_name = preg_replace('/'.$regex.'/','', $script_name);
	
	$action = null;	
	if (@$_GET['action']) {
		$action = @$_GET['action'];
	} else if (@$_POST['action']) {
		$action = @$_POST['action'];
	}
	
	if(!$action) {
		$action = $default;		
	}
	
	$action = ereg_replace('/[.][.]\//', '', $action);
	
	$serviceInfo = new ServiceInfo($module_name, $submodule_name, $action);
	
	return new Delegator($serviceInfo);
}

class Delegator {
	
	var $serviceInfo;
	
	function Delegator($serviceInfo) {
		$this -> serviceInfo = $serviceInfo;
	}
	
	private function _targetFile() {
		$module = $this -> serviceInfo -> moduleName;
		$submodule =  $this -> serviceInfo -> submoduleName;
		if($submodule) $submodule .= '/';
		$action = $this -> serviceInfo -> action;
		return XOOPS_TRUST_PATH."/modules/{$module}/actions/{$submodule}${action}.php";
	}
	
	private function _defaultTmpl() {
		$module = $this -> serviceInfo -> moduleName;
		$submodule =  $this->serviceInfo->submoduleName;
		if($submodule) $submodule .= '_';
		$action = $this -> serviceInfo -> action;
		return "{$module}_{$submodule}{$action}.html";
	}
	
	private function isPartial() {
		return ereg("^_", $this -> serviceInfo -> action);
	}
	
	function execute() {
		$mytrustdirname = basename(dirname(__FILE__));
		$mydirname = basename(dirname(__FILE__));
		
		include(XOOPS_ROOT_PATH . '/header.php');
		
		$xoopsTpl = $GLOBALS['xoopsTpl'];
		$xoopsTpl -> assign('mod_url', $this -> serviceInfo -> getModulePath());
		$xoopsTpl -> assign('mod_name', $this -> serviceInfo -> moduleName);	
		
		if($this -> isPartial()) {
			$controller = XCube_Root::getSingleton()-> mController;
			$controller -> setDialogMode(true);
			$renderOption['type'] = 'noheader';	
		}
		
		$service = $this -> serviceInfo;
		include $this -> _targetFile();			

		
		if(!@$renderOption['template']){
			$renderOption['template'] = $this -> _defaultTmpl();
		}
		$GLOBALS['xoopsOption']['template_main']  = $renderOption['template'];
		if(@$renderOption['type'] == 'stream') {
			$this -> executeStreamRespone();
		} else if(@$renderOption['type'] == 'json') {
			
		} else if(@$renderOption['type'] == 'noheader') {
			print $xoopsTpl -> fetch( 'db:'.$renderOption['template'] );	 
		} else {
			include(XOOPS_ROOT_PATH . '/footer.php');			
		}
	}
	
	function executeStreamRespone() {
		
	}
}

function mb_truncate($string, $length = 80, $etc = '...') {
    if ($length == 0) {return '';}
    if (mb_strlen($string) > $length) {
        return mb_substr($string, 0, $length).$etc;
    } else {
        return $string;
    }
}
?>