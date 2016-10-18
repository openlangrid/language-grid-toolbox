<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
/**
 * @author kitajima
 */
require_once dirname(__FILE__).'/../class/factory/client-factory.php';
require_once dirname(__FILE__).'/../class/manager/qa-permission-manager.php';
require_once dirname(__FILE__).'/../class/util/qa-record-util.php';

try {
	$newFlag = $_POST['newFlag'];
	$name = $_POST['name'];
	$recordId = $_POST['recordId'];
	$question = $_POST['question'];
	$categoryIds = (isset($_POST['categoryIds'])) ? $_POST['categoryIds'] : null;
	$parameterIds = (isset($_POST['parameterIds'])) ? $_POST['parameterIds'] : array();
	
	$permissionManager = new QaPermissionManager();
	$permission = $permissionManager->getMyPermission($name);
	
	if ($permission < QaEnumPermission::EDIT) {
		throw new Exception('Permission denied.');
	}
	
	$questionVos = QaRecordUtil::expressions2toolboxVos($question);
	
	$factory = ClientFactory::getFactory(__TOOLBOX_MODULE_NAME__);
	$moduleClient = $factory->createModuleClient();
	
	if ($newFlag) {
		$result = $moduleClient->addRecord($name, $questionVos, $parameterIds, $categoryIds);
	} else {
		$result = $moduleClient->updateRecord($name, $recordId, $questionVos, $parameterIds, $categoryIds);
	}
	
	$result['contents'] = QaRecordUtil::buildRecord($result['contents']);

	// EBMT学習用
	require_once(XOOPS_ROOT_PATH.'/service_grid/manager/EBMTLearningManager.class.php');
	$EBMTLearningManager = new EBMTLearningManager();
	$EBMTLearningManager->reservationLearning($name);

	// 学習を非同期で実施する為のHTTPリクエストを発信
	$url = XOOPS_URL . '/modules/langrid_config/ebmt-learning.php';
	if (no_sync_access($url)) {
		;
	}
} catch (Exception $e) {
	$result = array(
		'status' => 'ERROR',
		'message' => $e->getMessage(),
		'contents' => null
	);
}

echo json_encode($result);

//非同期でURLにアクセスする関数
function no_sync_access($url){
	if(preg_match('/^(.+?):\/\/(\d+\.\d+\.\d+\.\d+|.+?):?(\d+)?(\/.*)?$/', $url, $matches)){

		$protocol = $matches[1];
		$host = $matches[2];
		$port = $matches[3];
		$path = $matches[4];

		if($port == ''){
			$port = '80';
		}

		if($path == ''){
			$path = '/';
		}

		//接続
		$fp = fsockopen($host, $port, $errno, $errstr, 5);
		if (!$fp) {
			return false;
		} else {
			//リクエストを送信
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Connection: Close\r\n\r\n";
			fwrite($fp, $out);
			//すぐに閉じる
			fclose($fp);
		}
		return true;
	}else{
		return false;
	}
}
?>