<?php
class ServiceInfo {
	private $loginUser;
	public $moduleName;
	public $submoduleName;
	public $action;
	
	public function __construct($moduleName, $submoduleName, $action) {
		$this -> loginUser = XCube_Root::getSingleton()->mContext->mXoopsUser;
		$this -> moduleName = $moduleName;
		$this -> submoduleName = $submoduleName;
		$this -> action = $action;
	}
	
	public function getUserId() {
		return $this -> loginUser -> get('uid');
	}
	
	public function getUserName() {
		return $this -> loginUser -> get('uname');
	}
	
	
	public function getModulePath() {
		return XOOPS_URL."/modules/".$this -> moduleName;
	}
}

//$service = new ServiceInfo($xoopsUser);

?>
