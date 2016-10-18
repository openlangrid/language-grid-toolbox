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
require_once dirname(__FILE__).'/abstract-manager.php';
require_once dirname(__FILE__).'/../model/forum.php';
require_once dirname(__FILE__).'/topic-manager.php';

class ForumManager extends AbstractManager {

	private $forums = array();
	private $CurPage;
	private $ParPage;
	private $SortKey;

	public function __construct() {
		parent::__construct();

		$this->CurPage = 0;
		$this->ParPage = 0;
		$this->SortKey = 0;
	}


	/**
	 * @return Array
	 */
	public function getForums() {
		if (!$this->forums) {
			$forumsTable = $this->db->prefix($this->moduleName.'_forums');
			$forumsBodyTable = $this->db->prefix($this->moduleName.'_forums_body');
			$topicsTable = $this->db->prefix($this->moduleName.'_topics');
			$postsTable = $this->db->prefix($this->moduleName.'_posts');
			$usersTable = $this->db->prefix('users');

			$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

			$sql  = '';
			$sql .= ' SELECT  ';
			$sql .= '  T1.cat_id, ';
			$sql .= '  T1.forum_id, ';
			$sql .= '  T2.title, ';
			$sql .= '  T2.description, ';
			$sql .= '  T1.forum_original_language, ';
			//$sql .= '  T1.forum_last_post_time, ';
			$sql .= '  COALESCE(T3.topic_count,0) AS forum_topics_count, ';
			$sql .= '  COALESCE(T3.post_count,0) AS forum_posts_count , ';
			$sql .= '  T4.uid, ';
			$sql .= '  T5.uname,  ';
			$sql .= '  T4.post_time AS forum_last_post_time ';
			$sql .= ' FROM '.$forumsTable.' T1  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '   SELECT  ';
			$sql .= '    forum_id, ';
			$sql .= '    REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title, ';
			$sql .= '    REPLACE(REPLACE(description, CHAR(13),\'\'),CHAR(10),\' \') AS description  ';
			$sql .= '   FROM '.$forumsBodyTable.'  ';
			$sql .= '   WHERE `language_code` = \''.$this->languageManager->getSelectedLanguage().'\' ';
			$sql .= ' ) AS T2 USING(forum_id)  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '     SELECT  ';
			$sql .= '      t.forum_id, ';
			$sql .= '      COUNT(t.topic_id) AS topic_count , ';
			$sql .= '      SUM(p.pcnt) AS post_count , ';
			$sql .= '      MAX(p.max_pid) AS max_pid  ';
			$sql .= '     FROM '.$topicsTable.' AS t  ';
			$sql .= '     LEFT JOIN ( ';
			$sql .= '       SELECT ';
			$sql .= '        topic_id, ';
			$sql .= '        COUNT(post_id) AS pcnt, ';
			$sql .= '        MAX(post_id) AS max_pid  ';
			$sql .= '       FROM '.$postsTable.' ';
			$sql .= '       WHERE delete_flag = 0   ';
			$sql .= '       GROUP BY topic_id  ';
			$sql .= '     ) AS p USING(topic_id)  ';
			$sql .= '     WHERE t.delete_flag = 0  ';
			$sql .= '     GROUP BY t.forum_id ';
			$sql .= ' ) AS T3 USING(forum_id)  ';
			$sql .= ' LEFT JOIN ('.$postsTable.' AS T4  ';
			$sql .= '   LEFT JOIN( ';
			$sql .= '     SELECT  ';
			$sql .= '       uid, ';
			$sql .= '       CASE WHEN COALESCE(`name`, \'\') = \'\' THEN `uname` ELSE `name` END `uname`  ';
			$sql .= '     FROM '.$usersTable.'  ';
			$sql .= '   ) AS T5 USING(uid) ';
			$sql .= ' ) ';
			$sql .= ' ON T4.post_id = T3.max_pid  ';
			$sql .= ' WHERE T1.forum_id IN ('.implode(',', $this->getAllowedForumIds()).') ';

			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)) {
				$this->forums[] = new Forum($row);
			}
		}
		return $this->forums;
	}

	/**
	 * @return Array
	 */
	public function getForumsByCatId($cat_id) {
		if (!$this->forums) {
			$forumsTable = $this->db->prefix($this->moduleName.'_forums');
			$forumsBodyTable = $this->db->prefix($this->moduleName.'_forums_body');
			$topicsTable = $this->db->prefix($this->moduleName.'_topics');
			$postsTable = $this->db->prefix($this->moduleName.'_posts');
			$usersTable = $this->db->prefix('users');

			$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

			$OrderSQL = "";
			$sortkey = intval($this->SortKey);
			if (!$sortkey) $sortkey = 0;
			if($sortkey > 0 && $sortkey < 11){
				$OrderSQL = ' ORDER BY ';
				switch($sortkey){
					case  1:
					case  2:$OrderSQL.='T2.title';break;
					case  3:
					case  4:$OrderSQL.='T2.description';break;
					case  5:
					case  6:$OrderSQL.='COALESCE(T3.topic_count,0)';break;
					case  7:
					case  8:$OrderSQL.='COALESCE(T3.post_count,0)';break;
					case  9:
					case 10:$OrderSQL.='T4.post_time';break;
					default:$OrderSQL.='T1.forum_id';break;
				}
				if($sortkey % 2 == 0){
					$OrderSQL .= ' ASC ';
				}else{
					$OrderSQL .= ' DESC ';
				}
			}

			$page = intval($this->CurPage);
			if (!$page) $page = 0;

			$view = intval($this->ParPage);
			if (!$view) $view = 0;

			$sql  = '';
			$sql .= ' SELECT  ';
			$sql .= '  T1.cat_id, ';
			$sql .= '  T1.forum_id, ';
			$sql .= '  T2.title, ';
			$sql .= '  T2.description, ';
			$sql .= '  T1.forum_original_language, ';
			//$sql .= '  T1.forum_last_post_time, ';
			$sql .= '  COALESCE(T3.topic_count,0) AS forum_topics_count, ';
			$sql .= '  COALESCE(T3.post_count,0) AS forum_posts_count , ';
			$sql .= '  T4.uid, ';
			$sql .= '  T5.uname,  ';
			$sql .= '  T4.post_time AS forum_last_post_time ';
			$sql .= ' FROM '.$forumsTable.' T1  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '   SELECT  ';
			$sql .= '    forum_id, ';
			$sql .= '    REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title, ';
			$sql .= '    REPLACE(REPLACE(description, CHAR(13),\'\'),CHAR(10),\' \') AS description  ';
			$sql .= '   FROM '.$forumsBodyTable.'  ';
			$sql .= '   WHERE `language_code` = \''.$this->languageManager->getSelectedLanguage().'\' ';
			$sql .= ' ) AS T2 USING(forum_id)  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '     SELECT  ';
			$sql .= '      t.forum_id, ';
			$sql .= '      COUNT(t.topic_id) AS topic_count , ';
			$sql .= '      SUM(p.pcnt) AS post_count , ';
			$sql .= '      MAX(p.max_pid) AS max_pid  ';
			$sql .= '     FROM '.$topicsTable.' AS t  ';
			$sql .= '     LEFT JOIN ( ';
			$sql .= '       SELECT ';
			$sql .= '        topic_id, ';
			$sql .= '        COUNT(post_id) AS pcnt, ';
			$sql .= '        MAX(post_id) AS max_pid  ';
			$sql .= '       FROM '.$postsTable.' ';
			$sql .= '       WHERE delete_flag = 0   ';
			$sql .= '       GROUP BY topic_id  ';
			$sql .= '     ) AS p USING(topic_id)  ';
			$sql .= '     WHERE t.delete_flag = 0  ';
			$sql .= '     GROUP BY t.forum_id ';
			$sql .= ' ) AS T3 USING(forum_id)  ';
			$sql .= ' LEFT JOIN ('.$postsTable.' AS T4  ';
			$sql .= '   LEFT JOIN( ';
			$sql .= '     SELECT  ';
			$sql .= '       uid, ';
			$sql .= '       CASE WHEN COALESCE(`name`, \'\') = \'\' THEN `uname` ELSE `name` END `uname`  ';
			$sql .= '     FROM '.$usersTable.'  ';
			$sql .= '   ) AS T5 USING(uid) ';
			$sql .= ' ) ';
			$sql .= ' ON T4.post_id = T3.max_pid  ';
			$sql .= ' WHERE T1.forum_id IN ('.implode(',', $this->getAllowedForumIds()).') ';
			$sql .= ' AND T1.cat_id = ? ';
			$sql .= $OrderSQL;
			if($page > 0 && $view > 0){
				$page--;
				$start = $page * $view;
				$sql .= ' LIMIT '.$view.' OFFSET '.$start.' ';
			}

			$this->db->prepare($sql);
			$this->db->bind_param('i', intval($cat_id));
			$result = $this->db->execute();
			//$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)) {
				$this->forums[] = new Forum($row);
			}
		}
		return $this->forums;
	}

	public function setCurPage($var){
		$this->CurPage = intval($var);
	}

	public function setParPage($var){
		$this->ParPage = intval($var);
	}

	public function setSortKey($var){
		$this->SortKey = intval($var);
	}

	public function getForumsCountByCatId($cat_id) {
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');

		$ret = 0;
		$sql  = '';
		$sql .= ' SELECT COUNT(forum_id) as forum_count ';
		$sql .= ' FROM '.$forumsTable.' ';
		$sql .= ' WHERE cat_id = ? ';
		$sql .= ' AND delete_flag = 0 ';

		$this->db->prepare($sql);
		$this->db->bind_param('i', intval($cat_id));
		$result = $this->db->execute();
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}else{
			if ($row = $this->db->fetchArray($result)) {
				$ret = $row['forum_count'];
			}
		}

		return $ret;
	}

	public function modifyForum($forumId, $title, $description, $language, $userId) {
		$table = $this->db->prefix($this->moduleName.'_forums');
		$bodyTable = $this->db->prefix($this->moduleName.'_forums_body');
		$language = $this->languageManager->getSelectedLanguage();
		$userId = $this->root->mContext->mXoopsUser->get('uid');

		$id = intval($forumId);

		$sql  = '';
		$sql .= ' SELECT forum_id FROM '.$table.' AS T ';
		$sql .= '   LEFT JOIN ';
		$sql .= '     ( SELECT forum_id,title, description FROM '.$bodyTable.' WHERE `language_code` = \'%s\') AS T1 ';
		$sql .= '   USING(forum_id) ';
		$sql .= '   WHERE `forum_id` = \'%d\' ';

		$sql = sprintf($sql, $language, $id);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		$row = $this->db->fetchArray($result);
		if (!$row['forum_id']) {
			return false;
		}
		if ($row['title'] == $title || $row['description'] == $this->escape($description)) {
			return false;
		}
		$sql  = '';
		$sql .= ' UPDATE %s SET ';
		$sql .= '     `title` = \'%s\', `description` = \'%s\' ';
		$sql .= ' WHERE `forum_id` = %d AND `language_code` = \'%s\'';

		$sql = sprintf($sql, $bodyTable, $this->escape($title)
				, $this->escape($description), $id,$language);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		} else {
			$this->setLog($id
							, EnumBBSItemTypeCode::$forumTitle
							, $language, EnumProcessTypeCode::$modify
							, $title);
			$this->setLog($id
							, EnumBBSItemTypeCode::$forumDescription
							, $language, EnumProcessTypeCode::$modify
							, $description);
		}
		return;
	}

	public function sortForumsByCategoryId() {
		if (!function_exists('sortByCategoryId')) {
			function sortByCategoryId($a, $b) {
				if ($a->getCategoryId() == $b->getCategoryId()) {
					return 0;
				} else if ($a->getCategoryId() > $b->getCategoryId()) {
					return 1;
				} else {
					return -1;
				}
			}
		}
		usort($this->forums, "sortByCategoryId");
	}
//	public function updateForum($params) {
//		$forumsBodyTable = $this->db->prefix($this->moduleName.'_forums_body');
//		$selectedLanguageTag = $this->languageManager->getSelectedLanguage();
//
//		$translation = new Translation();
//		foreach ($this->languageManager->getAllLanguages() as $languageTag) {
//
//			$sql = ' DELETE FROM '.$forumsBodyTable;
//			$sql .= ' WHERE forum_id = %d AND `language_code` = \'%s\'';
//
//			$sql = sprintf($sql, intval($params['forumId']), $languageTag);
//			$this->db->query($sql);
//
//			$sql  = '';
////			$sql .= ' UPDATE '.$forumsBodyTable.' SET ';
//			$sql .= ' INSERT INTO '.$forumsBodyTable.' SET ';
////			var_dump($translation);
//			if (!$params['title'][$languageTag] && array_search($languageTag, $this->languageManager->getToLanguages()) !== false) {
//				$sourceText = $params['title'][$selectedLanguageTag];
//				$translation->setTargetLanguage($languageTag);
//				$result = $translation->translate($sourceText);
//				$params['title'][$languageTag] = $result['contents'][$languageTag]['translation']['contents'];
//			}
//			$sql .= ' title=\'';
//			$sql .= $this->escape($params['title'][$languageTag]).'\'';
//			if (array_search($languageTag, $this->languageManager->getToLanguages()) !== false) {
//				$sourceText = $params['message'][$selectedLanguageTag];
//				$translation->setTargetLanguage($languageTag);
//				$result = $translation->translate($sourceText);
//				$params['message'][$languageTag] = $result['contents'][$languageTag]['translation']['contents'];
//			}
//			$sql .= ', description=\'';
//			$sql .= $this->escape($params['message'][$languageTag]).'\' ';
////			$sql .= ' WHERE forum_id = '.intval($params['forumId']).' AND `language_code` = \''.$languageTag.'\' ';
//			$sql .= ' ,forum_id = '.intval($params['forumId']).', `language_code` = \''.$languageTag.'\' ';
//			$result = $this->db->query($sql);
//			if (!$result) {
//				die(_MD_D3FORUM_ERR_SQL.__LINE__);
//			} else {
//				$this->setLog($params['forumId'], EnumBBSItemTypeCode::$forumTitle
//								, $languageTag, EnumProcessTypeCode::$edit
//								, $params['title'][$languageTag]);
//				$this->setLog($params['forumId']
//								, EnumBBSItemTypeCode::$forumDescription
//								, $languageTag, EnumProcessTypeCode::$edit
//								, $params['message'][$languageTag]);
//			}
//		}
//
////		$this->db->prepare($sql);
////		$this->db->bind_param('si', $selectedLanguageTag, $params['forumId']);
////		echo $this->db->mPrepareQuery;die();
//		return $params;
//	}

	public function getForum($forumId) {
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$forumsBodyTable = $this->db->prefix($this->moduleName.'_forums_body');
		$selectedLanguageTag = $this->languageManager->getSelectedLanguage();

		$sql  = '';
		$sql .= ' SELECT * FROM '.$forumsTable.' ';
		$sql .= ' LEFT JOIN (SELECT * FROM '.$forumsBodyTable.' WHERE `language_code` = \''.$selectedLanguageTag.'\') AS T1 USING (forum_id) ';
		$sql .= ' WHERE forum_id = '.intval($forumId).' AND delete_flag = 0 ';

//		$this->db->prepare($sql);
//		$this->db->bind_param('i', );
		$result = $this->db->query($sql);

		$forum = null;
		$category = array();
		if ($row = $this->db->fetchArray($result)) {
			$forum =  new Forum($row);
			$forum->setOriginalLanguage($row['forum_original_language']);
		}

		return $forum;
	}
	public function deleteForum($forumId) {
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$postsTable = $this->db->prefix($this->moduleName.'_posts');

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '         '.$forumsTable.' AS T1 ';
		$sql .= '   SET ';
		$sql .= '     T1.delete_flag = 1 ';
		$sql .= '   WHERE forum_id = %d ';
		$sql = sprintf($sql, $forumId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '         '.$forumsTable.' AS T2 ';
		$sql .= '           RIGHT JOIN ';
		$sql .= '             ( ';
		$sql .= '               '.$topicsTable.' AS T3 ';
		$sql .= '              ) ';
		$sql .= '           USING(forum_id) ';
		$sql .= '   SET ';
		$sql .= '     T2.delete_flag = 1, ';
		$sql .= '     T3.delete_flag = 1 ';
		$sql .= '   WHERE forum_id = %d ';
		$sql = sprintf($sql, $forumId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '         '.$forumsTable.' AS T2 ';
		$sql .= '           RIGHT JOIN ';
		$sql .= '             ( ';
		$sql .= '               '.$topicsTable.' AS T3 ';
		$sql .= '                 RIGHT JOIN '.$postsTable.' AS T4 ';
		$sql .= '                   USING(topic_id) ';
		$sql .= '              ) ';
		$sql .= '           USING(forum_id) ';
		$sql .= '   SET ';
		$sql .= '     T4.delete_flag = 1 ';
		$sql .= '   WHERE forum_id = %d ';

		$sql = sprintf($sql, $forumId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
	}
}
?>