<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
require_once dirname(__FILE__).'/../translation/translation.php';

class TitleManager extends AbstractManager {

	private $titles = array();

	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return Array
	 */
	public function getAllTitle() {
		if (!$this->titles) {
			$categoriesTable = $this->db->prefix($this->moduleName.'_categories');
			$categoriesBodyTable = $this->db->prefix($this->moduleName.'_categories_body');
			$forumsTable = $this->db->prefix($this->moduleName.'_forums');
			$forumsBodyTable = $this->db->prefix($this->moduleName.'_forums_body');
			$topicsTable = $this->db->prefix($this->moduleName.'_topics');
			$postsTable = $this->db->prefix($this->moduleName.'_posts');

			$sql  = '';
			$sql .= ' SELECT  ';
			$sql .= ' T1.cat_id, ';
			$sql .= ' T2.title as cat_title, ';
			$sql .= ' T3.forum_id, ';
			$sql .= ' T3.forum_title, ';
			$sql .= ' COALESCE(T3.topic_count,0) AS topics_count, ';
			$sql .= ' COALESCE(T3.post_count,0) AS posts_count , ';
			$sql .= ' T4.post_time AS last_post_time  ';
			$sql .= ' FROM '.$categoriesTable.' T1  ';
			$sql .= ' LEFT JOIN (  ';
			$sql .= '   SELECT cat_id, ';
			$sql .= '   REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title, ';
			$sql .= '   REPLACE(REPLACE(description, CHAR(13),\'\'),CHAR(10),\' \') AS description  ';
			$sql .= '   FROM '.$categoriesBodyTable.'  ';
			$sql .= '   WHERE `language_code` = \''.$this->languageManager->getSelectedLanguage().'\'  ';
			$sql .= ' ) AS T2 USING(cat_id)  ';
			$sql .= ' LEFT JOIN (  ';
			$sql .= '   SELECT  ';
			$sql .= '   f1.cat_id, ';
			$sql .= '   f1.forum_id , ';
			$sql .= '   REPLACE(REPLACE(f2.title, CHAR(13),\'\'),CHAR(10),\' \') AS forum_title , ';
			$sql .= '   SUM(N.tcnt) AS topic_count , ';
			$sql .= '   SUM(N.pcnt) AS post_count , ';
			$sql .= '   MAX(N.max_pid) AS max_pid  ';
			$sql .= '   FROM '.$forumsTable.' AS f1  ';
			$sql .= '   LEFT JOIN (  ';
			$sql .= '     SELECT forum_id,title  ';
			$sql .= '     FROM '.$forumsBodyTable.'  ';
			$sql .= '     WHERE `language_code` = \''.$this->languageManager->getSelectedLanguage().'\'  ';
			$sql .= '   ) AS f2 USING(forum_id)  ';
			$sql .= '   LEFT JOIN (  ';
			$sql .= '     SELECT  ';
			$sql .= '       t.forum_id, ';
			$sql .= '       COUNT(t.topic_id) AS tcnt , ';
			$sql .= '       SUM(p.pcnt) AS pcnt , ';
			$sql .= '       MAX(p.max_pid) AS max_pid  ';
			$sql .= '     FROM '.$topicsTable.' AS t  ';
			$sql .= '     LEFT JOIN (  ';
			$sql .= '       SELECT topic_id, ';
			$sql .= '       COUNT(post_id) AS pcnt, ';
			$sql .= '       MAX(post_id) AS max_pid  ';
			$sql .= '       FROM '.$postsTable.'  ';
			$sql .= '       WHERE delete_flag = 0  ';
			$sql .= '       GROUP BY topic_id  ';
			$sql .= '     ) AS p USING(topic_id)  ';
			$sql .= '     WHERE t.delete_flag = 0  ';
			$sql .= '     GROUP BY t.forum_id  ';
			$sql .= '   ) AS N USING(forum_id)  ';
			$sql .= '   WHERE f1.forum_id IN (';

			if (!count($this->getAllowedForumIds())) {
				$sql .= '0';
			} else {
				$sql .= implode(',', $this->getAllowedForumIds());
			}
			$sql .= ')  ';
			$sql .= '   GROUP BY  f1.cat_id,  f1.forum_id  ';
			$sql .= ' ) AS T3 USING(cat_id)  ';
			$sql .= ' LEFT JOIN '.$postsTable.' AS T4  ';
			$sql .= ' ON T4.post_id = T3.max_pid  ';
			$sql .= ' WHERE T1.cat_id IN ('.implode(',', $this->getAllowedCategoryIds()).')  ';

			$result = $this->db->query($sql);
			while ($row = $this->db->fetchArray($result)) {
				if(!isset($this->titles[$row['cat_id']]) || !is_array($this->titles[$row['cat_id']])){
					$this->titles[$row['cat_id']] = array();
				}
				$this->titles[$row['cat_id']]['cat_title'] = $row['cat_title'];
				if(!isset($this->titles[$row['cat_id']]['forums']) || !is_array($this->titles[$row['cat_id']]['forums'])){
					$this->titles[$row['cat_id']]['forums'] = array();
				}
				if($row['forum_id'] != "" && is_numeric($row['forum_id'])){
					$this->titles[$row['cat_id']]['forums'][$row['forum_id']] = array();
					$tmp = array();
					if (!isset($row['forum_title']) || $row['forum_title'] == "") {
						$tmp['title'] = _MD_D3FORUM_NO_TRANSLATION;
					} else {
						$tmp['title'] = $row['forum_title'];
					}
					$tmp['topic_cnt'] = $row['topics_count'];
					$tmp['post_cnt'] = $row['posts_count'];
					if((time() - $row['last_post_time']) < 24 * 60 * 60){
						$tmp['hasNewPost'] = true;
					}else{
						$tmp['hasNewPost'] = false;
					}
					$this->titles[$row['cat_id']]['forums'][$row['forum_id']] = $tmp;
				}
			}
		}
		return $this->titles;
	}
}
?>