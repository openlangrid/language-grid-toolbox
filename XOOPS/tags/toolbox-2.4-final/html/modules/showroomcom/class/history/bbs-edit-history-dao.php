<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
 *
 * @author kitajima
 *
 */
require_once(dirname(__FILE__).'/bbs-edit-history-model.php');

class BBSEditHistoryDAO {
	private $db;
	private $tableName;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->db = Database::getInstance();
		$this->tableName = $this->db->prefix(USE_TABLE_PREFIX.'_bbs_correct_edit_history');
	}
	public function getDBInstance(){
		return $this->db;
	}
	public function getModificationHistory($bbsId, $bbsItemTypeCode, $languageCode) {

		$sql  = '
				SELECT	`history_count`, `proc_type_cd`, `bbs_text`
						, `user_id`, `create_date`, `uname` AS `user_name`
				FROM	%s
				AS		T1
				LEFT JOIN
					(
						SELECT	uname, uid
						FROM	%s
					)
					AS	T2
					ON	T1.user_id = T2.uid
				WHERE	`bbs_id` = \'%d\'
				AND		`bbs_item_type_cd` = \'%s\'
				AND		`language_code` = \'%s\'
				AND		`delete_flag` = \'0\'
				AND		`proc_type_cd`
				IN		(\'%s\', \'%s\', \'%s\')
				ORDER BY	history_count ASC
		';

		$sql = sprintf(
					$sql, $this->tableName, $this->db->prefix('users'), intval($bbsId)
					, $this->escape($bbsItemTypeCode), $this->escape($languageCode)
					, $this->escape(EnumProcessTypeCode::$new)
					, $this->escape(EnumProcessTypeCode::$modify)
					, $this->escape(EnumProcessTypeCode::$edit)
				);

		$modificationHistory = array();
		$result = $this->db->query($sql);
		if ($result) {
			while ($row = $this->db->fetchArray($result)) {
				$bbsEditHistoryModel = new BBSEditHistoryModel();
				$bbsEditHistoryModel->setBBSId($bbsId);
				$bbsEditHistoryModel->setBBSItemTypeCode($bbsItemTypeCode);
				$bbsEditHistoryModel->setLanguageCode($languageCode);
				$bbsEditHistoryModel->setHistoryCount($row['history_count']);
				$bbsEditHistoryModel->setProcessTypeCode($row['proc_type_cd']);
				$bbsEditHistoryModel->setBBSText($row['bbs_text']);
				$bbsEditHistoryModel->setUserId($row['user_id']);
				$bbsEditHistoryModel->setUserName($row['user_name']);
				$bbsEditHistoryModel->setCreateDate($row['create_date']);
				$bbsEditHistoryModel->setDeleteFlag(0);
				$modificationHistory[] = $bbsEditHistoryModel;
			}
		}
		return $modificationHistory;
	}
	public function registerModificationHistory($bbsId, $bbsItemTypeCode, $languageCode,
								$processTypeCode, $bbsText, $userId) {
		$sql  = '';
		$sql .= ' INSERT INTO ';
		$sql .=       $this->tableName;
		$sql .= '    (`bbs_id`, `bbs_item_type_cd`, `language_code`, `history_count` ';
		$sql .= '     ,`proc_type_cd`, `bbs_text`, `user_id`, `create_date`, `delete_flag`) ';
		$sql .= '   SELECT ';
		$sql .= '      \'%d\', \'%s\', \'%s\', COALESCE(MAX(`history_count`), 0)+1, \'%s\' ';
		$sql .= '     , \'%s\', \'%s\', \'%d\', \'0\' ';
		$sql .= '   FROM ';
		$sql .= 		$this->tableName;
		$sql .= '   WHERE ';
		$sql .= '       `bbs_id` = \'%d\' ';
		$sql .= '   AND `bbs_item_type_cd` = \'%s\' ';
		$sql .= '   AND `language_code` = \'%s\' ';
		$sql .= '   AND `delete_flag` = \'0\' ';

		$sql = sprintf($sql, intval($bbsId), $this->escape($bbsItemTypeCode)
						, $this->escape($languageCode), $this->escape($processTypeCode)
						, $this->escape($bbsText), $this->escape($userId), time()
						, intval($bbsId), $this->escape($bbsItemTypeCode)
						, $this->escape($languageCode));
		$result = $this->db->queryf($sql);
		return (bool)$result;
	}

	private function escape($string) {
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}
		return mysql_real_escape_string($string);
	}
}
?>