<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009-2013  Department of Social Informatics, Kyoto University
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

error_reporting(0);
setlocale(LC_ALL,'en_US.UTF-8');
//error_reporting(E_ALL);

require_once XOOPS_ROOT_PATH.'/modules/translation_setting/php/class/TranslationServiceSetting.class.php';
//define('DICTIONARY_ENTPOINT_URL_BASE', XOOPS_URL.'/modules/dictionary/services/invoker/billingualdictionary.php?serviceId=');
require_once(XOOPS_ROOT_PATH.'/modules/dictionary/services/defines.php');

require_once(dirname( __FILE__ ).'/util/db_util.php');
require_once(dirname( __FILE__ ).'/util/sql.php');
require_once(dirname( __FILE__ ).'/validator.php');

class DictionaryType {
	const DICTIONARY = 0;
	const PARALLEL_TEXT = 1;
	const PARAPHRASE = 5;
	const NORMALIZE = 6;
}


class UserDictionaryController {
	private $db;
	private $languageManager;
	private $root;

	const NORMALIZED_LANG_SUFFIX = "-normalized";

	public function __construct() {
		$this->db = Database::getInstance();
		$this->root = XCube_Root::getSingleton();
	}

	public function create($params) {
		$response = array('status'=>'OK', 'message' => 'Successful Dictionary Create', 'contents' => array());
		$response['contents'] = $this->doCreate($params);
		if (!isset($response['contents']['dictionaryId'])) {
			$response['status'] = 'Error';
			$response['message'] = $response['contents']['message'];
		} else {
			$dict = $this->getDictionary($response['contents']['dictionaryId']);
			$response['contents']['updateDate'] = $dict['update_date'];
		}

		return $response;
	}

	public function load() {
		$return = array('status' => 'OK', 'contents' => array(), 'message' => 'Successful Dictionary Load');
		$return['contents'] = $this->getAllUserDictionaries();
		return $return;
	}

	public function read($params) {
		$response = array('status'=>'OK', 'message' => 'Successful Dictionary Load', 'contents' => array());
		if (!$this->canLoad($params['id'])) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD;
			return $response;
		}
		
		$response['contents']['dictionary'] = $this->getAllDictionaryContentsWithoutRowByDictionaryId($params['id']);
		$response['contents']['permission'] = $this->getPermission($params['id']);
		$response['contents']['permission']['user']['admin'] = $this->canChangePermission($params['id']);
		$response['contents']['permission']['user']['view'] = true;
		$response['contents']['permission']['user']['edit'] = $this->canEdit($params['id']);

		$dict = $this->getDictionary($params['id']);

		$response['contents']['updateDate'] = $dict['update_date'];
		$response['contents']['dictionaryName'] = $dict['dictionary_name']; 
		return $response;
	}

	public function loadDictionaries($params) {
		if(!@$params['perPage']) $params['perPage'] = 10; 
		if(!@$params['pageNo']) $params['pageNo'] = 1;
		$offset = (intval(@$params['pageNo']) - 1) * intval($params['perPage']);
		$response = array('status' => 'OK', 'contents' => array(), 'message' => 'Successful Dictionary Load');
		$response['contents'] = array(
			'dictionaries' => $this->getAllDictionariesByTypeId(intval($params['typeId']), intval($params['perPage']), $offset),
			'paginateInfo' => array(
				'totalNum' => $this->countAllDictionariesByTypeId(intval($params['typeId'])),
				'perPage' => $params['perPage'],
				'pageNo' => $params['pageNo']
			)
		);
		
		return $response;
	}

	public function readDictionary($params) {
		$response = array('status'=>'OK', 'message' => 'Successful Dictionary Load', 'contents' => array());
		if (!$this->canLoad($params['id'])) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD;
			return $response;
		}
		if(!@$params['perPage']) $params['perPage'] = 10; 
		if(!@$params['pageNo']) $params['pageNo'] = 1;
		$offset = (intval(@$params['pageNo']) - 1) * intval($params['perPage']);
		$response['contents']['dictionary'] = $this->getAllDictionaryContentsByDictionaryId($params['id'], intval($params['perPage']), $offset); 
		$response['contents']['permission'] = $this->getPermission($params['id']);
		$response['contents']['permission']['user']['admin'] = $this->canChangePermission($params['id']);
		$response['contents']['permission']['user']['view'] = true;
		$response['contents']['permission']['user']['edit'] = $this->canEdit($params['id']);

		$dict = $this->getDictionary($params['id']);

		$response['contents']['updateDate'] = $dict['update_date'];
		$response['contents']['dictionaryName'] = $dict['dictionary_name'];
		$response['contents']['paginateInfo'] = array(
			'totalNum' => $this->countAllDictionaryContentsByDictionaryId($params['id']),
			'perPage' => $params['perPage'],
			'pageNo' => $params['pageNo']
		);
		return $response;
	}

	public function update($params) {
		$response = array('status'=>'OK', 'message' => 'Successful Dictionary Save', 'contents' => array());

		if ($params['overwrite'] == 'false') {
			$dict = $this->getDictionary($params['dictionaryId']);
			if ($dict['update_date'] > $params['updateDate']) {
				$response = array('status'=>'WARNING', 'message' => _MI_DICTIONARY_ERROR_DICTIONARY_CONFLICT, 'contents' => array());
				return $response;
			}
		}

		if(!$this->doUpdate($params)) {
			$response['message'] = "Error";
			$response['status'] = 'ERROR';
		}

		if (@$params['viewPermission'] && @$params['editPermission']
            && $this->canChangePermission($params['dictionaryId'])) {
			$this->setPermission($params);
		}

		$dict = $this->getDictionary($params['dictionaryId']);
		$response['contents']['updateDate'] = $dict['update_date'];

		return $response;
	}

	public function download($id) {
		$return = array('status' => 'OK', 'contents' => array(), 'message' => 'Successful dictionary download');
		$dictionaryId = intval($id);
		if (!$this->canLoad($id)) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_LOAD;
			return $response;
		}
		$return['contents'] = $this->doDownload($dictionaryId);
		return $return;
	}

	public function upload($params, $files) {
		$return = array('status' => 'OK', 'contents' => array(), 'message' => 'Successful dictionary upload');

		$tmpFilePath = $files['dictfile']['tmp_name'];
		$typeId = intval($params['dictionary_type']);
		$name = $params['dictionary_name'];
		$editPermission = $params['edit_permission'];
		$readPermission = $params['view_permission'];
		$mimeType = $files['dictfile']['type'];
		$return['contents'] = $this->doUpload($tmpFilePath, $typeId,
		$name, $editPermission, $readPermission, $mimeType);
		return $return;
	}

	public function deploy($id) {
		$return = array('status' => 'OK', 'contents' => array(), 'message' => 'Successful dictionary deploy');
		$dictionaryId = intval($id);
		if (!$this->canEdit($id)) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_DEPLOY;
			return $response;
		}
		$return['contents'] = $this->doDeploy($dictionaryId);
		return $return;
	}

	public function undeploy($id) {
		$return = array('status' => 'OK', 'contents' => array(), 'message' => 'Successful dictionary undeploy');
		$dictionaryId = intval($id);
		if (!$this->canLoad($id)) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_UNDEPLOY;
			return $response;
		}
		$return['contents'] = $this->doUndeploy($dictionaryId);
		return $return;
	}

	private function doDeploy($dictionaryId) {
		$tableName = $this->db->prefix('user_dictionary');
		$sql  = ' UPDATE  '.$tableName.' ';
		$sql .= ' SET     `deploy_flag` = \'1\' ';
		$sql .= ' WHERE   `user_dictionary_id` = '.intval($dictionaryId).' ';

		return $this->db->query($sql);
	}
	private function doUndeploy($dictionaryId) {
		$tableName = $this->db->prefix('user_dictionary');
		$sql  = ' UPDATE  '.$tableName.' ';
		$sql .= ' SET     `deploy_flag` = \'0\' ';
		$sql .= ' WHERE   `user_dictionary_id` = '.intval($dictionaryId).' ';

		$this->removeLocalDictionary($dictionaryId);

		return $this->db->query($sql);
	}

	private function removeLocalDictionary($dictionaryId) {
		$dictionary = $this->getDictionary($dictionaryId);
		$dictionaryName = $dictionary['dictionary_name'];
		$endpointUrl = DICTIONARY_ENTPOINT_URL_BASE.str_replace(' ', '_', $dictionaryName);

		$tss = new TranslationServiceSetting();
		$tss->removeLocalDictionary($endpointUrl);

	}

	private function removeTemporalDictionary($dictionaryName) {
		$tss = new TranslationServiceSetting();
		$tss->removeTemporalDictionary($dictionaryName);
	}

	private function doDownload($dictionaryId) {
		$contents = $this->getAllDictionaryContentsByDictionaryId($dictionaryId, -1, 0);
		$dictionary = $this->getDictionary($dictionaryId);

		$lines = array();
		foreach($contents as $row) {
			$lines[] = $this->joinLineForCSV($row);
		}
		$output = implode(PHP_EOL, $lines);
		// 最終行は改行で終わる 
		$output .= PHP_EOL;
		
		$output = chr(255).chr(254).mb_convert_encoding($output, "UTF-16LE", "UTF-8");
		return array(
			"output" => $output,
			"name" => $this->getCleanFileName($dictionary['dictionary_name'])
		);
	}
	
	protected function joinLineForCSV($recordArray, $delimiter = "\t") {
		$results = array();
		foreach($recordArray as $key => $value) {
			if($key != "row") $results[] = $value;
		}
		return implode($delimiter, $results);
	}

	private function getCleanFileName($fileName) {
		$fileName = str_replace(array('.', '_', '-', ' '), '', $fileName);
		if (!$fileName) {
			$fileName = 'resource';
		}
		return $fileName.'.txt';
	}

	private function doUpload($tmpFilePath, $typeId, $name,	$editPermission, $readPermission, $mimeType) {

		$error = false;
		$tmpFileLines = file($tmpFilePath);
		$code = mb_detect_encoding($tmpFileLines[0]);

		if (ord($tmpFileLines[0]{0}) == 255 && ord($tmpFileLines[0]{1}) == 254) {
			$code = "UTF-16LE";
		} else if (ord($tmpFileLines[0]{0}) == 254 && ord($tmpFileLines[0]{1}) == 255) {
			$code = "UTF-16BE";
		} else {
			$code = '';
		}
		if ($code == '') {
			$error = _MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID;
			return $this->_doUploadErrorResponse($error);
		}
		$tmpFileContent = '';
		foreach($tmpFileLines as $aline) {
			$tmpFileContent .= $aline;
		}

		$utf8content = mb_convert_encoding($tmpFileContent, 'UTF-8', $code);
		if (ord($utf8content{0}) == 0xef && ord($utf8content{1}) == 0xbb && ord($utf8content{2}) == 0xbf) {
			$utf8content = substr($utf8content, 3);
		}

		$lines = array();
		$temp = fopen('php://memory', 'rw');
		fwrite($temp, $utf8content);
		fseek($temp, 0);
		while (($cells = fgetcsv($temp, 10240, chr(0x09))) !== false) {			// chr(0x09) == \t
			$lines[] = $cells;
		}
		fclose($temp);

		$validColNums = false;
		foreach($lines as $aline){
			if ($aline == null || is_array($aline) === false || count($aline) == 0) {
				continue;
			}
			$rowArray = $aline;

			if(!$validColNums){
				$validColNums = array();
				for($i=0; $i<count($rowArray); $i++){
					if(mb_strlen($rowArray[$i])>0){
						$validColNums[] = $i;
					}
				}
			}

			$tableRow = array();
			foreach($validColNums as $colNum){
				$tableRow[] = $rowArray[$colNum] ? $rowArray[$colNum] : "";
			}

			$dictTable[] = $tableRow;
		}

		$response = $this->doCreate(array(
			'dictionaryName' => $name,
			'viewPermission' => $editPermission,
			'editPermission' => $readPermission,
			'supportedLanguages' => $dictTable[0],
			'dictionaryTypeId' => $typeId
		));

		if (strtoupper($response['status']) == 'ERROR') {
			$error = $response['message'];
			return $this->_doUploadErrorResponse($error);
		}
		
		$dictionaryId = intval(@$response['dictionaryId']);
		$params = array(
			'dictionaryId' => $dictionaryId,
			'valueToSave' => $dictTable,
			'mimeType' => $mimeType,
			'typeId' => $typeId
		);
		
		if($msg = Validator::validateForUpload($params)) {
			return $this->_doUploadErrorResponse($msg);
		}

		$checkResp = $this->dictionaryTableCheck($dictTable, $typeId);
		if ($checkResp['status'] == 'ERROR') {
			return $this->_doUploadErrorResponse($checkResp['message']);
		}
		
		$result = true;
		$result &= $this->updateContentsAll($dictionaryId, $dictTable[0], $dictTable);
		
		return $this->_doUploadSuccessResponse($dictionaryId);
	}

	private function _doUploadErrorResponse($error) {
		$scripts = <<<JS
			alert("{$error}");
			parent.DialogViewController.hideIndicator();
JS;
		return $this->_doUploadResponse($scripts);
	}

	private function _doUploadSuccessResponse($dictionaryId) {
		$scripts = <<<JS
			with(window.parent) {										  
				DialogViewController.ImportDictionary.afterImport({$dictionaryId});
			}
JS;
		return $this->_doUploadResponse($scripts);
	}
	
	private function _doUploadResponse($scripts) {
		return <<<HTML
			<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>Langrid ToolBox</title>
			</head>
			<body>			  
			<script language="JavaScript" type="text/javascript">
			{$scripts}
			</script>
			</body>
			</html>
HTML;
	}

	public function loadTargetLanguages($typeId = null) {
		return array(
			'status'=>'OK', 
			'message' => 'Successful Dictionary Save', 
			'contents' => $this->getLanguagesByTypeId($typeId)
		);
	}

	public function delete($params) {
		$response = array('status'=>'OK', 'message' => 'Successful Dictionary Remove', 'contents' => array());
		if ( ! isset($params['dictionaryId'])) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_DICTIONARY;
			return $response;
		}
		$dictionaryId = $params['dictionaryId'];
		$dict = $this->getDictionary($dictionaryId);
		if (!$this->canDelete($dictionaryId)) {
			$response['status'] = 'ERROR';
			$response['message'] = _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_EDIT;
			return $response;
		}
		if($dict['deploy_flag'] == 1){
			$this->removeLocalDictionary($dictionaryId);
		}
		$this->deleteContents($dictionaryId);
		$this->removeDictionary($dictionaryId);
		$this->removeTemporalDictionary($dict['dictionary_name']);
		return $response;
	}

	public function search($params) {
		if(!@$params['perPage']) $params['perPage'] = 10; 
		if(!@$params['pageNo']) $params['pageNo'] = 1;
		$offset = (intval(@$params['pageNo']) - 1) * intval($params['perPage']);
		$keywords = array(@$params["keyword"]);
		
		$response = array('status'=>'OK', 'message' => 'Successful Dictionary Search', 'contents' => array());
	
		$response['contents'] = array(
			'results' => $this->getContentsBySearchKeyword(
				$params["typeId"], $params["sourceLang"], $keywords, $params["matchingMethod"], 
				$params["targetLang"], intval($params['perPage']), $offset
			),
			'paginateInfo' => array(
				'totalNum' => $this->getCountDictionaryBySearchKeyword(
					$params["typeId"], $params["sourceLang"], $keywords, $params["matchingMethod"]
				),
				'perPage' => $params['perPage'],
				'pageNo' => $params['pageNo']
			)
		);
		return $response;
	}

	public function _preSearch($params) {
		$keywords = array($params["keyword"]);
		return $this->getCountDictionaryBySearchKeyword(
			$params["typeId"], $params["sourceLang"], $keywords, $params["matchingMethod"]
		);
	}


	private function deleteContents($dictionaryId) {
		$userDictionaryContentsTable = $this->db->prefix('user_dictionary_contents');

		$sql  = '';
		$sql .= ' DELETE FROM '.$userDictionaryContentsTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d ';

		$sql = sprintf($sql, intval($dictionaryId));
		$result = $this->db->query($sql);

		return (bool)$result;
	}

	private function removeDictionary($dictionaryId) {
		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');

		$sql  = '';
		$sql .= ' UPDATE '.$userDictionaryTable.' ';
		$sql .= ' SET delete_flag = 1 , update_date = ? ';
		$sql .= ' WHERE user_dictionary_id = ? ';

		$this->db->prepare($sql);
		$this->db->bind_param('ii', time(), intval($dictionaryId));
		$result = $this->db->execute();

		if($result){
			$sql  = ' SELECT dictionary_name ';
			$sql .= ' FROM '.$userDictionaryTable.' ';
			$sql .= ' WHERE user_dictionary_id = '.intval($dictionaryId).' ';
			if ($rs = $this->db->query($sql)) {
				$row = $this->db->fetchArray($rs);
				$DictName = $row['dictionary_name'];
				$this->updateTranslationPath($DictName);
			}
		}

		$sql  = '';
		$sql .= ' UPDATE '.$userDictionaryPermissionTable.' ';
		$sql .= ' SET delete_flag = 1 ';
		$sql .= ' WHERE user_dictionary_id = ? ';

		$this->db->prepare($sql);
		$this->db->bind_param('i', intval($dictionaryId));
		$result = $this->db->execute();

		return (bool)$result;
	}

	private function getUserDictionaries() {

		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryContentsTable = $this->db->prefix('user_dictionary_contents');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$usersTable = $this->db->prefix('users');
		$userId = $this->root->mContext->mXoopsUser->get('uid');
		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

		$userDictionaryIds = $this->getUserDictionaryIds();

		if (!count($userDictionaryIds)) {
			return array();
		}

		$sql  = '';
		$sql .= ' SELECT * FROM (('.$userDictionaryTable;
		$sql .= '   LEFT JOIN ';
		$sql .= '     (SELECT COALESCE(MAX(row),0) AS count,user_dictionary_id, language ';
		$sql .= '        FROM '.$userDictionaryContentsTable;
		$sql .= '        WHERE delete_flag = 0';
		$sql .= '          AND user_dictionary_id IN ('.implode(',', $userDictionaryIds).')';
		$sql .= '        GROUP BY user_dictionary_id, language ';
		$sql .= '      ) AS T1 USING (user_dictionary_id)';
		$sql .= '   ) LEFT JOIN ';
		$sql .= '     (SELECT uid,uname FROM '.$usersTable.') AS T2 ON T2.uid = user_id)';
		$sql .= '   LEFT JOIN ';
		$sql .= '   (SELECT user_dictionary_id,permission_type, view, edit FROM '.$userDictionaryPermissionTable.') ';
		$sql .= '   AS T3 USING(user_dictionary_id) ';
		$sql .= '   WHERE ';
		$sql .= '     user_dictionary_id IN ('.implode(',', $userDictionaryIds).') ';
		$sql .= '     AND delete_flag = 0 ';
		$sql .= '     AND T1.count IS NOT NULL ';
		$sql .= '     AND T1.language is NOT NULL ';
		$sql .= '   ORDER BY `type_id` ASC, `dictionary_name`, `user_dictionary_id`, language  ASC ';

		$result = $this->db->query($sql);
		if (!$result) {
			//			die($sql);
		}
		$userDictionaries = array();
		$previewId = 0;
		$dictionary = array();
		while($row = $this->db->fetchArray($result)) {
			if ($previewId == $row['user_dictionary_id']) {
				$dictionary['supportedLanguages'][] = $row['language'];
			} else {
				if ($previewId) {
					$userDictionaries[] = $dictionary;
					$dictionary['supportedLanguages'] = array();
					$dictionary = array();
				}
				$dictionary['supportedLanguages'][] = $row['language'];
				$dictionary['id'] = $row['user_dictionary_id'];
				$dictionary['name'] = $row['dictionary_name'];
				$dictionary['userId'] = $row['user_id'];
				$dictionary['typeId'] = $row['type_id'];
				$dictionary['createDate'] = $row['create_date'];
				$dictionary['updateDate'] = $row['update_date'];
				$dictionary['createDateFormat'] = formatTimestamp($row['create_date'], 'Y/m/d H:i'); //'m');
				$dictionary['updateDateFormat'] = formatTimestamp($row['update_date'], 'Y/m/d H:i'); //'m');
				$dictionary['userName'] = $row['uname'];

				$dictionary['view'] = true;
				$dictionary['edit'] = false;

				if ($row['permission_type'] == 'all' && $row['edit'] == 1
				|| $row['user_id'] == $this->root->mContext->mXoopsUser->get('uid')
				|| $this->root->mContext->mXoopsUser->isAdmin()) {
					$dictionary['edit'] = true;
				}

				$dictionary['count'] = $row['count'] ? $row['count'] : 0;
				$previewId = $row['user_dictionary_id'];
			}
		}
		if (count($dictionary)) {
			$userDictionaries[] = $dictionary;
		}
		return $userDictionaries;
	}

	private function getUserDictionaryIds() {

		$userDictionaryIds = array();
		$userId = $this->root->mContext->mXoopsUser->get('uid');
		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

		$sql = '';
		$sql .= 'SELECT user_dictionary_id ';
		$sql .= 'FROM '.DBtableName('user_dictionary_permission').' ';
		$sql .= 'WHERE (permission_type = \'all\'';
		$sql .= '    ) ';
		$sql .= '  AND (view = 1 OR edit = 1)';
		$sql .= '  AND delete_flag = 0';
		$sql .= ' GROUP BY user_dictionary_id ';
		$result = $this->db->query($sql);

		while($row = $this->db->fetchArray($result)) {
			$userDictionaryIds[] = $row['user_dictionary_id'];
		}

		$sql  = '';
		$sql .= ' SELECT user_dictionary_id FROM '.DBtableName('user_dictionary');

		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= ' WHERE user_id = '.$userId.' AND delete_flag = 0 ';
		}
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result)) {
			$userDictionaryIds[] = $row['user_dictionary_id'];
		}
		return $userDictionaryIds;
	}
	
	protected function canDeleteDictionaryByUserId($userId) {
		return $this->root->mContext->mXoopsUser->isAdmin()
			|| $this->root->mContext->mXoopsUser->get('uid') == $userId;
	}
	
	protected function hasEditPermission($row) {
		return ($row['permission_type'] == 'all' && $row['edit'] == 1)
			|| $row['userId'] == $this->root->mContext->mXoopsUser->get('uid')
			|| $this->root->mContext->mXoopsUser->isAdmin();
	}
	protected function hasViewPermission($row) {
		return ($row['permission_type'] == 'all' && $row['view'] == 1)
			|| ($row['permission_type'] == 'all' && $row['edit'] == 1)
			|| $row['userId'] == $this->root->mContext->mXoopsUser->get('uid')
			|| $this->root->mContext->mXoopsUser->isAdmin();
	}

	protected function getAllUserDictionaries() {

		$sql = SQL::getSQLForSelectAllUserDictionaries();
		$result = $this->db->query($sql);
		
		$userDictionaries = array();
		$previewId = 0;
		$dictionary = array();
		while($row = $this->db->fetchArray($result)) {
			if ($previewId == $row['user_dictionary_id']) {
				$dictionary['supportedLanguages'][] = $row['language'];
			} else {
				if ($previewId) {
					$userDictionaries[] = $dictionary;
					$dictionary['supportedLanguages'] = array();
					$dictionary = array();
				}
				$dictionary['supportedLanguages'][] = $row['language'];
				$dictionary['id'] = $row['user_dictionary_id'];
				$dictionary['name'] = $row['dictionary_name'];
				$dictionary['userId'] = $row['user_id'];
				$dictionary['typeId'] = $row['type_id'];
				$dictionary['createDate'] = $row['create_date'];
				$dictionary['updateDate'] = $row['update_date'];
				$dictionary['createDateFormat'] = formatTimestamp($row['create_date'], 'Y/m/d H:i'); //'m');
				$dictionary['updateDateFormat'] = formatTimestamp($row['update_date'], 'Y/m/d H:i'); //'m');
				$dictionary['userName'] = $row['uname'];

				$dictionary['view'] = $this->hasViewPermission($row);
				$dictionary['edit'] = $this->hasEditPermission($row);
				$dictionary['deployFlag'] = (bool)$row['deploy_flag'];
				$dictionary['delete'] = $this->canDeleteDictionaryByUserId($dictionary['userId']);
				$dictionary['count'] = $row['count'] ? $row['count'] : 0;
				$previewId = $row['user_dictionary_id'];
			}
		}
		if (count($dictionary)) {
			$userDictionaries[] = $dictionary;
		}
		return $userDictionaries;
	}

	private function getAllUserDictionaryIds() {

		$userDictionaryIds = array();
		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryContentsTable = $this->db->prefix('user_dictionary_contents');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$userId = $this->root->mContext->mXoopsUser->get('uid');
		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

		$sql = '';
		$sql .= 'SELECT user_dictionary_id ';
		$sql .= 'FROM '.$userDictionaryTable.' ';
		$sql .= '  WHERE delete_flag = 0';
		$result = $this->db->query($sql);

		while($row = $this->db->fetchArray($result)) {
			$userDictionaryIds[] = $row['user_dictionary_id'];
		}

		$sql  = '';
		$sql .= ' SELECT user_dictionary_id FROM '.$userDictionaryTable;

		if (!$this->root->mContext->mXoopsUser->isAdmin()) {
			$sql .= ' WHERE user_id = '.$userId.' AND delete_flag = 0 ';
		}
		$result = $this->db->query($sql);
		while($row = $this->db->fetchArray($result)) {
			$userDictionaryIds[] = $row['user_dictionary_id'];
		}
		return $userDictionaryIds;
	}

	/**
	 * @param $params = array(
	 * 		dictionaryName
	 * 		viewPermission
	 *		editPermission
	 *		supportedLanguages
	 *		dictionaryTypeId
	 * );
	 * @return array(
	 *		dictionaryId (insert id)
	 * )
	 */
	private function doCreate($params) {

		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryContentsTable = $this->db->prefix('user_dictionary_contents');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$userId = $this->root->mContext->mXoopsUser->get('uid');
		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

		$params = array_merge(
			array(
				'dictionaryName' => '',
				'viewPermission' => 'user',
				'editPermission' => 'user',
				'supportedLanguages' => array(),
				'dictionaryTypeId' => 0,
				'deployFlag' => false
			), $params
		);
		
		if($msg = Validator::validateForCreate($params)) {
			$response['message'] = $msg;
			$response['status'] = 'Error';
			return $response;
		}

		if (isset($params['valueToSave']))$valueToSave = $params['valueToSave'];
		$dictionaryName = $params['dictionaryName'];

		$sql = '';
		$sql .= 'SELECT COUNT(*) AS CNT, type_id FROM '.$userDictionaryTable.' ';
		$sql .= ' WHERE dictionary_name = \'%s\' AND delete_flag = 0 ';
		$sql = sprintf($sql, $this->escape($dictionaryName));

		$result = $this->db->query($sql);
		$row = $this->db->fetchArray($result);
		if ($row['CNT'] > 0) {
			$response['message'] = _MI_DICTIONARY_ERROR_DICTIONARY_ALREADY_EXISTS;

			if ($params['dictionaryTypeId']) {
				$response['message'] = _MI_DICTIONARY_ERROR_PARALLEL_TEXT_ALREADY_EXISTS;
			}

			$response['status'] = 'Error';
			return $response;
		}

		$sql  = '';
		$sql .= ' INSERT INTO '.$userDictionaryTable.' SET `user_id` = \'%d\' ';
		$sql .= ' , `create_date` = \'%d\', `update_date` = \'%d\' ';
		$sql .= ' , `dictionary_name` = \'%s\', type_id = \'%d\' ';
		$sql .= ' , `deploy_flag` = \'%d\'';

		$time = time();
		$sql = sprintf($sql,  $userId, $time, $time
			, $this->escape($dictionaryName), $params['dictionaryTypeId']
			, $params['deployFlag'] === true ? 1 : 0);
		$result = $this->db->query($sql);
		$userDictionaryId = $this->db->getInsertId();

		$sql  = '';
		$sql .= ' INSERT INTO '.$userDictionaryContentsTable.' SET ';
		$sql .= ' `row` = \'0\', `contents` = \'%s\', `user_dictionary_id` = \'%d\', ';

		$sql .= ' `language` = \'%s\' ';
		foreach ($params['supportedLanguages'] as $language) {
			$this->db->query(sprintf($sql, $language, $userDictionaryId, $language));
		}

		$params['dictionaryId'] = $userDictionaryId;
		$this->setPermission($params);

		$return['dictionaryId'] = $userDictionaryId;
		$return['status'] = 'OK';

		return $return;
	}
	
	
	// @return isValid
	protected function validateForUpdate($params) {
		if (!$this->canEdit($params['dictionaryId'])) {
			return _MI_DICTIONARY_ERROR_NO_PERMISSION_TO_EDIT;
		}

		$languages = $params['valueToSave'][0];
		if(!isAllSupportedLanguage($languages, $params['typeId'])) {
			return _MI_DICTIONARY_ERROR_FAILED_TO_LOAD_SUPPORTED_LANGUAGE;
		}
		
		if (@$params['removeLanguages'] &&
			!isAllSupportedLanguage($params['removeLanguages'] , $params['typeId'])) {
			return _MI_DICTIONARY_ERROR_FAILED_TO_LOAD_SUPPORTED_LANGUAGE;				
		}
		return null;
	}

	/**
	 *
	 * @param $params = array(
	 * 		'dictionaryId' => Dictionary ID to update
	 * 		'valueToSave' => array(
	 * 			languagesArray,
	 * 			contentsArray,
	 * 			contentsArray,
	 * 				.
	 * 				.
	 * 				.
	 * 			contentsArray
	 * )
	 * @return unknown_type
	 */
	private function doUpdate($params) {
		
		if($msg = $this->validateForUpdate($params)) {
			return array('status' => 'ERROR', 'message' => $msg);
		}
		
		$dictionaryId = intval($params['dictionaryId']);
		$result = true;
		
		// 言語削除分
		if(@$params['removeLanguages']) {
			$result &= $this->removeLanguagesAll($dictionaryId, $params['removeLanguages']);
		}
		
		// 更新分
		$result &= $this->updateContentsAll($dictionaryId, $params['valueToSave'][0], $params['valueToSave']);

		// 新規追加分
		if(@$params['newRecord']) {
			$result &= $this->createContentsAll($dictionaryId, $params['valueToSave'][0], $params['newRecord']);
		}
		
		if(@$params['viewPermission'] && @$params['editPermission']) {
			$this->setPermission($params);	
		}

		// 更新日付を最新化（排他制御）
		$result &= $this->updateDictionaryLastUpdateToCurrentDate($dictionaryId);

		$dictionary = $this->getDictionary($dictionaryId);
		// call user hook function
		$pinfo = pathinfo(__FILE__);
		$hookfile = $pinfo['dirname'].'/hooks/'.$pinfo['filename'].'.hook.'.$pinfo['extension'];
		if (file_exists($hookfile)) {
			require_once($hookfile);
			$hookclass = get_class($this).'_Hook';
			if (class_exists($hookclass)) {
				$hook = new $hookclass;
				$hookfunc = 'doUpdateAfter';
				if (method_exists($hook, $hookfunc)) {
					call_user_method($hookfunc, $hook, $dictionary);
				}
			}
		}

		return (boolean)$result;
	}
	
	protected function updateContentsAll($dictionaryId, $supportedLanguages, $records) {
		$result = true;
		$sql = SQL::getSQLForInsertContent();
		foreach ($records as $row => $contents) {
			$result &= $this->deleteContentsByDictionaryIdAndRows($dictionaryId, $row);
			if(!$this->isAllEmptyRow($contents)) {
				$result &= $this->insertContents($dictionaryId, $row, $supportedLanguages, $contents, $sql);
			}	
		}
		return $result;
	}
	
	protected function createContentsAll($dictionaryId, $supportedLanguages, $records) {
		$result = true;
		$sql = SQL::getSQLForInsertContent();
		$row = $this->getMaxRowByDictionaryId($dictionaryId) + 1;
		foreach ($records as $index => $contents) {
			if($this->isAllEmptyRow($contents)) continue;
			$result &= $this->insertContents($dictionaryId, $row, $supportedLanguages, $contents, $sql);
			$row++;
		}
		return $result;
	}
	
	protected function removeLanguagesAll($dictionaryId, $languages = array()) {
		$sql = SQL::getSQLForDeleteContentsByDictionaryIdAndLanguages();
		$langStr = array();
		foreach ($languages as $lang) {
			$langStr[] = "'{$lang}'";
		}
		$query = sprintf($sql, intval($dictionaryId), implode(",", $langStr));
		return $this->db->query($query);
	}
	
	protected function isAllEmptyRow($contents) {
		$result = true;
		foreach($contents as $key => $value) {
			if($value) $result = false;
		}
		return $result;
	}
	
	protected function insertContents($dictionaryId, $row, $languages, $contents, $sql) {
		$result = true;
		foreach ($contents as $key => $text) {
			$lang = $this->escape(is_numeric($key) ? $languages[$key] : $key);
			$text = $this->escape($text);
			$query = sprintf($sql, $dictionaryId, $lang, $text , $row);
			$result &= $this->db->query($query);
		}
		return $result;
	}

	public function getDictionary($id) {
		$sql = SQL::getSQLForSelectDictionaryByDictionaryId();
		$sql = sprintf($sql, intval($id));
		$result = $this->db->query($sql);
		$row = array();
		if ($result) {
			$row = $this->db->fetchArray($result);
		}
		return $row;
	}

	public function getDictionaryByName($name) {
		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryContentsTable = $this->db->prefix('user_dictionary_contents');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');

		$sql  = '';
		$sql .= ' SELECT dictionary_name FROM '.$userDictionaryTable;
		$sql .= ' WHERE `dictionary_name` = \'%s\' AND `delete_flag` = 0 ';

		$sql = sprintf($sql, $this->escape($params['name']));
		$result = $this->db->query($sql);
		$row = array();
		if ($result) {
			$row = $this->db->fetchArray($result);
		}
		return $row;
	}

	/**
	 *
	 * @return bool
	 */
	private function canEdit($dictionaryId) {

		if ($this->root->mContext->mXoopsUser->isAdmin()) {
			return true;
		}

		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());
		$userId = $this->root->mContext->mXoopsUser->get('uid');


		$sql = '';
		$sql .= 'select * from '.$userDictionaryTable.' as D left join '.$userDictionaryPermissionTable.' as P on D.user_dictionary_id = P.user_dictionary_id ';
		$sql .= 'where D.delete_flag = \'0\' and D.user_dictionary_id = \''.$dictionaryId.'\';';
		$result = $this->db->query($sql);
		if ($this->db->getRowsNum($result) == 0) {
			return false;
		}


		while ($row = $this->db->fetchArray($result)) {
			if ($row['user_id'] == $userId) {
				return true;
			} else if ($row['permission_type'] == 'all' && $row['edit'] == 1) {
				return true;
			}

		}
		return false;
	}
	/**
	 *
	 * @return bool
	 */
	private function canLoad($dictionaryId) {

		if ($this->root->mContext->mXoopsUser->isAdmin()) {
			return true;
		}

		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());
		$userId = $this->root->mContext->mXoopsUser->get('uid');


		$sql = '';
		$sql .= 'select * from '.$userDictionaryTable.' as D left join '.$userDictionaryPermissionTable.' as P on D.user_dictionary_id = P.user_dictionary_id ';
		$sql .= 'where D.delete_flag = \'0\' and D.user_dictionary_id = \''.$dictionaryId.'\';';

		$result = $this->db->query($sql);
		if ($this->db->getRowsNum($result) == 0) {
			return false;
		}


		while ($row = $this->db->fetchArray($result)) {
			if ($row['user_id'] == $userId) {
				return true;
			} else if ($row['permission_type'] == 'all') {
				return true;
			}

		}
		return false;
	}

	private function canDelete($dictionaryId) {

		if ($this->root->mContext->mXoopsUser->isAdmin()) {
			return true;
		}

		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userId = $this->root->mContext->mXoopsUser->get('uid');

		$sql  = ' SELECT COUNT(*) AS CNT ';
		$sql .= ' FROM %s ';
		$sql .= ' WHERE `user_id` = \'%s\' ';
		$sql .= ' AND `delete_flag` = \'0\' ';

		$sql = sprintf($sql, $userDictionaryTable, $userId);
		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			return (bool)$row['CNT'];
		}
	}

	private function deletePermission($dictionaryId) {
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$sql  = '';
		$sql .= ' DELETE FROM '.$userDictionaryPermissionTable;
		$sql .= ' WHERE `user_dictionary_id` = \'%d\' ';

		$sql = sprintf($sql, $dictionaryId);
		$result = $this->db->query($sql);

		return $result;
	}

	private function getPermission($id) {
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$userDictionaryId = intval($id);

		$sql  = '';
		$sql .= ' SELECT * FROM '.$userDictionaryPermissionTable.' ';
		$sql .= ' WHERE user_dictionary_id = %d AND delete_flag = 0 ';
		$sql .= ' ORDER BY permission_type ASC';

		$sql = sprintf($sql, $userDictionaryId);
		//		echo $sql;
		$result = $this->db->query($sql);

		$permission = array();
		$permission['dictionary']['edit'] = 'user';
		$permission['dictionary']['view'] = 'user';

		while ($row = $this->db->fetchArray($result)) {
			if ($row['permission_type'] == 'all') {
				if ($row['edit']) {
					$permission['dictionary']['edit'] = 'all';
					$permission['dictionary']['view'] = 'all';
					continue;
				} else if ($row['view']) {
					$permission['dictionary']['view'] = 'all';
				}
			}

		}

		return $permission;
	}

	/**
	 *
	 * @param $params
	 * @return unknown_type
	 */
	private function setPermission($params) {
		$userDictionaryPermissionTable = $this->db->prefix('user_dictionary_permission');
		$userDictionaryId = $params['dictionaryId'];
		$this->deletePermission($params['dictionaryId']);

		$sql  = '';
		$sql .= ' INSERT '.$userDictionaryPermissionTable.' SET ';
		$sql .= ' user_dictionary_id = ?, permission_type = ?, ';
		$sql .= ' permission_type_id = ?, `view` = ?, `use` = ?, edit = ? ';

		if ($params['editPermission'] == 'all') {
			$this->db->prepare($sql);
			$this->db->bind_param('isiiii', $userDictionaryId, 'all', 0, 1, 1, 1);
			$this->db->execute();
		}


		if ($params['editPermission'] != 'all' && $params['viewPermission'] == 'all') {
			$this->db->prepare($sql);
			$this->db->bind_param('isiiii', $userDictionaryId, 'all', 0, 1, 1, 0);
			$this->db->execute();
		}

	}

	/**
	 *
	 * @param $dictionaryId
	 * @return bool
	 */
	private function canChangePermission($dictionaryId) {
		if ($this->root->mContext->mXoopsUser->isAdmin()) {
			return true;
		}

		$userDictionaryTable = $this->db->prefix('user_dictionary');
		$userId = $this->root->mContext->mXoopsUser->get('uid');

		$sql  = '';
		$sql .= ' SELECT COUNT(*) CNT FROM '.$userDictionaryTable.' ';
		$sql .= ' WHERE user_id = %d AND user_dictionary_id = %d AND delete_flag = 0 ';

		$sql = sprintf($sql, $userId, $dictionaryId);

		$result = $this->db->query($sql);
		if ($row = $this->db->fetchArray($result)) {
			if ($row['CNT'] > 0) {
				return true;
			}
		}
		return false;
	}


	/**
	 *
	 * @param $str
	 * @return string
	 */
	private function escape($str) {
		if ( get_magic_quotes_gpc() ) {
			$str = stripslashes( $str );
		}
		return mysql_real_escape_string($str);
	}

	/**
	 *
	 * @return array(
	 * 	status
	 *  message
	 *  contents
	 * )
	 */
	private function dictionaryTableCheck($dictTable, $typeId) {
		$response = array(
			'status' => 'OK',
			'message' => 'OK'
		);
		$supportedLanguages = $dictTable[0];
		$columnsCount = count($supportedLanguages);
		if ($columnsCount < 2) {
			$response['status'] = 'ERROR';
			$response['message'] = 'Error!';
			return $response;
		}
		
		if(!isAllSupportedLanguage($supportedLanguages, $typeId)) {
			$response['status'] = 'ERROR';
			$response['message'] = sprintf(_MI_DICTIONARY_ERROR_UPLOAD_INVALID_LANGUAGE_TAG, $language);
			return $response;
		}
		
		if($this->isDuplicated($supportedLanguages)) {
				$response['status'] = 'ERROR';
				$response['message'] = sprintf(_MI_DICTIONARY_ERROR_MULTIPLE_ROWS, $language);
				return $response;
		}
		
		foreach ($dictTable as $row) {
			if (count($row) != $columnsCount) {
				$response['status'] = 'ERROR';
				$response['message'] = _MI_DICTIONARY_ERROR_FILE_FORMAT_INVALID;
				return $response;
			}
		}
		return $response;
	}
	
	public function isDeploy($name) {
		$tableName = $this->db->prefix('user_dictionary');

		$sql  = ' SELECT * ';
		$sql .= ' FROM   '.$tableName.' ';
		$sql .= ' WHERE  `dictionary_name` = \''.$this->escape($name).'\' ';
		$sql .= ' AND    `delete_flag` = \'0\' ';

		$result = $this->db->query($sql);
		$row = $this->db->fetchArray($result);
		if ($row) {
			return $row['deploy_flag'] == '0' ? false : true;
		}
		return false;
	}
	
	protected function isDuplicated($list) {
		$checked = array();
		foreach ($list as $l) {
			if (in_array($l, $checked)) return true;
			$checked[] = $l;
		}
		return false;
	}


	private function updateTranslationPath($dictname){
		$PathSettingTbl = $this->db->prefix('translation_path_setting');

		$sql  = ' SELECT id,bind_user_dict_ids ';
		$sql .= ' FROM '.$PathSettingTbl.' ';
		$sql .= ' WHERE bind_user_dict_ids LIKE \'%'.$dictname.'%\' ';
		$sql .= ' AND dictionary_flag = 2 ';
		$sql .= ' AND delete_flag = 0 ';
		if ($rs = $this->db->query($sql)) {
			while($row = $this->db->fetchArray($rs)){
				$id = $row['id'];
				$u_dict_ids = $row['bind_user_dict_ids'];

				$pat[0] = '/^'.$dictname.',/'; $rep[0] = '';
				$pat[1] = '/,'.$dictname.',/'; $rep[1] = ',';
				$pat[2] = '/,'.$dictname.'$/'; $rep[2] = '';

				$new_dict_ids = trim(preg_replace($pat,$rep,$u_dict_ids));

				$sql  = ' UPDATE '.$PathSettingTbl.' SET ';
				$sql .= ' edit_date = Now(),';
				$sql .= ' bind_user_dict_ids = \''.$new_dict_ids.'\' ';
				$sql .= ' WHERE id = '.$id.' ';
				$this->db->query($sql);
			}
		}
	}
	
	protected function getLanguagesByDictionaryId($dictionaryId) {
		$sql = SQL::getSQLForSelectDistinctAllLanguagesByDictionaryId();
		$sql = sprintf($sql, $dictionaryId);
		$results = array();
		if($rs = $this->db->query($sql)) {
			while($row = $this->db->fetchArray($rs)) {
				$results[] = $row['language'];
			}
		}
		
		return $results;
	}
	
	protected function getAllDictionaryContentsByDictionaryId($dictionaryId, $limit = 10, $offset = 0) {
 		$langs = $this->getLanguagesByDictionaryId($dictionaryId);
		$typeId = $this->getTypeIdByDictionaryId($dictionaryId);
		if (intval($typeId) == DictionaryType::NORMALIZE) {
			$normalizedLang = $this->getNormalizedLangByDictionaryId($dictionaryId);
			$sql = SQL::getSQLForSelectAllContentsByDictionaryId($langs, $dictionaryId, $normalizedLang);
			$langs = array_reverse($langs);
		} else {
			$sql = SQL::getSQLForSelectAllContentsByDictionaryId($langs, $dictionaryId);
		}

		if($limit != -1) {
			$sql .= " LIMIT {$limit} OFFSET {$offset}";
		}
		$langHash = array("row" => "0");
		foreach($langs as $l) $langHash[$l] = $l;
		return array_merge(array($langHash), $this->executeQueryForContentsAsHash($sql));
	}

	protected function getTypeIdByDictionaryId($dictionaryId) {
		$dict = $this->getDictionary($dictionaryId);
		return $dict['type_id'];
	}
	
	protected function countAllDictionaryContentsByDictionaryId($dictionaryId) {
 		$langs = $this->getLanguagesByDictionaryId($dictionaryId);
		$typeId = $this->getTypeIdByDictionaryId($dictionaryId);
		if (intval($typeId) == DictionaryType::NORMALIZE) {
			$normalizedLang = $this->getNormalizedLangByDictionaryId($dictionaryId);
			$sql = sprintf(SQL::getSQLForCountAllContentsByDictionaryId($normalizedLang), $dictionaryId);
		} else {
			$sql = sprintf(SQL::getSQLForCountAllContentsByDictionaryId(), $dictionaryId);
		}
		if($rs = $this->db->query($sql)) {
			if($row = $this->db->fetchArray($rs)) return $row['cnt'];				
		}
		return 0;
	}

	protected function getNormalizedLangByDictionaryId($dictionaryId) {
		$langs = $this->getLanguagesByDictionaryId($dictionaryId);
		foreach ($langs as $lang) {
			if (strpos($lang, self::NORMALIZED_LANG_SUFFIX) !== false) { return $lang; }
		}
		return '';
	}
	
	// 旧デザイン用の動作の為 レコードからrowカラムの値を省き、配列にしたものを返す
	protected function getAllDictionaryContentsWithoutRowByDictionaryId($dictionaryId) {
		$langs = $this->getLanguagesByDictionaryId($dictionaryId);
		$sql = SQL::getSQLForSelectAllContentsByDictionaryId($langs, $dictionaryId);
		$contents = $this->executeQueryForContentsAsArray($sql);
		array_unshift($contents, $langs);
		return $contents;
	}
	
	// 旧デザイン用の動作の為 レコードからrowカラムの値を省き、配列にしたものを返す
	protected function executeQueryForContentsAsArray($sql) {
		$results = array();
		if($rs = $this->db->query($sql)) {
			while($row = $this->db->fetchArray($rs)) {
				unset($row["row"]);
				$results[] = array_values($row);				
			}
		}
		return $results;
	}
	// 新デザイン用のレコードをカラムと値のハッシュ形式のものを返す
	protected function executeQueryForContentsAsHash($sql) {
		$results = array(); 
		if($rs = $this->db->query($sql)) {
			while($row = $this->db->fetchArray($rs)) {
				$results[] = $row;
			}
		}
		return $results;
	}
	
	protected function getMaxRowByDictionaryId($dictionaryId) {
		$sql = SQL::getSQLForMaxRowByDictionaryId();
		$sql = sprintf($sql, $dictionaryId);
		if($rs = $this->db->query($sql)) {
			if($row = $this->db->fetchArray($rs)) return $row["row"];
		}
		return 0;
	}
	
	protected function arrayToHash($array) {
		$results = array();
		foreach($array as $i => $value)
			$results[$value] = $value;
		return $results;
	}
	
	protected function getLanguagesByTypeId($typeId) {
		$result = array();
		$sql = SQL::getSQLForSelectDistinctAllLanguagesGroupByTypeId();
		if($rs = $this->db->query($sql)) {
			while($row = $this->db->fetchArray($rs)) {
				$result[$row["type_id"]][] = $row["language"];
			}
		}
		return $result[$typeId];
	}
	
	protected function getCountDictionaryBySearchKeyword($typeId, $sourceLanguage, $keywords, $matchingMethod) {
		if(!is_array($keywords)) $keywords = array($keywords);
		$keywords = allKeywords2conditions($keywords, $matchingMethod);
		
		$sql = SQL::getSQLForCountContentsByKeywords($typeId, $sourceLanguage, $keywords);
		if (!$result = $this->db->query($sql)) {
			return 'SQL ERROR IN PreSearch';
		} else {
			$row = $this->db->fetchArray($result);
			return $row['cnt'];
		}
	}
	protected function getContentsBySearchKeyword($typeId, $sourceLanguage, $keywords, $matchingMethod, $targetLanguages, $limit = 10, $offset = 0) {
		if(!is_array($keywords)) $keywords = array($keywords);
		$keywords = allKeywords2conditions($keywords, $matchingMethod);
		$targetLanguages[] = $sourceLanguage;
		
		$sql = SQL::getSQLForSearchContentsByKeywords($targetLanguages, $typeId, $sourceLanguage, $keywords);
		if($limit != -1) {
			$sql .= " LIMIT {$limit} OFFSET {$offset}";
		}
		
		$result = array();
		if($rs = $this->db->query($sql)) {
			while($row = $this->db->fetchArray($rs)) {
				$record = array();
				$record['dictionaryName'] = $row['dictionary_name'];
				$record['userDictionaryId'] = $row['user_dictionary_id'];
				$record['row'] = $row['row'];
				
				$record['languages'] = array();
				foreach($targetLanguages as $lang) {
					$record['languages'][$lang] = $row[$lang];
				}
				
				$result[] = $record;
			}
		}
		return $result;
	}
	
	protected function deleteContentsByDictionaryIdAndRows($dictionaryId, $rows = array()) {
		if(!is_array($rows)) $rows = array($rows);
		if(!count($rows) > 0) return;
		$rowStrs = array();
		foreach($rows as $r) $rowStrs[] = "'".intval($r)."'";
		$sql = SQL::getSQLForDeleteContentsByDictionaryIdAndRow();
		return $this->db->query(sprintf($sql, intval($dictionaryId), implode(",", $rowStrs)));
	}
	
	// 更新日付を最新化（排他制御）
	protected function updateDictionaryLastUpdateToCurrentDate($dictionaryId) {
		$sql = SQL::getSQLForUpdateDictionaryLastUpdate();
		$sql = sprintf($sql, time(), $dictionaryId);
		return $this->db->query($sql);
	}
	
	protected function getAllDictionariesByTypeId($typeId, $limit = 10, $offset = 0) {
		$sql = SQL::getSQLForSelectAllDictionariesByTypeId();
		if($limit != -1) {
			$sql .= " LIMIT {$limit} OFFSET {$offset}";
		}
		$rs = $this->db->query(sprintf($sql, intval($typeId)));
		$results = array();
		while($row = $this->db->fetchArray($rs)) {
			$row['supportedLanguages'] = explode(',', $row['languages']); 
			$row['createDateFormat'] = formatTimestamp($row['createDate'], 'Y/m/d H:i'); //'m');
			$row['updateDateFormat'] = formatTimestamp($row['updateDate'], 'Y/m/d H:i'); //'m');

			$row['view'] = $this->hasViewPermission($row);
			$row['edit'] = $this->hasEditPermission($row);
			$row['deployFlag'] = (bool)$row['deploy_flag'];
			$row['delete'] = $this->canDeleteDictionaryByUserId($row['userId']);
			$results[] = $row;
		}
		return $results;
	}
	
	protected function countAllDictionariesByTypeId($typeId) {
		$sql = sprintf(SQL::getSQLForCountAllDictionariesByTypeId(), $typeId);
		if($rs = $this->db->query($sql)) {
			if($row = $this->db->fetchArray($rs)) return $row['cnt'];				
		}
		return 0;
	}
	
}
?>