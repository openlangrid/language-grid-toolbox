<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
require_once(dirname(__FILE__).'/../Abstract_WebQABackendAction.class.php');
require_once XOOPS_ROOT_PATH.'/modules/langrid/include/Functions.php';

class PostLanguageAction {
	
	static $table = 'webqa_post_language';
	
	public static function registerLanguage($lang, $questionId, $answerId = 0) {
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		
		$table = $db->prefix(self::$table);
		
		$sql = "INSERT INTO %s (`question_id`, `answer_id`, `language`) VALUES('%d', '%d', '%s')";
		$sql = sprintf($sql, $table, $questionId, $answerId, $lang);
		
		$result = $db->queryF($sql); 
		
		// log_info($sql);
		
		return;
	}

	public static function getLanguage($questionId, $answerId = 0) {
		$root =& XCube_Root::getSingleton();
		$db = $root->mController->mDB;
		
		$table = $db->prefix(self::$table);
		
		$sql = "SELECT `language` FROM %s WHERE `question_id` = %d AND `answer_id` = %d"; 
		$sql = sprintf($sql, $table, $questionId, $answerId);
		
		$result = $db->queryF($sql);
		$lang = '';
		//log_info($result);
		if ($row = $db->fetchArray($result)) {
			//log_info($row);
			$lang = getLanguageName($row['language']);
		}
		
		return $lang;
	}
}
?>