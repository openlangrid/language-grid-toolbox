<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once(dirname(__FILE__).'/../include/Functions.php');
class LangridServicesClass {

	private $db = null;
	private $TBL = null;

	function __construct() {
		global $xoopsDB;
		$this->db = $xoopsDB;
		$this->TBL = $xoopsDB->prefix('langrid_services');
	}

	function getTranslators() {
		return $this->search(array('allowed_app_provision' => 'CLIENT_CONTROL', 'service_type'=>'TRANSLATION'));
	}
	function searchTranslation($serviceId) {
		$ret = $this->search(array('allowed_app_provision' => 'CLIENT_CONTROL', 'service_type'=>'TRANSLATION', 'service_id'=>$serviceId));
		if (count($ret) == 1) {
			return $ret[0];
		} else {
			return array();
		}
	}

	function getDictionarys() {
		return $this->search(array('allowed_app_provision' => 'CLIENT_CONTROL', 'service_type'=>'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH'));
	}
	function searchDictionary($serviceId) {
		return $this->search(array('allowed_app_provision' => 'CLIENT_CONTROL', 'service_type'=>'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH', 'service_id'=>$serviceId));
	}

	function getAnalyses() {
		return $this->search(array('allowed_app_provision' => 'CLIENT_CONTROL', 'service_type'=>'MORPHOLOGICALANALYSIS'));
	}
	function searchgetAnalyzer($serviceId) {
		return $this->search(array('allowed_app_provision' => 'CLIENT_CONTROL', 'service_type'=>'MORPHOLOGICALANALYSIS', 'service_id'=>$serviceId));
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
		$where = "where ";
		if(count($wheres) > 0){
			foreach ($wheres as $key => $value) {
				$where .= '`'.$key.'` = \'' . $this->sqlEscape($value) . '\' and';
			}
			$where = substr($where, 0, -3);
		}

		$sql = '';
		$sql .= 'select ';
		$sql .= 'service_id,';
		$sql .= 'service_name,';
		$sql .= 'service_type,';
		$sql .= 'endpoint_url,';
		$sql .= 'supported_languages_paths,';
		$sql .= 'copyright,';
		$sql .= 'license ';
		$sql .= 'from '.$this->TBL.' ';
		$sql .= $where;
		//$sql .= 'order by service_type, service_id';
		$result = array();
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchArray($rs)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function getTranslatorAllSupportLanguagePairs() {
		$allpaths = array();
		$paths = array();

		$sql = '';
		$sql .= 'select ';
		$sql .= 'supported_languages_paths ';
		$sql .= 'from '.$this->TBL.' ';
		$sql .= 'where (service_type = \'' . $this->sqlEscape('TRANSLATION') . '\' ';
		$sql .= 'or service_type = \'' . $this->sqlEscape('IMPORTED_TRANSLATION') . '\') ';
		$sql .= 'group by supported_languages_paths ';
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchArray($rs)) {
				$pathArry = explode(',',$row['supported_languages_paths']);
				$allpaths = array_merge($allpaths, $pathArry);
			}
		}
		sort($allpaths);

		$pairs = array();
		$srcLangs = array();
		$tgtLangs = array();
		foreach($allpaths as $path) {
			$pair = explode('2', $path);
			$pairs[] = $pair;
			$srcLangs[] = $pair[0];
			$tgtLangs[] = $pair[1];
		}

		$srcLangs = array_merge(array(), array_unique($srcLangs));
		$tgtLangs = array_merge(array(), array_unique($tgtLangs));

		$source = array();
		foreach ($srcLangs as $lang) {
			$source = array_merge($source, array($lang=>getLanguageName($lang)));
		}
		$target = array();
		foreach ($tgtLangs as $lang) {
			$target = array_merge($target, array($lang=>getLanguageName($lang)));
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
		$sql .= " WHERE `service_id` = '%s' ";

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
		$sql .= 'select * from '.$this->TBL.' where ';
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
		$sql .= " WHERE `service_id` = '%s' ";

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
		$sql .= " WHERE (`service_type` = '%s' ";
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
