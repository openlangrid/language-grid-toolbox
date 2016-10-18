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
require_once dirname(__FILE__).'/../model/post.php';
require_once dirname(__FILE__).'/../translation/translation.php';
require_once XOOPS_ROOT_PATH.'/modules/langrid/php/client/translation-logs.php';

class PostManager extends AbstractManager {

	private $posts = array();

	public function __construct() {
		parent::__construct();
	}

//	public function createPost($params) {
//		$postsTable = $this->db->prefix($this->moduleName.'_posts');
//		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
//		$selectedLanguage = $this->languageManager->getSelectedLanguage();
//		$ip = $_SERVER["REMOTE_ADDR"];
//		$userId = $this->root->mContext->mXoopsUser->get('uid');
//		$topicId = $params['topicId'];
//
//		$sql  = '';
//		$sql .= ' INSERT INTO '.$postsTable.' ';
//		$sql .= '   (`post_time`, `topic_id`, `poster_ip`, `uid` ';
//		$sql .= '    , `post_original_language`, `post_order`) ';
//		$sql .= '   SELECT \'%d\', \'%d\', \'%s\', \'%d\', \'%s\'';
//		$sql .= '    ,COALESCE(MAX(post_order),0) + 1 FROM '.$postsTable.' ';
//		$sql .= '   WHERE topic_id = \'%d\' ';
//
////		$sql .= ' INSERT INTO '.$postsTable.' SET ';
////		$sql .= ' post_time = ?, topic_id = ?, poster_ip = ?,';
////		$sql .= ' uid = ?, post_original_language = ? ';
////		$sql .= ' , post_order =  ';
//
////		$this->db->prepare($sql);
////		$this->db->bind_param('iisis', time(), $params['topicId'], $ip,
////						$userId, $selectedLanguage);
//
//		$sql = sprintf($sql, time(), $topicId, $this->escape($ip)
//						, $userId, $this->escape($selectedLanguage), $topicId);
////		var_dump($sql);
//		$result = $this->db->query($sql);
//		if (!$result) {
//			die(_MD_D3FORUM_ERR_SQL.__LINE__);
//		} else {
//			$params['postId'] = intval($this->db->getInsertId());
//		}
//
//		$sourceText = $params['message'][$selectedLanguage];
//		$translation = new Translation();
//		foreach ($this->languageManager->getAllLanguages() as
//								 $languageTag) {
//			$sql  = '';
//			$sql .= ' INSERT INTO '.$postsBodyTable.' SET `language_code` = \''.$languageTag.'\', ';
//			$sql .= ' post_id = '.intval($params['postId']).' ';
//			if (($languageTag != $selectedLanguage) && array_search($languageTag, $this->languageManager->getToLanguages()) !== false) {
//
//				$config = array(
//					'loginUserId' => intval($this->root->mContext->mXoopsUser->get('uid')),
//					'appName' => 'Forum',
//					'key01' => 'postBody',
//					'key02' => 'postId',
//					'key03' => intval($params['postId']),
//					'key04' => 'post',
//					'key05' => 'translation',
//					'mtFlg' => '1'
//				);
//				$translation->setTargetLanguage($languageTag);
//				$result = $translation->translate($sourceText, $config);
//				$params['message'][$languageTag] = $result['contents'][$languageTag]['translation']['contents'];
//			}
//			$sql .= ' , description= \'';
//			$sql .= $this->escape($params['message'][$languageTag]).'\'';
////			echo $sql;die();
//			$result = $this->db->query($sql);
//			if (!$result) {
//				die(_MD_D3FORUM_ERR_SQL.__LINE__);
//			} else {
//				$this->setLog($params['postId'], EnumBBSItemTypeCode::$post
//				, $languageTag
//				, EnumProcessTypeCode::$new, $params['message'][$languageTag]);
//			}
//		}
//		return intval($params['postId']);
//	}
//	public function editPost($params) {
//		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
//
//		$sourceText = $params['message'][$this->languageManager->getSelectedLanguage()];
//		$translation = new Translation();
//		$postId = intval($params['postId']);
//		foreach ($this->languageManager->getAllLanguages() as
//								 $languageTag) {
//
//			$sql = ' DELETE FROM '.$postsBodyTable;
//			$sql .= ' WHERE post_id = %d AND `language_code` = \'%s\'';
//
//			$sql = sprintf($sql, intval($params['postId']), $languageTag);
////			echo $sql;
//			$this->db->query($sql);
//
//			$sql  = '';
////			$sql .= ' UPDATE '.$postsBodyTable.' SET ';
//			$sql .= ' INSERT INTO '.$postsBodyTable.' SET ';
////			if (!$params['message'][$languageTag] && array_search($languageTag, $this->languageManager->getToLanguages()) !== false) {
//			if (($languageTag != $selectedLanguage) && array_search($languageTag, $this->languageManager->getToLanguages()) !== false) {
//				$translation->setTargetLanguage($languageTag);
//				$result = $translation->translate($sourceText);
//				$params['message'][$languageTag] = $result['contents'][$languageTag]['translation']['contents'];
//			}
//			$sql .= ' description = \'';
//			$sql .= $this->escape($params['message'][$languageTag]).'\' ';
////			$sql .= ' WHERE post_id = '.$postId.' AND `language_code` = \''.$languageTag.'\' ';
//			$sql .= ' ,post_id = '.$postId.' , `language_code` = \''.$languageTag.'\' ';
//			$result = $this->db->query($sql);
//			if (!$result) {
//				die(_MD_D3FORUM_ERR_SQL.__LINE__);
//			} else {
//				$this->setLog($postId, EnumBBSItemTypeCode::$post
//							, $languageTag, EnumProcessTypeCode::$edit
//							, $this->escape($params['message'][$languageTag]));
//			}
//		}
//
//		$translationLogs = new TranslationLogs();
//		$config = array(
//			'loginUserId' => intval($this->root->mContext->mXoopsUser->get('uid')),
//			'appName' => 'Forum',
//			'key01' => 'postBody',
//			'key02' => 'postId',
//			'key03' => intval($params['postId']),
//			'key04' => 'modify',
//			'key05' => 'translation',
//			'note1' => $row['description'],
//			'mtFlg' => '0'
//		);
//
//		$sql  = '';
//		$sql .= ' SELECT * FROM '.$postsTable.' AS T ';
//		$sql .= '   LEFT JOIN ';
//		$sql .= '     ( SELECT * FROM '.$postsBodyTable.' WHERE language_code = %s) AS T1 ';
//		$sql .= '   USING(post_id) ';
//		$sql .= '   WHERE `post_id` = \'%d\' ';
//		$sql = sprintf($sql, $params['postId'], $row['post_original_language']);
//		$result = $this->db->query($sql);
//		$row2 = $this->db->fetchArray($result);
//
//		$inParams = array(
//			'sourceLang' => $row['post_original_language'],
//			'targetLang' => $selectedLanguage,
//			'serviceId' => '',
//			'bindingString' => '',
//			'source' => $row2['description']
//		);
//
//		$outParams['contents']['targetText']['contents'] = $message;
//		$translationLogs->translateLog($inParams, $outParams, $config);
//
//	//		$this->db->prepare($sql);
//	//		$this->db->bind_param('i', $postId);
//	//		echo $this->db->mPrepareQuery;die();
//		return $params;
//	}

	public function modifyPost($params) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
		$selectedLanguage = $this->languageManager->getSelectedLanguage();
		$userId = $this->root->mContext->mXoopsUser->get('uid');

		$sql  = '';
		$sql .= ' SELECT * FROM '.$postsTable.' AS T ';
		$sql .= '   LEFT JOIN ';
		$sql .= '     ( SELECT * FROM '.$postsBodyTable.' WHERE `language_code` = \'%s\') AS T1 ';
		$sql .= '   USING(post_id) ';
		$sql .= '   WHERE `post_id` = \'%d\' ';

//		$this->db->prepare($sql);
//		$this->db->bind_param('i', $params['postId']);
//		$result = $this->db->execute();
		$sql = sprintf($sql, $selectedLanguage, $params['postId']);
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}
		$row = $this->db->fetchArray($result);
		if (!$row['uid']) {
			return false;
		}

		$message = $params['message'][$selectedLanguage];
		if ($row['description'] == $message) {
			return false;
		}

		$sql  = '';
		$sql .= ' DELETE FROM %s ';
		$sql .= ' WHERE `post_id` = %d AND `language_code` = \''.$selectedLanguage.'\'';

		$sql = sprintf($sql, $postsBodyTable, intval($params['postId']));
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$time = time();

		$sql  = '';
		$sql .= ' INSERT INTO %s SET ';
		$sql .= ' description = \'%s\', ';
		$sql .= ' `post_id` = %d, `language_code` = \''.$selectedLanguage.'\', `update_time` = %d ';

		$sql = sprintf($sql, $postsBodyTable, $this->escape($message), intval($params['postId']), $time);
//		echo $sql;
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		} else {
			$this->setLog($params['postId'], EnumBBSItemTypeCode::$post
					, $selectedLanguage, EnumProcessTypeCode::$modify, $message);
		}

		$sql  = '';
		$sql .= ' UPDATE %s SET ';
		$sql .= ' update_date = \'%d\' ';
		$sql .= ' WHERE `post_id` = %d';

		$sql = sprintf($sql, $postsTable, $time, intval($params['postId']));
		$result = $this->db->query($sql);
		if (!$result) {
			die(_MD_D3FORUM_ERR_SQL.__LINE__);
		}

		$translationLogs = new TranslationLogs();

		$config = array(
			'loginUserId' => intval($this->root->mContext->mXoopsUser->get('uid')),
			'appName' => 'Forum',
			'key01' => 'postBody',
			'key02' => 'postId',
			'key03' => intval($params['postId']),
			'key04' => 'modify',
			'key05' => 'translation',
			'note1' => $row['description'],
			'mtFlg' => '0'
		);

		$sql  = '';
		$sql .= ' SELECT * FROM '.$postsTable.' AS T ';
		$sql .= '   LEFT JOIN ';
		$sql .= '     ( SELECT * FROM '.$postsBodyTable.' WHERE language_code = %s) AS T1 ';
		$sql .= '   USING(post_id) ';
		$sql .= '   WHERE `post_id` = \'%d\' ';
		$sql = sprintf($sql, $params['postId'], $row['post_original_language']);
		$result = $this->db->query($sql);
		$row2 = $this->db->fetchArray($result);

		$inParams = array(
			'sourceLang' => $row['post_original_language'],
			'targetLang' => $selectedLanguage,
			'serviceId' => '',
			'bindingString' => '',
			'source' => $row2['description']
		);

		$outParams['contents']['targetText']['contents'] = $message;
		$translationLogs->translateLog($inParams, $outParams, $config);

		// call user hook function
		$pinfo = pathinfo(__FILE__);
		$hookfile = $pinfo['dirname'].'/hooks/'.$pinfo['filename'].'.hook.'.$pinfo['extension'];
		if (file_exists($hookfile)) {
			require_once($hookfile);
			$hookclass = get_class($this).'_Hook';
			if (class_exists($hookclass)) {
				$hook =& new $hookclass;
				$hookfunc = 'modifyPostAfter';
				if (method_exists($hook, $hookfunc)) {
					call_user_method($hookfunc, $hook, $params);
				}
			}
		}

		return;
	}

	public function getPosts($topicId, $offset, $limit) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$userTable = $this->db->prefix('users');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
		$postFileTable = $this->db->prefix($this->moduleName.'_post_file');

		$groupId = $this->root->mContext->mXoopsUser->getGroups();
		$selectedLanguage = $this->languageManager->getSelectedLanguage();

		$sql  = '';
		$sql .= ' SELECT post_id, file_id, file_name, file_size, uid, name, uname, language_code, description, poster_ip, post_original_language, post_time, post_order, delete_flag, update_date, update_time ,T2.user_avatar FROM (('.$postsTable;
		$sql .= ' LEFT JOIN (SELECT post_id, language_code, description, update_time FROM '.$postsBodyTable.' WHERE `language_code` = \''.$selectedLanguage.'\') AS T1 USING (post_id)) ';
		$sql .= ' LEFT JOIN (SELECT uid, name, uname, user_avatar FROM '.$userTable.') AS T2 USING (uid)) ';
		$sql .= ' LEFT JOIN (SELECT id AS file_id, post_id, file_name, file_size FROM '.$postFileTable.') AS T3 USING (post_id) ';
		$sql .= ' WHERE topic_id = '.intval($topicId).' ';
		$sql .= ' ORDER BY post_order ASC';
//		$sql .= ' LIMIT '.intval($offset).', '.intval($limit);

		$result = $this->db->query($sql);
		if (!$result) {
			die($sql);
		}

		$posts = array();
		require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
		$i = 0;
		$pointer = 0;
		while ($row = $this->db->fetchArray($result)) {
			if ($i > 0 && $posts[$pointer]['id'] == $row['post_id']) {
				$posts[$pointer]['files'][] = array(
					'id' => $row['file_id']
					, 'name' => $row['file_name']
					, 'size' => $row['file_size'] / 1000
				);
				continue;
			}
			$pointer = $i;
			if ($row['file_id']) {
				$posts[$pointer]['files'][] = array(
					'id' => $row['file_id']
					, 'name' => $row['file_name']
					, 'size' => $row['file_size'] / 1000
				);
			}
			$posts[$i]['id'] = $row['post_id'];
			$posts[$i]['user'] = array(
					'id' => $row['uid'],
					'name' => ($row['name'] != '') ? $row['name'] : $row['uname']
				);
			$posts[$i]['permission'] = array(
					'delete' => false,
					'edit' => false,
					'reply' => false,
					'modify' => false
				);
			$posts[$i]['language'] = array(
					'code' => $row['post_original_language'],
					'name' => $LANGRID_LANGUAGE_ARRAY[$row['post_original_language']]
				);
			$posts[$i]['message'] = $row['description'];
			$posts[$i]['ip'] = $row['poster_ip'];
			$posts[$i]['date'] = formatTimestamp($row['post_time'], 'm');
			$posts[$i]['time'] = $row['post_time'];
			$posts[$i]['updateTime'] = min($row['update_date'], $row['update_time']);
			$posts[$i]['order'] = $row['post_order'];
			$posts[$i]['deleteFlag'] = (bool) $row['delete_flag'];
			if ($posts[$i]['deleteFlag']) {
				$posts[$i]['updateTime'] = ($row['update_date']);
			}
			$posts[$i]['translationFlag'] = true;

			if ($row["user_avatar"]=="blank.gif") $row["user_avatar"] = "no-image.jpg";
			$posts[$i]["avatar"] = XOOPS_UPLOAD_URL."/".$row["user_avatar"];
//			$posts[$i] = array(
//				'id' => $row['post_id'],
//				'user' => array(
//					'id' => $row['uid'],
//					'name' => $row['uname']
//				),
//				'permission' => array(
//					'delete' => false,
//					'edit' => false,
//					'reply' => false,
//					'modify' => false
//				),
//				'language' => array(
//					'code' => $row['post_original_language'],
//					'name' => $LANGRID_LANGUAGE_ARRAY[$row['post_original_language']]
//				),
//				'message' => $row['description'],
//				'ip' => $row['poster_ip'],
//				'date' => formatTimestamp($row['post_time'], 'm'),
//				'time' => $row['post_time'],
//				'updateTime' => max($row['update_date'], $row['update_time']),
//				'order' => $row['post_order'],
//				'deleteFlag' => (bool) $row['delete_flag'],
//				'translationFlag' => true
//			);
			if (!isset($row['description']) || $row['description'] == "") {
				$posts[$i]['message'] = _MD_D3FORUM_NO_TRANSLATION;
				$posts[$i]['translationFlag'] = false;
			}
			if ($row['delete_flag']) {
				$posts[$i]['message'] = _MD_D3FORUM_POST_DELETED;
			}
			if ($row['delete_flag']) {
				$posts[$i]['permission'] = array(
					'delete' => false,
					'edit' => false,
					'modify' => false,
					'reply' => false
				);
			} else {
				if ($row['post_original_language'] != $this->languageManager->getSelectedLanguage()) {
					$posts[$i]['permission']['modify'] = true;
				}
				if ($this->root->mContext->mXoopsUser->isAdmin() || $this->root->mContext->mXoopsUser->get('uid') == $row['uid']) {
					if (!$posts[$i]['permission']['modify']) {
						$posts[$i]['permission']['edit'] = true;
					}
					$posts[$i]['permission']['delete'] = true;
				}
				$posts[$i]['permission']['reply'] = true;
			}
			$i++;
		}
		return $posts;
	}
	public function getPostsByTopicId($topicId, $page, $view) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
		$usersTable = $this->db->prefix("users");
		$groupId = $this->root->mContext->mXoopsUser->getGroups();
		$selectedLanguage = $this->languageManager->getSelectedLanguage();

		$sql  = '';
/*
		$sql .= ' SELECT * FROM '.$postsTable.' AS P ';
		$sql .= ' LEFT JOIN ( ';
		$sql .= '   SELECT * FROM '.$postsBodyTable.' ';
		$sql .= '   WHERE `language_code` = \''.$selectedLanguage.'\' ';
		$sql .= ' ) AS T1 USING (post_id) ';
		$sql .= ' LEFT JOIN ( ';
		$sql .= '   SELECT `post_id` AS t2id,`post_order` AS reply_number  ';
		$sql .= '   FROM '.$postsTable.'  ';
		$sql .= '   WHERE `delete_flag` = 0 ';
		$sql .= ' ) AS T2 ';
		$sql .= ' ON P.`reply_post_id` = T2.t2id ';
		$sql .= ' WHERE P.`topic_id` = '.intval($topicId).' ';
		$sql .= ' ORDER BY P.`post_order` ASC';
*/
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
		$sql .= '   P2.post_order as reply_number, ';
		$sql .= '   U.user_avatar as avatar ';
		$sql .= ' FROM '.$postsTable.' AS P ';
		//$sql .= ' LEFT JOIN '.$postsBodyTable.' AS PB USING (post_id) ';
		$sql .= '  LEFT JOIN  ( SELECT * FROM '.$postsBodyTable.' WHERE `language_code` = \''.$selectedLanguage.'\') AS PB  USING (post_id) ';
		$sql .= ' LEFT JOIN '.$postsTable.' AS P2 ';
		$sql .= '   ON P.`reply_post_id` = P2.`post_id` ';
		$sql .= ' LEFT JOIN '.$usersTable.' AS U ';
		$sql .= '   ON U.`uid` = P.`uid` ';
		$sql .= ' WHERE P.`topic_id` = '.intval($topicId).' ';
		//$sql .= '   AND PB.`language_code` = \''.$selectedLanguage.'\' ';
		$sql .= ' ORDER BY P.post_order ASC ';

		$page = intval($page);

		if (!$page) {
			$page = 1;
		}

		$view = intval($view);

		if (!$view) {
			$view = POST_LIST_MAX;
		}
		$page--;
		$start = $page * $view;

		$sql .= ' LIMIT '.intval(($page) * intval($view)).', '.intval($view);

		$result = $this->db->query($sql);

		$posts = array();
		$i = 0;
		while ($row = $this->db->fetchArray($result)) {
			$posts[$i] = new Post($row);
			$posts[$i]->setUpdateTime($row['update_date']);
			/*
			if(is_numeric($row['reply_post_id']) && $row['reply_post_id'] != 0){
				$sql  = '';
				$sql .= ' SELECT post_order ';
				$sql .= '   FROM '.$postsTable;
				$sql .= '   WHERE post_id = '.intval($row['reply_post_id']).' AND delete_flag = 0 ';
				$sql .= ' LIMIT 1 ';

				$result2 = $this->db->query($sql);
				if ($row2 = $this->db->fetchArray($result2)) {
					$posts[$i]->setReplyNumber($row2['post_order']);
				}
			}
			*/
			$i++;
		}
		return $posts;
//		while ($row = $this->db->fetchArray($result)) {
//			$this->posts[] = new Post($row);
//		}
//		return $this->posts;
	}

	public function deletePost($postId) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$sql  = '';
		$sql .= ' UPDATE '.$postsTable.' SET delete_flag = 1, update_date = '.time().' WHERE post_id = %d ';
		$sql = sprintf($sql, $postId);
		$result = $this->db->query($sql);

		if (!$result) {
			die(_MD_D3FORUM_SYSTEM_MESSAGE_ERROR_POST);
		}
		return;
	}
	/**
	 * @return Array
	 */
	public function getPost($postId) {
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
		$selectedLanguage = $this->languageManager->getSelectedLanguage();

		$groupId = $this->root->mContext->mXoopsUser->getGroups();

		$sql  = '';
		$sql .= ' SELECT * ';
		$sql .= '   FROM '.$postsTable;
		$sql .= '     LEFT JOIN ';
		$sql .= '       (SELECT * FROM '.$postsBodyTable.' WHERE `language_code` = \''.$selectedLanguage.'\') ';
		$sql .= '     AS T1 USING (post_id)';
		$sql .= '   WHERE post_id = '.intval($postId).' AND delete_flag = 0 ';

		$result = $this->db->query($sql);

		if ($row = $this->db->fetchArray($result)) {
			$post = new Post($row);
			if(is_numeric($row['reply_post_id']) && $row['reply_post_id'] != 0){
				$sql  = '';
				$sql .= ' SELECT post_order ';
				$sql .= '   FROM '.$postsTable;
				$sql .= '   WHERE post_id = '.intval($row['reply_post_id']).' AND delete_flag = 0 ';
				$sql .= ' LIMIT 1 ';

				$result2 = $this->db->query($sql);
				if ($row2 = $this->db->fetchArray($result2)) {
					$post->setReplyNumber($row2['post_order']);
				}
			}
		}
		return $post;
	}

	public function getPostsCount($topicId) {
		$postsCount = 0;
		$postsTable = $this->db->prefix($this->moduleName.'_posts');
		$postsBodyTable = $this->db->prefix($this->moduleName.'_posts_body');
		$selectedLanguage = $this->languageManager->getSelectedLanguage();

		$sql  = '';
		$sql .= ' SELECT COUNT(*) FROM '.$postsTable;
		$sql .= ' WHERE topic_id = ?';

		$this->db->prepare($sql);
		$this->db->bind_param('i', $topicId);
		$result = $this->db->execute();

		if ($row = $this->db->fetchArray($result)) {
			$postsCount = $row['COUNT(*)'];
		}
		return $postsCount;
	}
}
?>