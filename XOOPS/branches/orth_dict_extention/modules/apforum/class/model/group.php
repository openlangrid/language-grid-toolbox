<?php
/* require_once dirname(__FILE__).'/abstract-model.php';
 require_once dirname(__FILE__).'/../util/user.php';
require_once(XOOPS_TRUST_PATH.'/libs/altsys/class/D3LanguageManager.class.php');
 */
class Group {
	private $id;
	private $name;
	
	public function __construct($params) {

		$this->id   = $params['groupid'];
		$this->name = $params['description'];		
	}
	
	public function getParams() {
		return $this->params;
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
}