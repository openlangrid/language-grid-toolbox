<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/../model/category.php';
require_once dirname(__FILE__).'/../translation/translation.php';


class CategoryManager extends AbstractManager {

	private $categories = array();
	private $CurPage;
	private $ParPage;
	private $SortKey;
	private $gid_arr;
	
	public function __construct() {
		parent::__construct();
		$this->CurPage = 0;
		$this->ParPage = 0;
		$this->SortKey = 0;
		$this->gid_arr=is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
	}

	/**
	 * @return Array
	 */
	public function getCategories() {
		if ($this->categories) {
			
			return $this->categories;
		}
		
		if (!$this->categories) {
			$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
			$categoryBodyTable = $this->db->prefix($this->moduleName.'_categories_body');
			$forumsTable = $this->db->prefix($this->moduleName.'_forums');
			$topicsTable = $this->db->prefix($this->moduleName.'_topics');
			$postsTable = $this->db->prefix($this->moduleName.'_posts');
			$postAuthTable = $this->db->prefix($this->moduleName.'_post_auth');
			$topicAuthTable = $this->db->prefix($this->moduleName.'_topic_auth');
			$forumAuthTable = $this->db->prefix($this->moduleName.'_forum_auth');
			$categoryAuthTable = $this->db->prefix($this->moduleName.'_category_auth');
			$usersTable = $this->db->prefix('users');


//			$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

			$OrderSQL = "";
			$sortkey = intval($this->SortKey);
			if (!$sortkey) {
				$sortkey = 0;
			}
			$OrderSQL = ' ORDER BY ';
			switch($sortkey){
				case  1:
				case  2:$OrderSQL.='T2.title';break;
				case  3:
				case  4:$OrderSQL.='T2.description';break;
				case  5:
				case  6:$OrderSQL.='COALESCE(T3.forum_count,0)';break;
				case  7:
				case  8:$OrderSQL.='COALESCE(T3.topic_count,0)';break;
				case  9:
				case 10:$OrderSQL.='COALESCE(T3.post_count,0)';break;
				case 11:
				case 12:$OrderSQL.='T4.post_time';break;
				default:$OrderSQL.='T1.cat_id';break;
			}
			if($sortkey % 2 == 0){
				$OrderSQL .= ' ASC ';
			}else{
				$OrderSQL .= ' DESC ';
			}

			$page = intval($this->CurPage);
			if (!$page) $page = 0;

			$view = intval($this->ParPage);
			if (!$view) $view = 0;

			$sql  = '';
			$sql .= ' SELECT  ';
			$sql .= '  T1.cat_id, ';
			$sql .= '  T2.title, ';
			$sql .= '  T2.description, ';
			$sql .= '  T1.cat_original_language, ';
			//$sql .= '  T1.cat_last_post_time, ';
			$sql .= '  COALESCE(T3.forum_count,0) AS cat_forums_count, ';
			$sql .= '  COALESCE(T3.topic_count,0) AS cat_topics_count, ';
			$sql .= '  COALESCE(T3.post_count,0) AS cat_posts_count , ';
			$sql .= '  T1.uid, ';
			$sql .= '  T4.uid AS post_uid, ';
			$sql .= '  T5.uname AS post_uname,  ';
			$sql .= '  T4.post_time AS cat_last_post_time ';
			$sql .= ' FROM '.$categoriesTable.' T1  ';
			// if(!$this->isadmin){
				// $sql .= ' INNER JOIN ( ';
				// $sql .= '  SELECT DISTINCT ';
				// $sql .= '  cat_id ';
				// $sql .= '  FROM '.$categoryAuthTable.' ';
				// $sql .= '  WHERE groupid IN('.implode(',',$this->gid_arr).') ';
				// $sql .= ' ) AS ca USING(cat_id) ';
			// }
			$sql .= ' LEFT JOIN ( ';
			$sql .= '   SELECT  ';
			$sql .= '    cat_id, ';
			$sql .= '    REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title, ';
			$sql .= '    REPLACE(REPLACE(description, CHAR(13),\'\'),CHAR(10),\' \') AS description  ';
			$sql .= '   FROM '.$categoryBodyTable.'  ';
			$sql .= '   WHERE `language_code` = \''.$this->languageManager->getSelectedLanguage().'\' ';
			$sql .= ' ) AS T2 USING(cat_id)  ';
			$sql .= ' LEFT JOIN ( ';
			$sql .= '   SELECT  ';
			$sql .= '    f.cat_id, ';
			$sql .= '    COUNT(f.forum_id) AS forum_count , ';
			$sql .= '    SUM(t.tcnt) AS topic_count , ';
			$sql .= '    SUM(t.pcnt) AS post_count , ';
			$sql .= '    MAX(t.max_pid) AS max_pid  ';
			$sql .= '   FROM '.$forumsTable.' AS f  ';
			// if(!$this->isadmin){
				// $sql .= '	INNER JOIN ( ';
				// $sql .= '		SELECT DISTINCT ';
				// $sql .= '		 forum_id';
				// $sql .= '		FROM '.$forumAuthTable.' ';
				// $sql .= '		WHERE groupid IN('.implode(',',$this->gid_arr).') ';
				// $sql .= '	) AS ta USING(forum_id) ';
			// }
			$sql .= '   LEFT JOIN ( ';
			$sql .= '     SELECT  ';
			$sql .= '      t.forum_id, ';
			$sql .= '      COUNT(t.topic_id) AS tcnt , ';
			$sql .= '      SUM(p.pcnt) AS pcnt , ';
			$sql .= '      MAX(p.max_pid) AS max_pid  ';
			$sql .= '     FROM '.$topicsTable.' AS t  ';
/* 			if(!$this->isadmin){
				$sql .= '	  INNER JOIN ( ';
				$sql .= '		SELECT DISTINCT ';
				$sql .= '		 topic_id';
				$sql .= '		FROM '.$topicAuthTable.' ';
				$sql .= '		WHERE groupid IN('.implode(',',$this->gid_arr).') ';
				$sql .= '	  ) AS ta USING(topic_id) ';
			} */
			$sql .= '     LEFT JOIN ( ';
			$sql .= '       SELECT ';
			$sql .= '        topic_id, ';
			$sql .= '        COUNT(post_id) AS pcnt, ';
			$sql .= '        MAX(post_id) AS max_pid  ';
			$sql .= '       FROM '.$postsTable.' ';
/* 			if(!$this->isadmin){
				$sql .= '		INNER JOIN ( ';
				$sql .= '			SELECT DISTINCT ';
				$sql .= '			 post_id';
				$sql .= '			FROM '.$postAuthTable.' ';
				$sql .= '			WHERE groupid IN('.implode(',',$this->gid_arr).') ';
				$sql .= '	    ) AS pa USING(post_id) ';
			} */
			$sql .= '       WHERE post_id IN ('.implode(',',$this->getAllowedPostIds()).') ';
			$sql .= '       GROUP BY topic_id  ';
			$sql .= '     ) AS p USING(topic_id)  ';
			$sql .= '     WHERE t.topic_id IN ('.implode(',',$this->getAllowedTopicIds()).') ';
			$sql .= '     GROUP BY t.forum_id ';
			$sql .= '   ) AS t USING(forum_id)  ';
			$sql .= '   WHERE f.forum_id IN('.implode(',', $this->getAllowedForumIds()).') ';
			$sql .= '   GROUP BY f.cat_id ';
			$sql .= ' ) AS T3 USING(cat_id)  ';
			$sql .= ' LEFT JOIN '.$postsTable.' AS T4  ';
			$sql .= ' ON T4.post_id = T3.max_pid  ';
			$sql .= ' LEFT JOIN( ';
			$sql .= '   SELECT  ';
			$sql .= '     uid, ';
			$sql .= '     CASE WHEN COALESCE(`name`, \'\') = \'\' THEN `uname` ELSE `name` END `uname`  ';
			$sql .= '   FROM '.$usersTable.'  ';
			$sql .= ' ) AS T5 ON T4.uid = T5.uid  ';
			$sql .= ' WHERE T1.cat_id IN ('.implode(',', $this->getAllowedCategoryIds()).') ';
			$sql .= $OrderSQL;
			if($page > 0 && $view > 0){
				$page--;
				$start = $page * $view;
				$sql .= ' LIMIT '.$view.' OFFSET '.$start.' ';
			}	
			
			$result = $this->db->query($sql);

			

			while ($row = $this->db->fetchArray($result)) {			
				$category = new Category($row);
				$this->categories[] = $category;
			}
		}


		
		return $this->categories;
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

	/**
	 * @return Array
	 */
	public function getCategoriesCount(){
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$cateAuthTable = $this->db->prefix($this->moduleName.'_category_auth');
		$allowCategoryIds = $this->getAllowedCategoryIds();
		
		if (count($allowCategoryIds) == 0) {
			return 0;
		}

		$ret = 0;
		$sql  = '';
		$sql .= ' SELECT COUNT(cat_id) as cat_count ';
		$sql .= ' FROM '.$categoriesTable.' ';
		$sql .= ' WHERE cat_id IN ('.implode(',', $allowCategoryIds).') ';
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}else{
			if ($row = $this->db->fetchArray($result)) {
				$ret = $row['cat_count'];
			}
		}

		return $ret;
	}

	public function modifyCategory($categoryId, $title, $description, $language, $userId) {
		$table = $this->db->prefix($this->moduleName.'_categories');
		$bodyTable = $this->db->prefix($this->moduleName.'_categories_body');
		$language = $this->languageManager->getSelectedLanguage();
		$userId = $this->root->mContext->mXoopsUser->get('uid');

		$id = intval($categoryId);

		$sql  = '';
		$sql .= ' SELECT cat_id FROM '.$table.' AS T ';
		$sql .= '   LEFT JOIN ';
		$sql .= '     ( SELECT cat_id, title, description FROM '.$bodyTable.' WHERE `language_code` = \'%s\') AS T1 ';
		$sql .= '   USING(cat_id) ';
		$sql .= '   WHERE `cat_id` = \'%d\' ';

		$sql = sprintf($sql, $language, $id);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		$row = $this->db->fetchArray($result);
		if (!$row['cat_id']) {
			return false;
		}

		if ($row['title'] == $this->escape($title) && $row['description'] == $this->escape($description)) {
			return false;
		}

		$sql  = '';
		$sql .= ' UPDATE %s SET ';
		$sql .= '     `title` = \'%s\', `description` = \'%s\' ';
		$sql .= ' WHERE `cat_id` = %d AND `language_code` = \'%s\'';

		$sql = sprintf($sql, $bodyTable, $this->escape($title)
				, $this->escape($description), $id,$language);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		} else {
			$this->setLog($id
							, EnumBBSItemTypeCode::$categoryTitle
							, $language, EnumProcessTypeCode::$modify
							, $title);
			$this->setLog($id
							, EnumBBSItemTypeCode::$categoryDescription
							, $language, EnumProcessTypeCode::$modify
							, $description);
		}
		return;
	}

	public function getCategory($categoryId) {
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$categoriesBodyTable = $this->db->prefix($this->moduleName.'_categories_body');
		$selectedLanguageTag = $this->languageManager->getSelectedLanguage();

		
		$sql  = '';
		$sql .= ' SELECT * FROM '.$categoriesTable.'  ';
		$sql .= ' LEFT JOIN (SELECT * FROM '.$categoriesBodyTable;
		$sql .= ' WHERE `language_code` = \''.$selectedLanguageTag.'\') AS T1 USING (cat_id) ';
		$sql .= ' WHERE T1.cat_id = '.intval($categoryId).' AND delete_flag = 0 ';
		$result = $this->db->query($sql);
				
		if ($row = $this->db->fetchArray($result)) {
			$category =  new Category($row);
			$category->setOriginalLanguage($row['cat_original_language']);
			$auth = $this->getCategoryAuth($categoryId);
			$category->setAuth($auth);
		} else {
			$category =  new Category();
		}
		return $category;
	}
	public function deleteCategory($categoryId) {
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$postsTable = $this->db->prefix($this->moduleName.'_posts');

		$sql  = '';
		$sql .= ' UPDATE '.$categoriesTable.' SET delete_flag = 1 ';
		$sql .= ' WHERE cat_id = %d ';
		$sql = sprintf($sql, $categoryId);

		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$sql  = '';
		$sql .= ' UPDATE '.$categoriesTable.' AS T1';
		$sql .= ' RIGHT JOIN '.$forumsTable.' AS T2 USING(cat_id) ';
		$sql .= ' SET T2.delete_flag = 1 WHERE cat_id = %d ';
		$sql = sprintf($sql, $categoryId);

		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '   '.$categoriesTable.' AS T1 ';
		$sql .= '     RIGHT JOIN ';
		$sql .= '       ( ';
		$sql .= '         '.$forumsTable.' AS T2 ';
		$sql .= '           RIGHT JOIN ';
		$sql .= '               '.$topicsTable.' AS T3 ';
		$sql .= '           USING(forum_id) ';
		$sql .= '       ) ';
		$sql .= '     USING(cat_id) ';
		$sql .= '   SET ';
		$sql .= '     T3.delete_flag = 1 ';
		$sql .= '   WHERE cat_id = %d ';

		$sql = sprintf($sql, $categoryId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$sql  = '';
		$sql .= ' UPDATE ';
		$sql .= '   '.$categoriesTable.' AS T1 ';
		$sql .= '     RIGHT JOIN ';
		$sql .= '       ( ';
		$sql .= '         '.$forumsTable.' AS T2 ';
		$sql .= '           RIGHT JOIN ';
		$sql .= '             ( ';
		$sql .= '               '.$topicsTable.' AS T3 ';
		$sql .= '                 RIGHT JOIN '.$postsTable.' AS T4 ';
		$sql .= '                   USING(topic_id) ';
		$sql .= '              ) ';
		$sql .= '           USING(forum_id) ';
		$sql .= '       ) ';
		$sql .= '     USING(cat_id) ';
		$sql .= '   SET ';
		$sql .= '     T4.delete_flag = 1 ';
		$sql .= '   WHERE cat_id = %d ';

		$sql = sprintf($sql, $categoryId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		
		
	}
	
	public function createCategoryAuth($categoryId,$authGroup){
		$categoryAuthTable = $this->db->prefix($this->moduleName.'_category_auth');

		foreach($authGroup as $groupId){
			$sql  ='';
			$sql .=' INSERT IGNORE INTO ';
			$sql .=' '.$categoryAuthTable.' ';
			$sql .=' VALUES(%d, %d)';
			
			$sql = sprintf($sql, $categoryId, $groupId);
			$result = $this->db->query($sql);
			if (!$result) {
				die(_MD_D3FORUM_ERR_SQL.__LINE__);
			}
		}
	}
	public function deleteCategoryAuth($categoryId){
		$categoryAuthTable = $this->db->prefix($this->moduleName.'_category_auth');
		$sql  ='';
		$sql .=' DELETE FROM ';
		$sql .=' '.$categoryAuthTable.' ';
		$sql .=' WHERE cat_id = %d ';
		
		$sql = sprintf($sql, $categoryId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
	}
	public function modifyCategoryAuth($categoryId,$authGroup){
		$this->deleteCategoryAuth($categoryId);
		$this->createCategoryAuth($categoryId, $authGroup);
	}
	
	public function getCategoryAuth($categoryId){	
		if($this->getAllUserPermission($categoryId)==1){
			
			$auth= $this->getGroupIds();
			
		}else{
			$categoryAuthTable = $this->db->prefix($this->moduleName.'_category_auth');
			$sql  = '';
			$sql .= ' SELECT groupid FROM '.$categoryAuthTable.' ';
			$sql .= ' WHERE cat_id = '.intval($categoryId);		
			$result = $this->db->query($sql);
			
			$auth = array();
			while ($row = $this->db->fetchArray($result)) {
				$auth[]=$row['groupid'];
			}
		}
		return $auth;
	}
	
	public function getChildIds($id){
		$forumTable = $this->db->prefix($this->moduleName.'_forums');
		$sql  = '';
		$sql .= ' SELECT forum_id ';
		$sql .= ' FROM '.$forumTable.' ';
		$sql .= ' WHERE cat_id = '.intval($id).' and delete_flag = 0';
		$result = $this->db->query($sql);
		
		$ids = array();
		while($row = $this->db->fetchArray($result)) {
			$ids[] = $row['forum_id'];
		}
		
		return $ids;
	}
	
	public function insertCategoryUser($categoryId,$userId){
		$categoryTable = $this->db->prefix($this->moduleName.'_categories');
		$sql  = '';
		$sql .= ' UPDATE '.$categoryTable.' SET uid = %d WHERE cat_id = %d';
		

		$sql = sprintf($sql, $userId, $categoryId);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
	}
	public function SetAllUserPermission($id,$all=1){
		$categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');
		$sql ='';
		$sql.=' UPDATE '.$categoryAccessTable.' SET `all` = %d WHERE cat_id = %d';
		
		$sql = sprintf($sql,$all,$id);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

	}
	
	public function getAllUserPermission($id){
		$categoryAccessTable = $this->db->prefix($this->moduleName.'_category_access');
		$sql  ='';
		$sql .=' SELECT `all` FROM '.$categoryAccessTable;
		$sql .=' WHERE cat_id = %d';
		$sql = sprintf($sql,$id);
		$result = $this->db->query($sql);
		
		$all =0;
		if($row = $this->db->fetchArray($result)){
			$all = $row['all'];
		}
		return $all;
	}
}
?>