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

class UpdateTranslationLogKeys {

 	function __construct() {

 	}

 	function updateKeys($logId, $appName, $mtFlg = '1', $key01 = null, $key02 = null, $key03 = null, $key04 = null, $key05 = null, $note1 = null, $note2 = null) {
		die('Please do not use this program.');
		global $xoopsDB;

		$sql = 'UPDATE '.$xoopsDB->prefix('translation_logs').' SET mt_flg = \''.$mtFlg.'\'';
		$sql .= ', app_name = \''.$appName.'\'';

		if ($key01 != null) {
			$sql .= ', key01 = \''.$key01.'\'';
		}
		if ($key02 != null) {
			$sql .= ', key02 = \''.$key02.'\'';
		}
		if ($key03 != null) {
			$sql .= ', key03 = \''.$key03.'\'';
		}
		if ($key04 != null) {
			$sql .= ', key04 = \''.$key04.'\'';
		}
		if ($key05 != null) {
			$sql .= ', key05 = \''.$key05.'\'';
		}
		if ($note1 != null) {
			$sql .= ', note1 = \''.$note1.'\'';
		}
		if ($note2 != null) {
			$sql .= ', note2 = \''.$note2.'\'';
		}

		$sql .= ', edit_date = now() ';
		$sql .= ' WHERE id = \''.$logId.'\';';


		$xoopsDB->queryf($sql);
 	}
 }
?>
