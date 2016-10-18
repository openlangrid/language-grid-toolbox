<?php
require_once XOOPS_ROOT_PATH . '/api/class/client/ResourceClient.class.php';
require_once dirname(__FILE__).'/factory/client-factory.php';
require_once dirname(__FILE__).'/util/qa-category-util.php';
require_once dirname(__FILE__).'/util/qa-resource-util.php';

require_once XOOPS_ROOT_PATH .'/modules/langrid/class/UserDictionaryClass.php';
require_once XOOPS_ROOT_PATH .'/modules/langrid/class/PathSettingWrapperClass.php';




class GlossaryList {

	// for searching glossary dictionary data
	const SEARCH_KEY = 'GLOSSARY';

	/**
	 * Returns all glossary dictionary list.
	 * @return array of glossary dictionary.
	 */
	static function findAllFromGlossaryClient() {

		$result = array(
			'status' => 'OK',
			'message' => 'Success!',
			'contents' => array()
		);

		try {
			$factory = ClientFactory::getFactory(self::SEARCH_KEY);
			$resourceClient = $factory->createResourceClient();
			$moduleClient = $factory->createModuleClient();
			$resourcesResult = $resourceClient->getAllLanguageResources(self::SEARCH_KEY);
			$globalCategories = array();
			$result['contents']['resources'] = array();

			foreach ($resourcesResult['contents'] as $resource) {
				$categoryIds = array();
				$allCategories = $moduleClient->getAllCategories($resource->name);
				foreach ($allCategories['contents'] as $category) {
					$categoryIds[] = $category->id;
					$globalCategories[$category->id] = QaCategoryUtil::buildCategory($category);
				}

				$result['contents']['resources'][] = QaResourceUtil::buildResource($resource, array(), $categoryIds);
			}

			usort($result['contents']['resources'], array('QaResourceUtil', 'sortByNameAsc'));
			$result['contents']['categories'] = $globalCategories;
		} catch (Exception $e) {
			$result['status'] = 'ERROR';
			$result['message'] = $e->getMessage();
		}

		// edit
		$glossaryDictionaryList = array();
		if ($result && $result['status'] == 'OK') {
			foreach ($result['contents'] as $resources) {
				foreach ($resources as $resource) {

					//if ($glossary -> name && $glossary -> languages) {
					array_push($glossaryDictionaryList, Glossary::createWithParams($resource));
					//}

				}
			}
		}

		//echo json_encode($result);
		return $glossaryDictionaryList;
	}

	/**
	 * Returns all of glossary names with
	 * current dictionary settings.
	 * @return array contains names of glossaries
	 */
	static public function findGlossaryNames() {
		$userDictCtrl = new UserDictionaryClass();
		$userDicts = $userDictCtrl -> getGlossaryDictionarys();

		$glossaryDictionaries = array();
		foreach ($userDicts as $userDict) {
			$name = @$userDict['name'];
			if ($name) {
				$glossaryDictionaries[] = $name;
			}
		}

		return $glossaryDictionaries;
	}
	
	/**
	 * Inser selected glossaries into associate_glossaries table.
	 *
	 */
	static public function deleteInsertGlossaryDictionaries($resourceName, $dictionaryNames) {
		$xoopsDB =& Database::getInstance();
		$insResult = 0;
		$result = GlossaryList::deleteAssociateGlossaries($xoopsDB, $resourceName);
		if ($result) {
			foreach ($dictionaryNames as $dictionaryName) {
				$insResult += GlossaryList::insertAssociateGlossaries($xoopsDB, $resourceName, $dictionaryName);
			}
		}
		return $insResult;
	}

	/**
	 * Returns selected glossaris for storefront(reception).
	 * @return array of glossary name
	 */
	static public function findSelectedDefaultGlossaryDictionaries($resourceName) {
		
		$resrcClient =& new ResourceClient();
		$contents = $resrcClient -> getAllLanguageResources('GLOSSARY');

		$masterGlossaries   = array();
		$selectedGlossaries = array();
		
		if ($contents['contents']) {
			foreach ($contents['contents'] as $object) {
				if ($object -> name) {
					$masterGlossaries[] = $object -> name;
				}
			}
		}

		$xoopsDB =& Database::getInstance();
		$records = GlossaryList::selectSelectedGlossaries($xoopsDB, $resourceName);
		
		foreach($records as $record) {
		
			// find selected glossaries
			if (in_array($record['dictionary_name'], $masterGlossaries)) {
				$selectedGlossaries[] = $record['dictionary_name'];
			}
		}		
		return $selectedGlossaries;
	}
	
	/**
	 * Select selected glossaries associated 
	 * with storefront(Reception). 
	 * @param object $xoopsDB
	 * @return array of object
	 *
	 */
	static public function selectSelectedGlossaries($xoopsDB, $resourceName) {
		$sql  = " SELECT ";
		$sql .= "   * ";
		$sql .= " FROM ";
		$sql .=     self::actualTableName("associate_glossaries");
		
		$sql .= " WHERE ";
		$sql .= "   resource_name = '%s' ";

		$selectSql = sprintf($sql, mysql_real_escape_string($resourceName));

		$resultRecords = array();
		if($resultSet = $xoopsDB -> query($selectSql)) {
			while($record = $xoopsDB -> fetchArray($resultSet)) {
				$resultRecords[] = $record;
			}
		}
		
		return $resultRecords;
	}
	
	/**
	 * Insert data into associate_glossaries table.
	 * @param object $xoopsDB
	 * @param string $resourceName
	 * @param string $dictionaryName
	 * @return boolean
	 */
	static public function insertAssociateGlossaries($xoopsDB, $resourceName, $dictionaryName) {
		$sql  = " INSERT INTO ";
		$sql .=        self::actualTableName("associate_glossaries ");
		$sql .= "      (resource_name, dictionary_name, create_date) ";
		$sql .= " VALUES ";
		$sql .= "       ('%s', '%s', '%d')";

		$insertSql = sprintf($sql, 
							mysql_real_escape_string($resourceName),
							mysql_real_escape_string($dictionaryName),
							time());

		$result = $xoopsDB -> queryF($insertSql);				
		return $result;
	}
	
	/**
	 * Delete glossaries records from associate_glossaries table.
	 * @param object $xoopsDB
	 * @param string $resourceName
	 * @return boolean
	 */
	static public function deleteAssociateGlossaries($xoopsDB, $resourceName) {
		$sql  = " DELETE FROM ";
		$sql .=         self::actualTableName("associate_glossaries") ;
		$sql .= " WHERE ";
		$sql .= "       resource_name = '%s' ";

		$deleteSql = sprintf($sql, mysql_real_escape_string($resourceName));
		
		$result = $xoopsDB -> queryF($deleteSql);
		return $result;
	}
	
	/**
	 * Returns prefixed table name.
	 * @param $xoopsDB
	 * @param string $tableName
	 * @return string
	 */
	static public function actualTableName($tableName) {
		$xoopsDB =& Database::getInstance();
		return $xoopsDB->prefix($GLOBALS['mydirname'] . '_' . $tableName);
	}
}

class Glossary {

	private $glossary;

	protected function __construct($resource) {
		$this -> glossary = $resource;
	}

	static public function createWithParams($resource) {
		return new Glossary($resource);
	}

	/**
	 * Returns glossary dictionary name.
	 * @return string
	 */
	public function getName() {
		if (isset($this -> glossary['name'])) {
			return $this -> glossary['name'];
		}
		return null;
	}

	/**
	 * Returns categoryIds.
	 * @return array of categoryId
	 */
	public function getCategoryIds() {
		if (isset($this -> glossary['categoryIds'])) {
			return $this -> glossary['categoryIds'];
		}
		return null;
	}

	/**
	 * Returns language code.
	 * @return array of language code
	 */
	public function getLanguages() {
		if (isset($this -> glossary['languages'])) {
			return $this -> glossary['languages'];
		}
		return null;
	}

	/**
	 * Returns permission read permission/edit permission
	 * @return array
	 */
	public function getPermission() {
		if (isset($this -> glossary['permission'])) {
			return $this -> glossary['permission'];
		}
		return null;
	}

	/**
	 * Returns creator
	 * @return array
	 */
	public function getCreator() {
		if (isset($this -> glossary['creator'])) {
			return $this -> glossary['creator'];
		}
		return null;
	}
}

class GlossaryDictionary {

	private $glossaryDictionary;

	protected function __construct($resource) {
		$this -> glossaryDictionary = $resource;
	}

	static public function createWithParams($resource) {
		return new glossaryDictionary($resource);
	}

	/**
	 * Returns user dictionary id.
	 * @return int
	 */
	public function getId() {
		if (isset($this -> glossaryDictionary['id'])) {
			return $this -> glossaryDictionary['id'];
		}
		return null;
	}

	/**
	 * Returns user id.
	 * @return int
	 */
	public function getUserId() {
		if (isset($this -> glossaryDictionary['userId'])) {
			return $this -> glossaryDictionary['userId'];
		}
		return null;
	}

	/**
	 * Returns user id.
	 * @return int
	 */
	public function getTypeId() {
		if (isset($this -> glossaryDictionary['typeId'])) {
			return $this -> glossaryDictionary['typeId'];
		}
		return null;
	}

	/**
	 * Returns glossaryDictionary dictionary name.
	 * @return string
	 */
	public function getName() {
		if (isset($this -> glossaryDictionary['name'])) {
			return $this -> glossaryDictionary['name'];
		}
		return null;
	}

	/*
	 * Returns language code.
	 * @return array of language code
	 */
	public function getSupportLanguages() {
		if (isset($this -> glossaryDictionary['supportedLanguages'])) {
			return $this -> glossaryDictionary['supportedLanguages'];
		}
		return null;
	}

	/**
	 * Returns create date (int value).
	 * @return int
	 */
	public function getCreateDate() {
		if (isset($this -> glossaryDictionary['createDate'])) {
			return $this -> glossaryDictionary['createDate'];
		}
		return null;
	}

	/**
	 * Returns update date (int value).
	 * @return int
	 */
	public function getUpdateDate() {
		if (isset($this -> glossaryDictionary['updateDate'])) {
			return $this -> glossaryDictionary['updateDate'];
		}
		return null;
	}





	/**
	 * Returns format create date (string value).
	 * @return string
	 */
	public function getCreateDateFormat() {
		if (isset($this -> glossaryDictionary['createDateFormat'])) {
			return $this -> glossaryDictionary['createDateFormat'];
		}
		return null;
	}

	/**
	 * Returns format update date (string value).
	 * @return string
	 */
	public function getUpdateDateFormat() {
		if (isset($this -> glossaryDictionary['updateDateFormat'])) {
			return $this -> glossaryDictionary['updateDateFormat'];
		}
		return null;
	}
}

?>