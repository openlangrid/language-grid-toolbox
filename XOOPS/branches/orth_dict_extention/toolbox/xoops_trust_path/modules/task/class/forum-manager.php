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
require_once dirname(__FILE__).'/forum.php';
require_once dirname(__FILE__).'/topic-manager.php';

class ForumManager extends AbstractManager {

	private $forums = array();
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
			$sql .= '   WHERE `language_code` = \''.$this->selectedLanguage.'\' ';
			$sql .= ' ) AS T2 USING(forum_id) ';
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

			$sql  = '';
			$sql .= ' SELECT  ';
			$sql .= '  T1.cat_id, ';
			$sql .= '  T1.forum_id, ';
			$sql .= '  T2.title, ';
			$sql .= '  T2.description, ';
			$sql .= '  T1.forum_original_language, ';
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
			$sql .= '   WHERE `language_code` = \''.$this->selectedLanguage.'\' ';
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

			$this->db->prepare($sql);
			$this->db->bind_param('i', intval($cat_id));
			$result = $this->db->execute();
			while ($row = $this->db->fetchArray($result)) {
				$this->forums[] = new Forum($row);
			}
		}
		return $this->forums;
	}

	public function setSortKey($var){
		$this->SortKey = intval($var);
	}

	public function sortForumsByCategoryId() {
		usort($this->forums, array($this, "sortByCategoryId"));
	}

	public function sortByCategoryId($a, $b) {
		if ($a->getCategoryId() == $b->getCategoryId()) {
			return 0;
		} else if ($a->getCategoryId() > $b->getCategoryId()) {
			return 1;
		} else {
			return -1;
		}
	}
}
