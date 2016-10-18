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
require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
require_once(dirname(__FILE__).'/TagManagerAdapter.class.php');
require_once(dirname(__FILE__).'/../../../class/manager/language-manager.php');

class TagBBSClientAdapter extends BBSClient {

    public function __construct() {
    	parent::__construct(USE_TABLE_PREFIX);
    }

    public function getBindTags($postId) {
    	$manager = new TagManagerAdapter(USE_TABLE_PREFIX);
    	$relationObjects = $manager->findRelationsByPostId($postId);
    	if (!$relationObjects) {
    		return array();
    	}

    	$result = array();
    	foreach ($relationObjects as $relationObject) {
    		$set = $this->getTagSet($relationObject->get('tag_set_id'));
    		$tag = $this->getTag($relationObject->get('tag_set_id'), $relationObject->get('tag_id'));
    		if ($set['status'] == 'OK' && $tag['status'] == 'OK') {
    			$result[] = $this->_convertTag($set['contents'], $tag['contents']);
    		}
    	}
    	return $result;
    }

    public function addTagRelation($postId, $tagIds) {
    	$manager = new Toolbox_BBS_TagManager(USE_TABLE_PREFIX);
    	return $manager->bindTag($postId, $tagIds);
    }

    public function deleteTagRelation($postId) {
    	$manager = new Toolbox_BBS_TagManager(USE_TABLE_PREFIX);
    	return $manager->deleteBindTag($postId);
    }

    public function updateTagRelation($postId, $tagIds) {
    	$manager = new Toolbox_BBS_TagManager(USE_TABLE_PREFIX);
    	if ($manager->deleteBindTag($postId)) {
    		return $manager->bindTag($postId, $tagIds);
    	}
    	return false;
    }

    private function _convertTag($set, $tag) {
		$result = array();
		$lang = $this->_getUseLang();

		$result['tag_set_id'] = $set->id;
		$result['tag_id'] = $tag->id;
		$result['name'] = $this->_getText($set->name, $lang);
		$result['word'] = $this->_getText($tag->expressions, $lang);

		return $result;
    }

    private function _getUseLang() {
    	$a = new LanguageManager();
    	$lang = $a->getSelectedLanguage();
    	return $lang;
    }

    private function _getText($obj, $lang) {
    	$text = null;
    	$default = '';
    	foreach ($obj as $exp) {
    		if ($exp->language == $lang) {
    			$text = $exp->expression;
    		}
    		if ($exp->language == 'en') {
    			$default = $exp->expression;
    		}
    	}
		if ($text) {
			return $text;
		}
		return $default;
    }
}
?>