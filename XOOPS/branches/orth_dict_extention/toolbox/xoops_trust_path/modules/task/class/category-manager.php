<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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
require_once dirname(__FILE__).'/category.php';

class CategoryManager extends AbstractManager {

	private $categories = array();
	private $CurPage;
	private $ParPage;
	private $SortKey;

	public function __construct($lang) {
		parent::__construct($lang);

		$this->CurPage = 0;
		$this->ParPage = 0;
		$this->SortKey = 0;
	}

	/**
	 * @return Array
	 */
	public function getCategories() {
		if ($this->categories) {
			return $this->categories;
		}

		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$categoryBodyTable = $this->db->prefix($this->moduleName.'_categories_body');
		$forumsTable = $this->db->prefix($this->moduleName.'_forums');
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$usersTable = $this->db->prefix('users');

		$OrderSQL = "";
		$sortkey = intval($this->SortKey);
		if (!$sortkey) $sortkey = 0;
		if($sortkey > 0 && $sortkey < 13){
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
		}

		$sql  = '';
		$sql .= ' SELECT  ';
		$sql .= '  T1.cat_id, ';
		$sql .= '  T2.title, ';
		$sql .= '  T2.description, ';
		$sql .= '  T1.cat_original_language, ';
		$sql .= '  COALESCE(T3.forum_count,0) AS cat_forums_count, ';
		$sql .= '  COALESCE(T3.topic_count,0) AS cat_topics_count, ';
		$sql .= '  COALESCE(T3.post_count,0) AS cat_posts_count , ';
		$sql .= '  T4.uid, ';
		$sql .= '  T5.uname,  ';
		$sql .= '  T4.post_time AS cat_last_post_time ';
		$sql .= ' FROM '.$categoriesTable.' T1  ';
		$sql .= ' LEFT JOIN ( ';
		$sql .= '   SELECT  ';
		$sql .= '    cat_id, ';
		$sql .= '    REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title, ';
		$sql .= '    REPLACE(REPLACE(description, CHAR(13),\'\'),CHAR(10),\' \') AS description  ';
		$sql .= '   FROM '.$categoryBodyTable.'  ';
		$sql .= '   WHERE `language_code` = \''.$this->selectedLanguage.'\' ';
		$sql .= ' ) AS T2 USING(cat_id)  ';
		$sql .= ' LEFT JOIN ( ';
		$sql .= '   SELECT  ';
		$sql .= '    f.cat_id, ';
		$sql .= '    COUNT(f.forum_id) AS forum_count , ';
		$sql .= '    SUM(t.tcnt) AS topic_count , ';
		$sql .= '    SUM(t.pcnt) AS post_count , ';
		$sql .= '    MAX(t.max_pid) AS max_pid  ';
		$sql .= '   FROM '.$forumsTable.' AS f  ';
		$sql .= '   LEFT JOIN ( ';
		$sql .= '     SELECT  ';
		$sql .= '      t.forum_id, ';
		$sql .= '      COUNT(t.topic_id) AS tcnt , ';
		$sql .= '      SUM(p.pcnt) AS pcnt , ';
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
		$sql .= '   ) AS t USING(forum_id)  ';
		$sql .= '   WHERE f.delete_flag = 0  ';
		$sql .= '   GROUP BY f.cat_id ';
		$sql .= ' ) AS T3 USING(cat_id)  ';
		$sql .= ' LEFT JOIN '.$postsTable.' AS T4  ';
		$sql .= ' ON T4.post_id = T3.max_pid  ';
		$sql .= ' LEFT JOIN( ';
		$sql .= '   SELECT  ';
		$sql .= '     uid, ';
		$sql .= '     CASE WHEN COALESCE(`name`, \'\') = \'\' THEN `uname` ELSE `name` END `uname`  ';
		$sql .= '   FROM '.$usersTable.'  ';
		$sql .= ' ) AS T5 USING(uid)  ';
		$sql .= ' WHERE T1.cat_id IN ('.implode(',', $this->getAllowedCategoryIds()).') ';
		$sql .= $OrderSQL;

		$result = $this->db->query($sql);
		while ($row = $this->db->fetchArray($result)) {
			$this->categories[] = new Category($row);
		}

		return $this->categories;
	}

	public function setSortKey($var){
		$this->SortKey = intval($var);
	}

	public function getCategory($categoryId) {
		$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
		$categoriesBodyTable = $this->db->prefix($this->moduleName.'_categories_body');

		$sql  = '';
		$sql .= ' SELECT * FROM '.$categoriesTable.' ';
		$sql .= ' LEFT JOIN (SELECT * FROM '.$categoriesBodyTable;
		$sql .= ' WHERE `language_code` = \''.$this->selectedLanguage.'\') AS T1 USING (cat_id) ';
		$sql .= ' WHERE cat_id = '.intval($categoryId).' AND delete_flag = 0 ';

		$result = $this->db->query($sql);

		if ($row = $this->db->fetchArray($result)) {
			$category =  new Category($row);
			$category->setOriginalLanguage($row['cat_original_language']);
		} else {
			$category =  new Category();
		}

		return $category;
	}
}
