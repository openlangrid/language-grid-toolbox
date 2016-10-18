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
require_once dirname(__FILE__).'/post-manager.php';
require_once dirname(__FILE__).'/topic.php';

class TopicManager extends AbstractManager {

	private $topics = array();
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
	public function getTopicsByForumId($forumId) {
		$topicsTable = $this->db->prefix($this->moduleName.'_topics');
		$topicsBodyTable = $this->db->prefix($this->moduleName.'_topics_body');
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$usersTable = $this->db->prefix('users');

		$groupId = implode(',', $this->root->mContext->mXoopsUser->getGroups());

		$OrderSQL = "";
		$sortkey = intval($this->SortKey);
		if (!$sortkey) $sortkey = 0;
		if($sortkey > 0 && $sortkey < 7){
			$OrderSQL = ' ORDER BY ';
			switch($sortkey){
				case  1:
				case  2:$OrderSQL.='T2.title';break;
				case  3:
				case  4:$OrderSQL.='COALESCE(T3.post_count,0)';break;
				case  5:
				case  6:$OrderSQL.='T4.post_time';break;
				default:$OrderSQL.='T1.topic_id';break;
			}
			if($sortkey % 2 == 0){
				$OrderSQL .= ' ASC ';
			}else{
				$OrderSQL .= ' DESC ';
			}
		}

		$sql  = '';
		$sql .= ' SELECT  ';
		$sql .= '  T1.forum_id, ';
		$sql .= '  T1.topic_id, ';
		$sql .= '  T2.title, ';
		$sql .= '  T1.topic_original_language, ';
		$sql .= '  COALESCE(T3.post_count,0) AS topic_posts_count , ';
		$sql .= '  T4.uid, ';
		$sql .= '  T5.uname,  ';
		$sql .= '  T4.post_time AS topic_last_post_time ';
		$sql .= ' FROM '.$topicsTable.' T1  ';
		$sql .= ' LEFT JOIN ( ';
		$sql .= '   SELECT  ';
		$sql .= '    topic_id, ';
		$sql .= '    REPLACE(REPLACE(title, CHAR(13),\'\'),CHAR(10),\' \') AS title  ';
		$sql .= '   FROM '.$topicsBodyTable.'  ';
		$sql .= '   WHERE `language_code` = \''.$this->selectedLanguage.'\' ';
		$sql .= ' ) AS T2 USING(topic_id)  ';
		$sql .= ' LEFT JOIN ( ';
		$sql .= '   SELECT ';
		$sql .= '   topic_id, ';
		$sql .= '   COUNT(post_id) AS post_count, ';
		$sql .= '   MAX(post_id) AS max_pid  ';
		$sql .= '   FROM '.$postsTable.' ';
		$sql .= '   WHERE delete_flag = 0   ';
		$sql .= '   GROUP BY topic_id  ';
		$sql .= ' ) AS T3 USING(topic_id)  ';
		$sql .= ' LEFT JOIN ('.$postsTable.' AS T4  ';
		$sql .= '   LEFT JOIN( ';
		$sql .= '     SELECT  ';
		$sql .= '       uid, ';
		$sql .= '       CASE WHEN COALESCE(`name`, \'\') = \'\' THEN `uname` ELSE `name` END `uname`  ';
		$sql .= '     FROM '.$usersTable.'  ';
		$sql .= '   ) AS T5 USING(uid) ';
		$sql .= ' ) ';
		$sql .= ' ON T4.post_id = T3.max_pid  ';
		$sql .= ' WHERE T1.forum_id = ? ';
		$sql .= ' AND T1.delete_flag = 0 ';
		$sql .= $OrderSQL;

		$this->db->prepare($sql);
		$this->db->bind_param('i', intval($forumId));
		$result = $this->db->execute();
		$this->topics = array();
		while ($row = $this->db->fetchArray($result)) {
			$topic = new Topic($row);
			$this->topics[] = $topic;
		}

		return $this->topics;
	}

	public function setSortKey($var){
		$this->SortKey = intval($var);
	}
}
