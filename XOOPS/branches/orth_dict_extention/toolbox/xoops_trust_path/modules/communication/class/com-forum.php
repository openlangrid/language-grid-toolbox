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
require_once dirname(__FILE__).'/com-category.php';
require_once dirname(__FILE__).'/language-manager.php';

class Com_Forum {
	private $forum;
	var $id;

	private function __construct($forum) {
		$this -> forum = $forum;
		$this -> id = $forum->mVars['forum_id']['value'];
	}
	
	public function getCategoryId() {
		return $this -> forum ->mVars['cat_id']['value'];	
	}
	
	public function getTitleForLanguage($languageCode) {
		foreach($this -> forum -> body as $body) {
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
		return $this -> forum -> mVars['post_original_language']['value'];
	}
	
	public function getSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $languageManager->getSelectedLanguage();
	}
	
	
	public function getCategory() {
		return Com_Category::findByCategoryId($this->getCategoryId());
	}
	
	static public function findByForumId($forumId) {
		$root =& XCube_Root::getSingleton();
		$moduleName = $root->mContext->mModule->mXoopsModule->get('dirname');
		$bbs = new BBSClient($moduleName);
		return new Com_Forum($bbs -> getForum($forumId));
	}
}
?>
