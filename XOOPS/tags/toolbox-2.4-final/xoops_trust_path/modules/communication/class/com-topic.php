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

require_once XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php';
require_once dirname(__FILE__).'/language-manager.php';
require_once dirname(__FILE__).'/com-forum.php';

class Com_Topic {
	private $topic;
	var $id;

	private function __construct($topic) {
		$this -> topic = $topic;
		$this -> id = $topic->mVars['topic_id']['value'];
	}
	
	public function _get($key) {
		return $this -> topic->mVars[$key]['value'];	
	}
	
	public function getForumId() {
		return $this -> topic->mVars['forum_id']['value'];	
	}
	
	public function getTitleForLanguage($languageCode) {
		foreach($this -> topic -> body as $body) {
			if($body -> mVars['language_code']['value'] == $languageCode) {
				return $body->mVars['title']['value'];
			}
		}
		return null;
	}
	
	public function getTitleOfOriginal() {
		$original = $this -> getOriginalLanguage();
		return $this -> getTitleForLanguage($original);
	}
	
	public function getTitleForSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $this -> getTitleForLanguage($languageManager->getSelectedLanguage());
	}
	
	public function getOriginalLanguage() {
		return $this -> topic -> mVars['topic_original_language']['value'];
	}
	
	public function getOriginalLanguageAsName() {
		return CommonUtil::toLanguageAsName($this -> getOriginalLanguage());
	}
	
	public function getSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $languageManager->getSelectedLanguage();
	}
	
	public function isOwner($uid) {
		return $this -> _get('uid') == $uid;
	}
	
	public function canPreEditByLoginUser() {
		return $this -> _get('uid') == getLoginUserUID();
	}
	
	public function canDelete() {
		return getLoginUserUID() == '1' || $this -> isOwner(getLoginUserUID());
	}
	
	
	public function getForum() {
		return Com_Forum::findByForumId($this -> getForumId());
	}
	
	static public function findByTopicId($topicId) {
		$root =& XCube_Root::getSingleton();
		$moduleName = $root->mContext->mModule->mXoopsModule->get('dirname');
		$bbs = new BBSClient($moduleName);
		return new Com_Topic($bbs -> getTopic($topicId));
	}
}
?>
