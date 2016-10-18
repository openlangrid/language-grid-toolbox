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
/* $Id: JumpBox.class.php 4540 2010-10-07 04:01:57Z uehara $ */

require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');

class JumpBox {

	private $mClient = null;
	private $catId = null;
	private $forumId = null;
	private $topicId = null;

    public function __construct($catId = null, $forumId = null, $topicId = null) {
    	$this->mClient = new BBSClient(USE_TABLE_PREFIX);
	$this->catId = $catId;
	$this->forumId = $forumId;
	$this->topicId = $topicId;
    }

    public function loadAssignValues() {
//    	$result = array(
//    		'categoryList' => array(array('id'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_NEW_OPTION_CAT)),
//    		'forumList' => array(array('id'=>0, 'parentId'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_NEW_OPTION_FORUM)),
//    		'topicList' => array(array('id'=>0, 'parentId'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_NEW_OPTION_TOPIC))
//    	);
		$result = $this->createDefaultOptions();

		$language = $this->_getLanguage();

		$categoryRes = $this->mClient->getAllCategories();
		$categoryList = $categoryRes['contents'];
		foreach ($categoryList as $category) {
			$selected = ($this->catId === $category->id)?true:null;
			$result['categoryList'][] = array('id' => $category->id, 'text' => $this->_getText($category, $language), 'selected' => $selected);
			$forumRes = $this->mClient->getAllForums($category->id);
			$forumList = $forumRes['contents'];
			foreach ($forumList as $forum) {
				$selected = ($this->forumId === $forum->id)?true:null;
				$result['forumList'][] = array('id' => $forum->id, 'parentId' => $forum->categoryId, 'text' => $this->_getText($forum, $language), 'selected' => $selected);
				$topicRes = $this->mClient->getAllTopics($forum->id);
				$topicList = $topicRes['contents'];
				foreach ($topicList as $topic) {
					$selected = ($this->topicId === $topic->id)?true:null;
					$result['topicList'][] = array('id' => $topic->id, 'parentId' => $topic->forumId, 'text' => $this->_getText($topic, $language), 'selected' => $selected);
				}
			}
		}

    	return $result;
    }

    protected function createDefaultOptions() {
    	$result = array(
    		'categoryList' => array(array('id'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_NEW_OPTION_CAT)),
    		'forumList' => array(array('id'=>0, 'parentId'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_NEW_OPTION_FORUM)),
    		'topicList' => array(array('id'=>0, 'parentId'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_NEW_OPTION_TOPIC))
    	);
    	return $result;
    }

    private function _getText($obj, $lang, $val = 'title') {
		foreach ($obj->text->expression as $exp) {
			if ($exp->language == $lang) {
				if ($exp->$val) {
					return $exp->$val;
				} else {
					return _MD_D3FORUM_NO_TRANSLATION;
				}
			}
		}
		return _MD_D3FORUM_NO_TRANSLATION;
	}

	private function _getLanguage() {
		require_once(dirname(__FILE__).'/../../class/manager/language-manager.php');
		$a = new LanguageManager();
		$lang = $a->getSelectedLanguage();
		return $lang;
	}

}

class NaviSelectorBox extends JumpBox {
    protected function createDefaultOptions() {
    	$result = array(
    		'categoryList' => array(array('id'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_ALL_OPTION_CAT)),
    		'forumList' => array(array('id'=>0, 'parentId'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_ALL_OPTION_FORUM)),
    		'topicList' => array(array('id'=>0, 'parentId'=>0, 'text'=>_MD_D3FORUM_JUMPBOX_ALL_OPTION_TOPIC))
    	);
    	return $result;
    }
}
?>
