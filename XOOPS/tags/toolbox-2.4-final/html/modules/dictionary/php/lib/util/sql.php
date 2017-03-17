<?php
require_once(dirname( __FILE__ ).'/db_util.php');

class SQL {
	
	static public function getSQLForSelectAllMaxRowEachUserDictionary() {
		$userDictionaryContents = DBtableName('user_dictionary_contents');
		
		return <<<SQL
			SELECT 
				COALESCE(MAX(row),0) AS count,
				user_dictionary_id,
				language
			FROM 
				{$userDictionaryContents}
			WHERE 
				delete_flag = 0          
			GROUP BY 
				user_dictionary_id
				,language 
SQL;
	}
	
	static public function getSQLForSelectAllUserDictionaries() {
		$userDictionaryTable = DBtableName('user_dictionary');
		$userDictionaryMaxRow = self::getSQLForSelectAllMaxRowEachUserDictionary();
		$usersTable = DBtableName('users');
		$userDictionaryPermissionTable = DBtableName('user_dictionary_permission');
		
		return <<<SQL
			SELECT
				T0.*
				,T1.count
				,T1.language
				,T2.uid
				,T2.uname
				,T3.permission_type
				,T3.view
				,T3.edit 
			FROM
				{$userDictionaryTable} T0
				LEFT JOIN
					({$userDictionaryMaxRow}) T1 USING (user_dictionary_id)
				LEFT JOIN
					{$usersTable} T2 ON T2.uid = user_id
				LEFT JOIN
					{$userDictionaryPermissionTable} T3 USING(user_dictionary_id)	
			WHERE 
				T0.delete_flag = 0
			ORDER BY 
				`type_id` ASC, 
				`dictionary_name`ASC, 
				`user_dictionary_id`ASC, 
				T1.language  ASC
SQL;

	}
	
	static public function getSQLForSelectDictionaryByDictionaryId() {
		$userDictionaryTable = DBtableName('user_dictionary');

		return <<<SQL
			SELECT
				 dictionary_name
				,type_id
				,deploy_flag
				,update_date
			FROM
				{$userDictionaryTable}
			WHERE
				`user_dictionary_id` = '%d'
				AND
				`delete_flag` = 0
SQL;
	}
	
	static public function getSQLForSelectDistinctAllLanguagesByDictionaryId() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		
		return <<<SQL
			SELECT DISTINCT
				language
			FROM
				{$userDictionaryContentsTable}
			WHERE
				user_dictionary_id = %d
			ORDER BY
				language asc
SQL;
	}
	
	static public function getSQLForMaxRowByDictionaryId() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		
		return <<<SQL
			SELECT 
				MAX(row) as row
			FROM
				{$userDictionaryContentsTable}
			WHERE
				user_dictionary_id = %d
SQL;
	}
	
	static public function getSQLForInsertContent() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		
		return <<<SQL
			INSERT INTO
				{$userDictionaryContentsTable}
				(user_dictionary_id, language, contents, row)
			VALUES
				(%d, '%s', '%s', %d)
SQL;
	}
	
	// user_dictionary テーブルの更新日付を更新するSQL
	static public function getSQLForUpdateDictionaryLastUpdate() {
		$userDictionaryTable = DBtableName('user_dictionary');
		
		return <<<SQL
			UPDATE
				{$userDictionaryTable}
			SET
				`update_date` = '%d'
			WHERE
				user_dictionary_id = '%d'
SQL;
	}
	
	// 縦に持っている言語別のcontentsを、row毎に横に展開して取得するSQL
	// $languages: 抽出する言語
	// $dictionaryId: dictionaryId
	static public function getSQLForSelectAllContentsByDictionaryId($languages, $dictionaryId) {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		$columnStr = implode(",", self::generateColumnsForLanguageAsSelectContents($languages));
		$conditionStr = self::serializeConditionsForContents(array(
			"delete_flag" => 0,
			"user_dictionary_id" => $dictionaryId
		));
		
		$sql = <<<SQL
			SELECT
				row
				,{$columnStr}
			FROM
				{$userDictionaryContentsTable}
			WHERE
				row != 0
				AND
				({$conditionStr})
			GROUP BY
				row
			ORDER BY
				row desc
SQL;
		return $sql;
	}
	
	// $targetLanguages: 抽出対象言語
	static public function getSQLForSearchContentsByKeywords($targetLanguages, $typeId, $srcLanguage, $keywords) {
		$userDictionaryTable = DBtableName('user_dictionary');
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		$columnStr = implode("\n				,", self::generateColumnsForLanguageAsSelectContents($targetLanguages));
		$conditions = array();
		foreach($keywords as $word) $conditions[] = "contents like '{$word}'";
		$conditionStr = implode(" AND ", $conditions);

		return <<<SQL
			SELECT
				user_dictionary_id
				,dictionary_name
				,row
				,{$columnStr}
			FROM
				{$userDictionaryTable} ud
				INNER JOIN {$userDictionaryContentsTable} udc USING(user_dictionary_id)
				INNER JOIN (SELECT
					user_dictionary_id
					,row
				FROM
					{$userDictionaryContentsTable}
				WHERE
					language = '{$srcLanguage}'
					AND
					({$conditionStr})
				) pre using(user_dictionary_id, row)
			WHERE
				ud.delete_flag = 0
				AND
				udc.delete_flag = 0
				AND
				ud.type_id = '{$typeId}'
			GROUP BY
				user_dictionary_id
				,row
SQL;
	}
	
	static public function generateColumnsForLanguageAsSelectContents($languages) {
		$results = array();
		foreach($languages as $l) {
			$l = mysqlEscape($l);
			$results[] = sprintf("MAX(CASE WHEN language = '%s' THEN contents ELSE '' END) `%s`", $l, $l);
		}
		return $results;
	}
	
	// $conditionsがArrayなら要素をAND結合し、Hashなら展開する
	static public function serializeConditionsForContents($conditions) {
		$results = array();
		foreach($conditions as $column => $value) {
			$results[] = "{$column} = '{$value}'";
		}
		return implode(" AND ", $results);
	}
	
	static public function getSQLForSelectDistinctAllLanguagesGroupByTypeId() {
		$userDictionaryTable = DBtableName('user_dictionary');
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		
		return <<<SQL
			SELECT DISTINCT
				type_id, language
			FROM
				{$userDictionaryTable}
				INNER JOIN {$userDictionaryContentsTable} USING(user_dictionary_id)
			GROUP BY
				type_id, language
			ORDER BY
				type_id, language
SQL;
	}
	
	static public function getSQLForCountContentsByKeywords($typeId, $language, $keywords) {
		$userDictionaryTable = DBtableName('user_dictionary');
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		$conditionStr = implode(" AND ", self::getConditionsForKeywords($keywords));
		$language = mysqlEscape($language);
		
		return <<<SQL
			SELECT
				COUNT(row) cnt
			FROM
				{$userDictionaryTable} ud
				INNER JOIN {$userDictionaryContentsTable} c USING(user_dictionary_id)
			WHERE
				ud.delete_flag = 0
				AND
				c.delete_flag = 0
				AND
				c.row != 0
				AND
				ud.type_id = '{$typeId}'
				AND
				c.language = '{$language}'
				AND
				({$conditionStr})
SQL;
	}
	
	static public function getConditionsForKeywords($keywords) {
		$results = array();
		foreach($keywords as $word) $results[] = "c.contents like '{$word}'";
		return $results;
	}
	
	static public function getSQLForSelectDistinctContentsGroupByDictionaryId() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		return <<<SQL
			SELECT DISTINCT
				row, user_dictionary_id
			FROM
				{$userDictionaryContentsTable}
			WHERE
				row != 0 AND delete_flag = 0
SQL;
	}
	
	static public function getSQLForCountAllContents($conditions = "") {
		$distinctRowsSQL = self::getSQLForSelectDistinctContentsGroupByDictionaryId();
		$where = $conditions ? "WHERE {$conditions} " : "";
		return <<<SQL
			SELECT
				count(rows.row) cnt
				,user_dictionary_id
			FROM
				({$distinctRowsSQL}) rows
			{$where}
			GROUP BY
				user_dictionary_id
SQL;
	}
	
	static public function getSQLForCountAllContentsByDictionaryId() {
		return self::getSQLForCountAllContents("user_dictionary_id = '%d'");
	}
	
	static public function getSQLForDeleteContentsByDictionaryIdAndRow() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		return <<<SQL
			DELETE FROM
				{$userDictionaryContentsTable}
			WHERE
				user_dictionary_id = '%d'
				AND
				row IN (%s)
SQL;
	}
	
	// user_dictionary_id毎にlanguageをカンマ区切りで連結した文字列で抽出する
	static public function getSQLForSelectGroupConcatLanguageContents() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		return <<<SQL
			SELECT
				user_dictionary_id
				,GROUP_CONCAT(distinct language) languages
			FROM
				{$userDictionaryContentsTable}
			GROUP BY
				user_dictionary_id
SQL;
	}

	static public function getSQLForSelectAllDictionariesByTypeId() {
		$userDictionaryTable = DBtableName('user_dictionary');
		$countContentsQuery = self::getSQLForCountAllContents();
		$groupConcatLanguageQuery = self::getSQLForSelectGroupConcatLanguageContents();
		$usersTable = DBtableName('users');
		$userDictionaryPermissionTable = DBtableName('user_dictionary_permission');
		
		return <<<SQL
			SELECT
				ud.user_dictionary_id id
				,ud.user_id userId
				,ud.type_id typeId
				,ud.dictionary_name name
				,deploy_flag								
				,create_date createDate
				,update_date updateDate
				
				,udp.permission_type
				,udp.view
				,udp.edit 

				,ifnull(cc.cnt, 0) count
				,gcl.languages
				
				,user.uid
				,user.uname userName
			FROM
				{$userDictionaryTable} ud
				LEFT JOIN {$userDictionaryPermissionTable} udp USING(user_dictionary_id)
				LEFT JOIN ({$countContentsQuery}) cc USING (user_dictionary_id)
				LEFT JOIN ({$groupConcatLanguageQuery}) gcl USING (user_dictionary_id)
				LEFT JOIN {$usersTable} user ON user.uid = ud.user_id
			WHERE 
				ud.delete_flag = 0
				AND
				ud.type_id = '%d'
			ORDER BY
				ud.dictionary_name ASC
SQL;
	}
	
	static public function getSQLForCountAllDictionariesByTypeId() {
		$userDictionaryTable = DBtableName('user_dictionary');
		return <<<SQL
			SELECT
				count(user_dictionary_id) cnt
			FROM
				{$userDictionaryTable}
			WHERE
				delete_flag = 0
				AND
				type_id = '%d'
SQL;
	}
	
	static public function getSQLForDeleteContentsByDictionaryIdAndLanguages() {
		$userDictionaryContentsTable = DBtableName('user_dictionary_contents');
		return <<<SQL
			DELETE FROM
				{$userDictionaryContentsTable}
			WHERE
				user_dictionary_id = '%d'
				AND
				language IN (%s)
SQL;
	}
}
?>