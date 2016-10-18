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
require_once(dirname(__FILE__).'/../../class/manager/language-manager.php');

class Tag {

	private $mBBSClient = null;

    public function __construct() {
		$this->mBBSClient = new BBSClient(USE_TABLE_PREFIX);
    }

	// 投稿ページでタグ選択ボックスを生成するに必要なタグデータ（言語でフィルタ）
    public function loadTag() {
    	$tagSetsRes = $this->mBBSClient->getAllTagSets();
    	if ($tagSetsRes == null || $tagSetsRes['status'] != 'OK') {
    		return false;
    	}
    	$tagSet = $tagSetsRes['contents'];
		$lang = $this->_getUseLang();

		$result = array();

		foreach ($tagSet as $set) {
			$words = array();
			foreach ($set->words as $word) {
				$words[] = array(
					'id' => $word->id,
					'word' => $this->_getText($word->expressions, $lang)
				);
			}
			$result[] = array(
				'id' => $set->id,
				'name' => $this->_getText($set->name, $lang),
				'words' => $words
			);
		}
		return $result;
    }

    // 設定画面で必要なタグのすべてのデータ
    public function loadConfigs() {
		// リソースのメタ的情報（サポート言語とかね）

		$a = array(
			'language' => array('en'=>'english', 'ja'=>'japanese'),
			'sourceLang' => 'en',
			'targetLang' => 'ja',
			'tagSets' => $this->loadTagSets()
		);

		return $a;
    }

    private function loadTagSets() {
		$tagSetsRes = $this->mBBSClient->getAllTagSets();
		if ($tagSetsRes == null || $tagSetsRes['status'] != 'OK') {
			return false;
		}
//		return $tagSetsRes['contents'];
		$contents = $tagSetsRes['contents'];
		$result = array();
		foreach ($contents as $set) {
			$w = array();
			foreach ($set->words as $word) {
				$w[] = array(
					'id' => $word->id,
					'expressions' => $this->_expressionSerial($word->expressions)
				);
			}

			$result[] = array(
				'id' => $set->id,
				'name' => $this->_expressionSerial($set->name),
				'words' => $w
			);
		}
		return $result;
    }

    public function saveTagSet($tagSetId, $names, $isNew = false) {
		$exps = $this->_expressionEncode($names);
		if ($isNew) {
			$apiRes = $this->mBBSClient->addTagSet($exps);
		} else {
			$apiRes = $this->mBBSClient->updateTagSet($tagSetId, $exps);
		}
		if ($apiRes != null && $apiRes['status'] == 'OK') {
			$set = $apiRes['contents'];

			$w = array();
			foreach ($set->words as $word) {
				$w[] = array(
					'id' => $word->id,
					'expressions' => $this->_expressionSerial($word->expressions)
				);
			}

			$result = array(
				'id' => $set->id,
				'name' => $this->_expressionSerial($set->name),
				'words' => $w
			);
			return $result;
		}
		return false;
    }

    public function saveTag($tagSetId, $tagId, $words, $isNew = false) {
		$exps = $this->_expressionEncode($words);
		if ($isNew) {
			$apiRes = $this->mBBSClient->addTag($tagSetId, $exps);
		} else {
			$apiRes = $this->mBBSClient->updateTag($tagSetId, $tagId, $exps);
		}
		if ($apiRes != null && $apiRes['status'] == 'OK') {
			$tag = $apiRes['contents'];
			$result = array(
				'id' => $tag->id,
				'expressions' => $this->_expressionSerial($tag->expressions)
			);
			return $result;
		}
		return false;
    }

    public function deleteTagSet($tagSetId) {
    	$apiRes = $this->mBBSClient->deleteTagSet($tagSetId);
    	return true;
//		if ($apiRes != null && $apiRes['status'] == 'OK') {
//			return true;
//		}
//		return true;
    }

    public function deleteTag($tagSetId, $tagId) {
    	$apiRes = $this->mBBSClient->deleteTag($tagSetId, $tagId);
    	return true;
    }

    private function _expressionSerial($expressions) {
    	$result = array();
    	foreach ($expressions as $expression) {
    		$result[$expression->language] = $expression->expression;
    	}
    	return $result;
    }

    private function _expressionEncode($params) {
    	$exps = array();
    	foreach($params as $key => $val) {
    		$exp = new ToolboxVO_BBS_TagExpression();
    		$exp->language = $key;
    		$exp->expression = $val;
    		$exps[] = $exp;
    	}
    	return $exps;
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

    public static function getPostedTagIds() {
		if (!isset($_POST['message_tag'])) {
			return null;
		}
		$row = $_POST['message_tag'];
		return $row;
    }
}
?>