<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
/* $Id: PostedNotice.class.php 6254 2012-01-23 05:33:54Z infonic $ */
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_AbstractManager.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
require_once(dirname(__FILE__).'/PostedNotice_LanguageResource.php');
require_once(dirname(__FILE__).'/mail/PostedNoticeMailBuilder.class.php');
require_once(dirname(__FILE__).'/dao/PostedNoticeConfig.handler.php');

class PostedNotice extends Toolbox_AbstractManager {

	private $mHandler = null;

	public function __construct() {
		parent::__construct();
		$this->mHandler = new PostedNoticeConfigHandler($this->db, 'forum');
    }


    public function loadPostedNoticeConfig() {
    	$type = 0;
    	$lang = 0;
    	$obj = $this->mHandler->findByUserId($this->uid);
    	if ($obj) {
			$type = $obj->get('notice_type');
			$lang = $obj->get('language_code');
    	} else {
			require_once(dirname(__FILE__).'/../../class/manager/language-manager.php');
    		$a = new LanguageManager();
    		$lang = $a->getSelectedLanguage();
    	}

    	return array('type' => $type, 'language' => $lang);
    }

    public function savePostedNoticeConfig($type, $language) {
		return $this->mHandler->save($this->uid, $type, $language);
    }

    public function notifyPostedEachTime($postId) {
		$users = $this->mHandler->getSendToUserByEachTime();
		if (!$users) {
			return;
		}
		$userAdp = new UserAdapter();
		$userList = $userAdp->getIncludeXoopsUsers($users);

		$bbsAdp = new PostMessageAdapter();
		$messages = $bbsAdp->getMessage($postId);

		$this->sendNotify($userList, $messages);
    }

    public function notifyPostedOnceDay() {
		$users = $this->mHandler->getSendToUserByOnceDay();
		if (!$users) {
			return;
		}
		$userAdp = new UserAdapter();
		$userList = $userAdp->getIncludeXoopsUsers($users);

		$bbsAdp = new PostMessageAdapter();
		$messages = $bbsAdp->getMessageList();

		if (count($messages) > 0) {
			$this->sendNotify($userList, $messages);
		}
    }

    protected function sendNotify($sendToUsers, $messages) {
    	global $xoopsModuleConfig;
    	$xConfig = $this->root->mContext->getXoopsConfig();
    	$languageResource = new PostedNotice_LanguageResource();

		$bbs = new PostMessageAdapter();
		$bbsOther = $bbs->getCategoryForumTopic();

		foreach ($sendToUsers as $sendToUser) {
			$builder = new PostedNoticeMailBuilder($sendToUser['noticeConfig']->get('language_code'));
			$director = new PostedNoticeMailDirector(
				$builder, $sendToUser['xoopsUser'], $xConfig, $xoopsModuleConfig, $sendToUser['noticeConfig'], $messages, $bbsOther, $languageResource);
			$director->contruct();
			$xoopsMailer =& $builder->getResult();

			if (!$xoopsMailer->send()) {
				debugLog(print_r($xoopsMailer->getErrors(), true));
			}
		}
    }

    private function getSentToUsers() {
    	$result = array();
    	$users = $this->mHandler->getSendToUserByEachTime();
    	if ($users) {
			$userAdapter = new UserAdapter();
			foreach ($users as $user) {
				$xUser = $userAdapter->getUser($user->get('user_id'));
				$result[] = array(
					'xoopsUser' => $xUser,
					'noticeConfig' => $user
				);
			}
			return $result;
    	}
    	return false;
    }

}

class PostMessageAdapter {


	private $mClient = null;

	public function __construct() {
		$this->mClient = new BBSClient(USE_TABLE_PREFIX);
	}

	public function getMessage($messageId) {
		$a = $this->mClient->getPostMessage($messageId);
		if ($a['status'] == 'OK') {
			$arr = array();
			$arr[] = $a['contents'];
//			return $this->loadOtherInformation($arr);
			return $arr;
		}
		return false;
	}

	public function getMessageList() {
		$t = time();
		$t = $t - (60*60*24);
		$a = $this->mClient->getUpdatedMessages(null, $t);
		if ($a['status'] == 'OK') {
//			return $this->loadOtherInformation($a['contents']);
			return $a['contents'];
		}
		return false;
	}

	public function getCategoryForumTopic() {
		$result = array('categoryList'=>array(), 'forumList'=>array(), 'topicList'=>array());
		$categoryRes = $this->mClient->getAllCategories();
		$categoryList = $categoryRes['contents'];
		foreach ($categoryList as $category) {
			$result['categoryList'][$category->id] = $category;
			$forumRes = $this->mClient->getAllForums($category->id);
			$forumList = $forumRes['contents'];
			foreach ($forumList as $forum) {
				$result['forumList'][$forum->id] = $forum;
				$topicRes = $this->mClient->getAllTopics($forum->id);
				$topicList = $topicRes['contents'];
				foreach ($topicList as $topic) {
					$result['topicList'][$topic->id] = $topic;
				}
			}
		}

		return $result;
	}
}

class UserAdapter {

	private $mUserHandler = null;

	public function __construct() {
		$this->mUserHandler = xoops_gethandler('user');
	}

	public function getIncludeXoopsUsers($users) {
		$result = array();
		foreach ($users as $user) {
			$xUser = $this->getUser($user->get('user_id'));
			$result[] = array(
				'xoopsUser' => $xUser,
				'noticeConfig' => $user
			);
		}
		return $result;
	}

	private function getUser($userId) {
		$userArr = $this->mUserHandler->getObjects(new Criteria('uid', $userId));
		if ($userArr != null && is_array($userArr) && count($userArr) > 0) {
			return $userArr[0];
		}
		return false;
	}
}
?>