<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: AutoComplete.php 3614 2010-04-08 07:41:29Z yoshimura $ */

require_once(XOOPS_ROOT_PATH.'/api/class/manager/Toolbox_AbstractManager.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/ParallelTextClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/TranslationTemplateClient.class.php');

require_once(dirname(__FILE__).'/AutoCompleteSetting.php');
require_once(dirname(__FILE__).'/AutoCompleteTrieTree.php');

class AutoComplete extends Toolbox_AbstractManager {

	/** トライ木 */
	private $mTrieTree = null;
	/** 穴埋単語 */
	private $mBoundWords = array();

	private $mSetting = null;
	private $mSearchedLines = null;

	/**
	 * コンストラクタ
	 */
	public function AutoComplete() {
		parent::__construct();
		$this->mSetting = new AutoCompleteSetting();
		$this->mTrieTree = new AutoCompleteTrieTree();
	}

	/**
	 * 用例の検索とトライ木の構築(穴埋単語の収集も)
	 */
	public function initSearch($language, $keyword) {
		if ($this->searchParallelText($language, $keyword) === true) {
			$this->buildeTrieTree();
		}
	}

	/**
	 * トライ木を探査して候補を抽出
	 */
	public function find($keyword) {
		// $keywordの次のノードを取得（探査するときの根ノード）
		$topNode = $this->mTrieTree->findNode($keyword);
		if ($topNode === false) {
			return false;
		}
		$dist = array();
		// 探査(穴開は穴のまま)
		foreach ($topNode->children as $node) {
			$surveyer = new AutoComplete_Surveyer();
			$dist[] = $surveyer->survey($node);
		}

		// 穴埋単語をレスポンスにセット
		$response = array();
		foreach ($dist as &$line) {
			$line['node']->children = array();
			if ($line['nodeType'] == 'B') {
//				$line['boundWord'] = $this->mBoundWords[$line['value']];
				foreach ($this->mBoundWords[$line['node']->getBoundUniKey()] as $id => $word) {
					$a = $line;
					$a['word'] = $word;
					$a['wordId'] = $id;
					$response[] = $a;
				}
			} else {
				$response[] = $line;
			}
		}

		return $response;
	}

	public function getTrieTree() {
		return $this->mTrieTree;
	}

	public function getSearchedLines() {
		return $this->mSearchedLines;
	}

	protected function searchParallelText($language, $keyword) {
		// ParallelTextは検索するだけ、穴あきは、、、どうしよう、全展開、、、
		$resources = $this->mSetting->load();
		$lines = array();
		foreach ($resources as $name) {
			$searcher = new AutoComplete_Searcher();
			$res = $searcher->search($name, $language, $keyword);
			$lines = array_merge($lines, $res);
			$this->mBoundWords = array_merge($this->mBoundWords, $searcher->getSearchedBoundWords());
		}
		$this->mSearchedLines = $lines;

		return true;
	}

	protected function buildeTrieTree() {
		// 穴のノードを認識させるには、、、
		foreach ($this->mSearchedLines as $id => $line) {
			$this->mTrieTree->addString($line['text'], $id, $line['vo']);
		}
	}

}

class AutoComplete_Surveyer {
	private $string;
	private $node;
	private $nodeType;
	public function survey($node) {

		if ($node->isBound()) {
			if ($this->string == '') {
				$this->string = $node->keyString;
				$this->node = $node;
				$this->nodeType = 'B';
			}
			return array('value' => $this->string, 'node' => $this->node, 'nodeType' => $this->nodeType);
		}

		switch ($node->countChildren()) {
			case 0:		// 葉
				$this->node = $node;
				$this->nodeType = 'L';
				$this->string .= $node->keyString;
				break;
			case 1:		// 幹
				$this->node = $node;
				$this->nodeType = 'T';
				$this->string .= $node->keyString;
				$keys = array_keys($node->children);
				$this->survey($node->children[$keys[0]]);
				break;
			default:	// 枝分
				$this->node = $node;
				$this->nodeType = 'T';
				$this->string .= $node->keyString;
				break;
		}
		return array('value' => $this->string, 'node' => $this->node, 'nodeType' => $this->nodeType);
	}
}

class AutoComplete_Searcher {
	private $mResourceClient = null;
	private $mBoundWordsArray = array();

	public function AutoComplete_Searcher() {
		$this->mResourceClient = new ResourceClient();
	}
	public function search($resourceName, $language, $keyword) {
		$records = $this->doSearch($resourceName, $language, $keyword);
		if ($records === false) {
			return array();
		}
		return $records;
	}
	public function getSearchedBoundWords() {
		return $this->mBoundWordsArray;
	}

	private function doSearch($resourceName, $language, $keyword) {
		$res = $this->mResourceClient->getLanguageResource($resourceName);
		if ($res == null) {
			return false;
		}
		$vo = $res['contents'];
		$client = null;
		switch ($vo->type) {
			case 'PARALLELTEXT':
				$client = new ParallelTextClient();
				$res = $client->searchRecord($resourceName, $keyword, $language, "PREFIX");
				if ($res['status'] !== 'OK') {
					return false;
				}
				$dist = array();
				foreach ($res['contents'] as $vo) {
					$id = $this->_makeIndex($resourceName, $language, $vo->id);
					foreach ($vo->expressions as $exp) {
						if ($exp->language == $language) {
//							$dist[$id] = $exp->expression;
							$dist[$id] = array('text' => $exp->expression, 'vo' => $vo);
						}
					}
				}
				return $dist;
				break;
			case 'TRANSLATION_TEMPLATE':
				$client = new TranslationTemplateClient();
				$res = $client->searchRecord($resourceName, $keyword, $language, "prefix");
				if ($res['status'] !== 'OK') {
					return false;
				}
				$dist = array();
				foreach ($res['contents'] as $vo) {
					$id = $this->_makeIndex($resourceName, $language, $vo->id);

					// 穴埋単語を検索
					$boundWords = $client->getAllBoundWords($resourceName, $vo->wordSetIds[0]);
					$boundSets = array();
					foreach ($vo->wordSetIds as $key => $wordSetId) {
						$a = $client->getAllBoundWords($resourceName, $wordSetId);
						$words = array();
						foreach ($a['contents'] as $BoundWord) {
							foreach ($BoundWord->expressions as $exp) {
								if ($exp->language == $language) {
									$words[] = $exp->expression;
								}
							}
						}
						$boundSets[$key] = $words;
						$bid = "[$id&boundId=$key]";
						$this->mBoundWordsArray[$bid] = $words;

						$vo->boundSets[] = $a['contents'];
					}


					foreach ($vo->expressions as $exp) {
						if ($exp->language == $language) {
//							// 穴のIDを完全版に置換
//							$dist[$id] = preg_replace("/\[(\d)\]/u", "[$id&boundId=$1]", $exp->expression);
//							$dist[$id] = $exp->expression;
							$dist[$id] = array('text' => $exp->expression, 'vo' => $vo);
						}
					}
				}
				return $dist;
				break;
			default:
				return false;
				break;
		}
		return $client;
	}

	/*
	 * 葉を特定するためのキー文字列を生成
	 */
	private function _makeIndex($name, $language, $index) {
		return sprintf('name=%s&lang=%s&row=%s', urlencode($name), $language, $index);
	}
}
?>
