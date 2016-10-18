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
require_once dirname(__FILE__).'/post.php';

class PostManager extends AbstractManager {

	public function __construct($lang) {
		parent::__construct($lang);
	}

	public function getPostsByTopicId($topicId) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');

		$groupId = $this->root->mContext->mXoopsUser->getGroups();

		$sql  = '';
		$sql .= ' SELECT ';
		$sql .= '   P.post_id, ';
		$sql .= '   P.topic_id, ';
		$sql .= '   PB.description, ';
		$sql .= '   P.post_time, ';
		$sql .= '   P.post_original_language, ';
		$sql .= '   P.uid, ';
		$sql .= '   P.post_order, ';
		$sql .= '   P.delete_flag, ';
		$sql .= '   P.update_date, ';
		$sql .= '   P2.post_order as reply_number ';
		$sql .= ' FROM '.$postsTable.' AS P ';
		$sql .= ' LEFT JOIN '.$postsBodyTable.' AS PB USING (post_id) ';
		$sql .= ' LEFT JOIN '.$postsTable.' AS P2 ';
		$sql .= '   ON P.`reply_post_id` = P2.`post_id` ';
		$sql .= ' WHERE P.`topic_id` = '.intval($topicId).' ';
		$sql .= '   AND PB.`language_code` = \''.$this->selectedLanguage.'\' ';
		$sql .= ' ORDER BY P.post_order ASC ';

		$result = $this->db->query($sql);

		$posts = array();
		while ($row = $this->db->fetchArray($result)) {
			$posts[] = new Post($row);
		}
		return $posts;
	}
}
