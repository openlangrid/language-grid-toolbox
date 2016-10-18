<?php

require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
/**
 * <#if locale="en">
 * Client class for User Profile on Language Grid Service Manager
 * <#elseif locale="ja">
 * 言語グリッドサービスマネージャユーザプロファイルクライアント
 * </#if>
 */
class UserManagerClient extends ServiceClient {
	public function __construct() {
		parent::__construct('services/UserManagement?wsdl');
	}

	public function loadUserProfile($ownerUserId) {
		$parameters = array('userId' => $ownerUserId);

		$res = parent::call('getUserProfile', $parameters);
		//getUserProfile
		if ($res['status'] != 'OK') {
			return "";
		}else{
			return $res['contents'];
		}
	}

	protected function makeBindingHeader($parameters){
		return '';
	}
}
?>