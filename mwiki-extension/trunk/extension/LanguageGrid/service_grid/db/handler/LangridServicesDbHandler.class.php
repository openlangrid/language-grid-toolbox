<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
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
require_once dirname(__FILE__).'/../adapter/DaoAdapter.class.php';
/**
 * <#if locale="en">
 * Language Grid services related database handler class
 * <#elseif locale="ja">
 * 言語グリッド登録サービス関連のデータベースハンドラクラス
 * </#if>
 */
class LangridServicesDbHandler {

	private $db = null;
	private $TBL = null;

	protected $m_LangridServicesDao;
	protected $m_LangridServicesConfigDao;
	function __construct() {
		$adapter = DaoAdapter::getAdapter();
    	$this->db = $adapter->getDataBase();
    	$this->TBL = $this->db->tableName('langrid_services');
		$this->m_LangridServicesDao = $adapter->getLangridServicesDao();
		$this->m_LangridServicesConfigDao = $adapter->getLangridServicesConfigDao();
	}

	public function getLangridServicesDao() {
		return $this->m_LangridServicesDao;
	}
	public function getLangridServicesConfigDao() {
		return $this->m_LangridServicesConfigDao;
	}

	function getConfigDao() {
		return $this->getLangridServicesConfigDao();
		// $class =& new LangridServicesConfigDao($this->db);
		// return $class;
	}

	function getTranslators() {
		return $this->search(array('service_type'=>'TRANSLATION'));
	}
	function searchTranslation($serviceId) {
		$ret = $this->search(array('service_type'=>'TRANSLATION', 'service_id'=>$serviceId));
		if (count($ret) == 1) {
			return $ret[0];
		} else {
			return array();
		}
	}

	function getDictionarys() {
		return $this->search(array('service_type'=>'DICTIONARY'));
	}
	function searchDictionary($serviceId) {
		return $this->search(array('service_type'=>'DICTIONARY', 'service_id'=>$serviceId));
	}

	function getAnalyses() {
		return $this->search(array('service_type'=>'ANALYZER'));
	}
	function searchgetAnalyzer($serviceId) {
		return $this->search(array('service_type'=>'ANALYZER', 'service_id'=>$serviceId));
	}

	function getLocalDictionarys() {
		return $this->search(array('service_type'=>'IMPORTED_DICTIONARY'));
	}
	function searchLocalDictionary($serviceId) {
		return $this->search(array('service_type'=>'IMPORTED_DICTIONARY', 'service_id'=>$serviceId));
	}
	function searchLocalDictionaryByEndpoint($endPoint) {
		return $this->search(array('service_type'=>'IMPORTED_DICTIONARY', 'endpoint_url'=>$endPoint));
	}


	function getLocalTranslators() {
		return $this->search(array('service_type'=>'IMPORTED_TRANSLATION'));
	}
	function searchLocalTranslators($serviceId) {
		return $this->search(array('service_type'=>'IMPORTED_TRANSLATION', 'service_id'=>$serviceId));
	}
	function searchLocalTranslatorsByEndpoint($endPoint) {
		return $this->search(array('service_type'=>'IMPORTED_TRANSLATION', 'endpoint_url'=>$endPoint));
	}

	private function search($wheres) {
		$sql = '';
		$sql .= 'select * from '.$this->TBL.' where delete_flag = \'0\' and';
		foreach ($wheres as $key => $value) {
			$sql .= '`'.$key.'` = \'' . $this->sqlEscape($value) . '\' and';
		}
		$sql = substr($sql, 0, -4);
		$sql .= 'order by service_type, service_id';
		$result = array();
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchRow($rs)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function getTranslatorAllSupportLanguagePairs() {
		$allpaths = array();
		$services = $this->search(array('service_type'=>'TRANSLATION'));
		$services2 = $this->search(array('service_type'=>'IMPORTED_TRANSLATION'));
		$services = array_merge($services, $services2);
		foreach ($services as $service) {
			$pathArry = explode(',', $service['supported_languages_paths']);
			$allpaths = array_merge($allpaths, $pathArry);
		}
		sort($allpaths);

		$pairs = array();
		$srcLangs = array();
		$tgtLangs = array();
		foreach($allpaths as $path) {
			$pair = explode('2', $path);
			$pairs[] = $pair;
			$srcLangs[] = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $pair[0]);
			$tgtLangs[] = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $pair[1]);
		}

		$srcLangs = array_merge(array(), array_unique($srcLangs));
		$tgtLangs = array_merge(array(), array_unique($tgtLangs));

		$source = array();
		foreach ($srcLangs as $lang) {
			$source = array_merge($source, array($lang=>getLangridLanguageName($lang)));
		}
		$target = array();
		foreach ($tgtLangs as $lang) {
			$target = array_merge($target, array($lang=>getLangridLanguageName($lang)));
		}

		asort($source);
		asort($target);

		return array('sourceLanguages'=>$source, 'targetLanguages'=>$target);
	}

	function getAllSupportLanguagePairs() {
		global $LANGRID_LANGUAGE_ARRAY;
		return array('sourceLanguages'=>$LANGRID_LANGUAGE_ARRAY, 'targetLanguages'=>$LANGRID_LANGUAGE_ARRAY);
	}

	/**
	 * @param LangridServiceTO $langridServiceTO
	 * @throws IllegalArgumentException
	 * @throws SQLException
	 * @return LangridServiceTO created langrid service
	 */
	public function create($langridServiceTO) {

		$serviceId = md5(time().$langridServiceTO->serviceName.$langridServiceTO->endpointUrl);

		$sql = "";
		$sql .= "INSERT INTO `%s` ";
		$sql .= "  (`service_id`, `service_type`, `service_name`, `endpoint_url` ";
		$sql .= "     , `supported_languages_paths`, `now_active`, `organization`";
		$sql .= "     , `copyright`, `license`, `description` ";
		$sql .= "  )";
		$sql .= "  VALUES ";
		$sql .= "  ('%s', '%s', '%s', '%s' ";
		$sql .= "     , '%s', '%s', '%s' ";
		$sql .= "     , '%s', '%s', '%s' ";
		$sql .= "  )";

		$sql = sprintf($sql
			, $this->TBL
			, $serviceId
			, EnumLangridServiceType::valueOf($langridServiceTO->serviceType)
			, self::sqlEscape($langridServiceTO->serviceName)
			, self::sqlEscape($langridServiceTO->endpointUrl)
			, self::sqlEscape($langridServiceTO->supportedLanguagePaths)
			, ($langridServiceTO->nowActive == 'on') ? 'on' : 'off'
			, self::sqlEscape($langridServiceTO->organization)
			, self::sqlEscape($langridServiceTO->copyright)
			, self::sqlEscape($langridServiceTO->license)
			, self::sqlEscape($langridServiceTO->description)
		);

		$result = $this->db->query($sql);

		if (!$result) {
			throw new SQLException('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		return $this->find($serviceId);
	}

	/**
	 * @param LangridServiceTO $langridServiceTO
	 * @throws SQLException
	 * @return LangridServiceTO updated langrid service
	 */
	public function update($langridServiceTO) {

		$sql = "";
		$sql .= "UPDATE `%s` SET ";
		$sql .= "  `endpoint_url` = '%s' ";
		$sql .= "  , `supported_languages_paths` = '%s' ";
		$sql .= "  , `now_active` = '%s' ";
		$sql .= "  , `organization` = '%s' ";
		$sql .= "  , `copyright` = '%s' ";
		$sql .= "  , `license` = '%s' ";
		$sql .= "  , `description` = '%s' ";
		$sql .= " WHERE `service_id` = '%s' AND `delete_flag` = '0' ";

		$sql = sprintf($sql
			, $this->TBL
			, self::sqlEscape($langridServiceTO->endpointUrl)
			, self::sqlEscape($langridServiceTO->supportedLanguagePaths)
			, ($langridServiceTO->nowActive == 'on') ? 'on' : 'off'
			, self::sqlEscape($langridServiceTO->organization)
			, self::sqlEscape($langridServiceTO->copyright)
			, self::sqlEscape($langridServiceTO->license)
			, self::sqlEscape($langridServiceTO->description)
			, $langridServiceTO->serviceId
		);

		$result = $this->db->query($sql);

		if (!$result) {
			throw new SQLException('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		return $this->find($langridServiceTO->serviceId);
	}

	/**
	 * @param String $serviceId
	 * @throws SQLException
	 * @return void
	 */
	public function remove($serviceId) {

		$sql = "";
		$sql .= "DELETE FROM `%s` WHERE `service_id` = '%s' ";

		$sql = sprintf($sql
			, $this->TBL
			, self::sqlEscape($serviceId)
		);

		$result = $this->db->query($sql);

		if (!$result) {
			throw new SQLException('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		return;
	}

	public function isExistsServiceName($serviceName) {
		return count($this->search(
			array(
				'service_name' => $serviceName
			)
		)) > 0;
	}
	public function isExistsEndpointUrl($endpointUrl, $serviceId = '') {
		$sql = '';
		$sql .= 'select * from '.$this->TBL.' where delete_flag = \'0\' and';
		$sql .= '`endpoint_url` = \'' . $this->sqlEscape($endpointUrl) . '\' and';
		$sql .= '`service_id` != \'' . $this->sqlEscape($serviceId) . '\' ';
		$result = array();
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchArray($rs)) {
				$result[] = $row;
			}
		}
		return count($result) > 0;
	}

	/**
	 * @param String $serviceId
	 * @return LangridServiceTO
	 */
	public function find($serviceId) {
		$sql = "";
		$sql .= " SELECT ";
		$sql .= "   `service_id`, `service_type`, `service_name`, `endpoint_url` ";
		$sql .= "   , `supported_languages_paths`, `now_active`, `organization` ";
		$sql .= "   , `copyright`, `license`, `description`, `create_date` ";
		$sql .= " FROM `%s` ";
		$sql .= " WHERE `service_id` = '%s' AND `delete_flag` = '0' ";

		$sql = sprintf($sql
			, $this->TBL
			, self::sqlEscape($serviceId)
		);

		$result = $this->db->query($sql);

		if (!$result) {
			throw new SQLException('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		$langridServiceTO = null;
		if ($row = $this->db->fetchArray($result)) {
			$langridServiceTO = $this->getTOFromRow($row);
		}

		return $langridServiceTO;
	}

	private function getTOFromRow($row) {
		$langridServiceTO = new LangridServiceTO();
		$langridServiceTO->serviceId = $row['service_id'];
		$langridServiceTO->serviceType = $row['service_type'];
		$langridServiceTO->serviceName = $row['service_name'];
		$langridServiceTO->endpointUrl = $row['endpoint_url'];
		$langridServiceTO->supportedLanguagePaths = $row['supported_languages_paths'];
		$langridServiceTO->nowActive = $row['now_active'];
		$langridServiceTO->organization = $row['organization'];
		$langridServiceTO->copyright = $row['copyright'];
		$langridServiceTO->license = $row['license'];
		$langridServiceTO->description = $row['description'];
		$langridServiceTO->createDate = $row['create_date'];
		$langridServiceTO->editDate = null;
		$langridServiceTO->deleteFlag = null;
		return $langridServiceTO;
	}

	public function getAllImportedServices() {
		$sql = "";
		$sql .= " SELECT ";
		$sql .= "   `service_id`, `service_type`, `service_name`, `endpoint_url` ";
		$sql .= "   , `supported_languages_paths`, `now_active`, `organization` ";
		$sql .= "   , `copyright`, `license`, `description`, `create_date` ";
		$sql .= " FROM `%s` ";
		$sql .= " WHERE `delete_flag` = '0' ";
		$sql .= " AND (`service_type` = '%s' ";
		$sql .= " OR `service_type` = '%s') ";

		$sql = sprintf($sql
			, $this->TBL
			, EnumLangridServiceType::$IMPORTED_DICTIONARY
			, EnumLangridServiceType::$IMPORTED_TRANSLATOR
		);

		$result = $this->db->query($sql);

		if (!$result) {
			throw new SQLException('SQL Error occured "'.$sql.'" in '.__FILE__.' at line '.__LINE__);
		}

		$importedServices = array();
		while ($row = $this->db->fetchArray($result)) {
			$importedServices[] = $this->getTOFromRow($row);
		}

		return $importedServices;
	}

	public function refreshServise($profileArray, $serviceType) {
		//$dao = new LangridServicesDao($this->db);
		$dao = $this->m_LangridServicesDao;

		if (!$dao->deleteByServiceType($serviceType)) {
			return;
		}

		foreach ($profileArray as $service) {
			$paths = implode(',', $service['path']);
			$obj = $dao->create(true);
			$obj->set('service_id', $service['serviceId']);
			$obj->set('service_type', $serviceType);
			$obj->set('service_name', $service['name']);
			$obj->set('endpoint_url', $service['endpointUrl']);
			$obj->set('supported_languages_paths', $paths);
			$obj->set('organization', addslashes($service['organization']));
			$obj->set('copyright', addslashes($service['copyright']));
			$obj->set('license', addslashes($service['license']));
			$obj->set('description', addslashes($service['description']));
//			$obj->set('registered_date', null);
//			$obj->set('updated_date', null);
			$obj->set('create_date', time());
			$obj->set('delete_flag', '0');

			$dao->insert($obj);
		}
	}

	/**
	 * @param String $string
	 * @return String $string escaped string for sql
	 */
	public static function sqlEscape($string) {
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}
		return mysql_real_escape_string($string);
	}
}

class SQLException extends Exception {}
class IllegalArgumentException extends Exception {}

class EnumLangridServiceType {
	public static $DICTIONARY = 'DICTIONARY';
	public static $TRANSLATOR = 'TRANSLATION';
	public static $IMPORTED_DICTIONARY = 'IMPORTED_DICTIONARY';
	public static $IMPORTED_TRANSLATOR = 'IMPORTED_TRANSLATION';

	public static function valueOf($serviceType) {
		switch ($serviceType) {
		case self::$DICTIONARY:
			return self::$DICTIONARY;
		case self::$TRANSLATOR:
			return self::$TRANSLATOR;
		case self::$IMPORTED_DICTIONARY:
			return self::$IMPORTED_DICTIONARY;
		case self::$IMPORTED_TRANSLATOR:
			return self::$IMPORTED_TRANSLATOR;
		default:
			throw new IllegalArgumentException($serviceType.' is not EnumLangridServiceType.');
			break;
		}
	}
}

class LangridServiceTO {
	public $serviceId = '';
	public $serviceType = '';
	public $serviceName = '';
	public $endpointUrl = '';
	public $supportedLanguagePaths = '';
	public $nowActive = '';
	public $organization = '';
	public $copyright = '';
	public $license = '';
	public $description = '';
	public $createDate = '';
	public $editDate = '';
	public $deleteFlag = '';
}
?>
