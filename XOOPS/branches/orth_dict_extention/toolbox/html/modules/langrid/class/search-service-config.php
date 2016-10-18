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

class SearchServiceConfig {

 	function __construct() {

 	}

 	function findAllTranslationConfig() {
 		global $xoopsDB;

 		$return = array();
 		$sql = 'SELECT * FROM '.$xoopsDB->prefix('translation_config').' WHERE user_id = \'1\' ORDER BY source_lang, target_lang;';

 		if ($rs = $xoopsDB->query($sql)) {
 			for ($i = 0; $i < $xoopsDB->getRowsNum($rs); $i++) {
 				$return[$i] = $xoopsDB->fetchArray($rs);
 			}
 		}

 		return $return;
 	}

 	function getTranslationServiceIdByLanguagePair($userId, $sourceLang, $targetLang) {
 		global $xoopsDB;

 		$sql = 'SELECT translation_service_id FROM '.$xoopsDB->prefix('translation_config').' WHERE source_lang = \'%s\' AND target_lang = \'%s\' AND (user_id IN (\'%s\', \'%s\')) ORDER BY user_id DESC;';
 		$sql = sprintf($sql, mysql_real_escape_string($sourceLang)
 							,mysql_real_escape_string($targetLang)
 							,mysql_real_escape_string($userId)
 							,mysql_real_escape_string('1'));
 		$serviceId = '';
 		if ($rs = $xoopsDB->query($sql)) {
 			if ($xoopsDB->getRowsNum($rs) > 0) {
	 			$row = $xoopsDB->fetchArray($rs);
 				$serviceId = $row['translation_service_id'];
 			}
 		}

 		if ($serviceId == '') {
 			die('service is not found.');
 		}
 		return $serviceId;
 	}

 	function getDictServiceIdByLanguagePair($userId, $sourceLang, $targetLang) {
 		global $xoopsDB;

 		$sql = 'SELECT dict_service_id_1, dict_service_id_2, dict_service_id_3, dict_service_id_4, dict_service_id_5 FROM '.$xoopsDB->prefix('translation_config').' WHERE source_lang = \'%s\' AND target_lang = \'%s\' AND (user_id IN (\'%s\', \'%s\')) ORDER BY user_id DESC;';
 		$sql = sprintf($sql, mysql_real_escape_string($sourceLang)
 							,mysql_real_escape_string($targetLang)
 							,mysql_real_escape_string($userId)
 							,mysql_real_escape_string('1'));
 		$serviceId = array();
 		if ($rs = $xoopsDB->query($sql)) {
 			if ($xoopsDB->getRowsNum($rs) > 0) {
	 			$row = $xoopsDB->fetchArray($rs);
	 			if ($row['dict_service_id_1'] != '') {
	 				$serviceId[] = $row['dict_service_id_1'];
	 			}
	 			if ($row['dict_service_id_2'] != '') {
	 				$serviceId[] = $row['dict_service_id_2'];
	 			}
	 			if ($row['dict_service_id_3'] != '') {
	 				$serviceId[] = $row['dict_service_id_3'];
	 			}
	 			if ($row['dict_service_id_4'] != '') {
	 				$serviceId[] = $row['dict_service_id_4'];
	 			}
	 			if ($row['dict_service_id_5'] != '') {
	 				$serviceId[] = $row['dict_service_id_5'];
	 			}
 			}
 		}

 		if ($serviceId == '') {
 			die('dictionary service is not found.');
 		}
 		return $serviceId;
 	}

 }
?>
