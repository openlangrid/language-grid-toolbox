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

class TranslationLogs {

 	function __construct() {

 	}

 	function translateLog($inParams, $outParams, $config) {
		global $xoopsDB;
		$sql = sprintf($this->createInsertQueryString(),
			$this->_mres($config['loginUserId']),
			$this->_mres($inParams['sourceLang']),
			$this->_mres($inParams['targetLang']),
			$this->_mres($inParams['serviceId']),
			$this->_mres($inParams['bindingString']),
			$this->_mres($inParams['source']),
			$this->_mres($outParams['contents']['targetText']['contents']),
			$this->_mres($config['appName']),
			$this->_mres($config['key01']),
			$this->_mres($config['key02']),
			$this->_mres($config['key03']),
			$this->_mres($config['key04']),
			$this->_mres($config['key05']),
			$this->_mres($config['mtFlg']),
			$this->_mres($config['note1']),
			$this->_mres($config['note2']),
			$this->_mres(print_r($outParams['message'], true))
			);
		$xoopsDB->queryf($sql);
		return mysql_insert_id();
 	}

	private function createInsertQueryString() {
		global $xoopsDB;
		$tableName = $xoopsDB->prefix('translation_logs');

		$sql = '';
		$sql .= 'INSERT INTO '.$tableName.' (';
		$sql .= 'user_id, source_lang, target_lang, service_id, soapBindings, ';
		$sql .= 'source, target, app_name, key01, key02, key03, key04, key05, mt_flg, note1, note2, output_print) ';
		$sql .= 'VALUES (';
		$sql .= '\'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\', \'%s\');';

		return $sql;
	}

	private function _mres($moji) {
		return mysql_real_escape_string($moji);
	}

 	function getTranslateLogs($request) {
		global $xoopsDB;

		$sql = 'SELECT * FROM '.$xoopsDB->prefix('translation_logs').' AS t';
		$sql .= ' LEFT JOIN '.$xoopsDB->prefix('users').' AS u ON u.uid = t.user_id ';

		$sql .= ' WHERE ';
		foreach ($request as $key => $value) {
			$sql .= ' `'.$key.'` = \''.$this->_mres($value).'\' AND ';
		}
		$sql = substr($sql, 0, -4);

		$sql .= ' ORDER BY create_date ASC ';
		$result = $xoopsDB->queryf($sql);
		$logs = array();
		while ($row = $xoopsDB->fetchArray($result)) {
			$logs[] = $row;
		}
		return $logs;
 	}
 }
?>
