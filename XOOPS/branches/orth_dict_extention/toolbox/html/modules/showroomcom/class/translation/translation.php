<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
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
//require_once XOOPS_ROOT_PATH.'/modules/langrid/php/building-blocks/combination/translation-with-bilingual-dictionary-with-lm.php';
require_once XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php';
require_once dirname(__FILE__).'/../manager/language-manager.php';

class Translation {

	private $targetLanguages = array();
	private $sourceText = '';
	private $translator;
	private $result = array(
						'status' => 'OK',
						'message' => 'Success',
						'contents' => array()
						);
	private $isError = false;
	private $isWarning = false;
	private $languageManager;

	function __construct() {
		$this->root = XCube_Root::getSingleton();
		$this->languageManager = new LanguageManager();
		$this->sourceLanguageTag = $this->languageManager->getSelectedLanguage();
		$this->targetLanguages = $this->languageManager->getToLanguages();
//		$translator = 'http://langrid.nict.go.jp/langrid-1.2/invoker/KyotoUJServer';
//		$this->translator = new TranslationWithBilingualDictionaryWithLongestMatch();
//		$largeDicts = null;
//		$this->translator->setBindings($this->languageManager->getSourceLanguageTag(), $translator, $largeDicts);
	}

	/**
	 * @param $sourceText
	 * @return Array
	 * result['contents'][language tag]['translation']['contents']
	 * result['contents'][language tag]['backTranslation']['contents']
	 */
	public function translate ($sourceText = '', $config = array(), $backTranslateFlag = false) {
		$userDict = array(array());

		$config = array_merge(
			array(
					'appName'=> 'Forum',
					'loginUserId'=> $this->root->mContext->mXoopsUser->get('uid'),
//					'key01' => 'postBody',//postBody, postTitle, topicTitle, topicBody
//					'key02' => 'postId',//postId, topicId, forumId,
//					'key03' => '1',// id of key02
//					'key04' => 1,
//					'key05' => 0,
					'mtFlg' => '1',
//					'note1'=>'modify',//modify or post or ajax
//					'note2'=> 'translation'//backTranslation, translation
				),
			$config
		);

		foreach ($this->targetLanguages as $targetLanguageTag) {
			$config['note2'] = 'translation';
			$langridClient = new LangridClient(
				array(
					'sourceLang' => $this->sourceLanguageTag,
					'targetLang' => $targetLanguageTag
					),
				$config
			);
//			$translation = $langridClient->translate($sourceText);
			$translation = $this->sentenceTranslate($langridClient, $sourceText);
//			var_dump($this->sourceLanguageTag);
//			var_dump($targetLanguageTag);
//			var_dump($translation);
//			die();
			$this->result['contents'][$targetLanguageTag]['translation'] = $translation['contents']['targetText'];
			$this->result['contents'][$targetLanguageTag]['translation']['sourceLanguage'] = $this->languageManager->getNameByTag($this->sourceLanguageTag);
			$this->result['contents'][$targetLanguageTag]['translation']['targetLanguage'] = $this->languageManager->getNameByTag($targetLanguageTag);

			if (array_search($this->sourceLanguageTag, $this->languageManager->getToLanguagesBySourceLanguageTag($targetLanguageTag)) !== false) {
				$config['note2'] = 'backTranslation';
				$langridClient = new LangridClient(
					array(
						'sourceLang' => $targetLanguageTag,
						'targetLang' => $this->sourceLanguageTag
						),
					$config
				);
//				$backTranslation = $langridClient->translate($translation['contents']['targetText']['contents']);
				if ($backTranslateFlag) {
					$backTranslation = $this->sentenceTranslate($langridClient, $translation['contents']['targetText']['contents']);
					$this->result['contents'][$targetLanguageTag]['backTranslation'] = $backTranslation['contents']['targetText'];
					$this->result['contents'][$targetLanguageTag]['backTranslation']['sourceLanguage'] = $this->languageManager->getNameByTag($targetLanguageTag);
					$this->result['contents'][$targetLanguageTag]['backTranslation']['targetLanguage'] = $this->languageManager->getNameByTag($this->sourceLanguageTag);
				}
			} else {
				$this->result['contents'][$targetLanguageTag]['backTranslation'] = array(
					'status' => 'WARNING',
					'contents' => '<font color="red">'.$targetLanguageTag.' =&gt; '.$this->sourceLanguageTag.' is not supported</font>',
					'message' => 'not supported',
					'sourceLanguage' => $this->languageManager->getNameByTag($targetLanguageTag),
					'targetLanguage' => $this->languageManager->getNameByTag($this->sourceLanguageTag)
				);
			}
		}
		return $this->result;
	}

	public function sentenceTranslate($langridClient, $text) {
		$sourceTexts = explode("\n", $text);
//		foreach ($sourceTexts as $key => $s) {
//			if ($this->textBlank($s)) {
//				$translation[]['contents']['targetText']['contents'] = '';
//				continue;
//			}
//			$translation[] = $langridClient->translate($s);
//		}
//		$return = $translation[0];
//		foreach ($translation as $key => $t) {
//			if (!$key) {
//				continue;
//			}
//			$return['contents']['targetText']['contents'] .= "\n".$t['contents']['targetText']['contents'];
//		}
// 		$translation['contents']['targetText'];

//		$return = $langridClient->translate($text);
//		var_dump($return);
		$prefix = array();
		foreach ($sourceTexts as $key => $value) {
			preg_match('/^>*/', $value, $matches);
			$prefix[$key] = $matches[0];
			$sourceTexts[$key] = preg_replace('/^>*/', '', $value);
		}
		$result = $langridClient->translate($sourceTexts);
//		error_log(print_r($result, 1), 3, dirname(__FILE__).'/logs/translation-log-'.date('Y-m-d-H-i-s').'.log');
		$tl = array_keys($result['contents']);
		$tt = '';
		foreach ($result['contents'][$tl[0]] as $key => $value) {
			$tt .= $prefix[$key];
			if (strtoupper($value['status']) != 'OK' || strtoupper($value['contents']['targetText']['status']) != 'OK') {
				$tt .= "\n";
				continue;
			}
			$tt .= $value['contents']['targetText']['contents']."\n";
		}
		$tt = substr($tt, 0, -1);
		$return = array(
			'status' => 'OK',
			'message' => '',
			'contents' =>
				array(
					'targetLanguage' => $tl[0],
					'targetText' => array(
						'contents' => $tt,
						'status' => 'OK'
					)
				)
		);
		return $return;
	}

	public function textBlank($text) {
		return (!preg_replace('/[\r\n　\t \s]/u', '', $text));
	}

	public function isError() {
		return $this->isError;
//		return strtoupper($this->result['status']) == 'ERROR';
	}
	public function isWarning() {
		return $this->isWarning;
//		return strtoupper($this->result['status']) == 'WARNING';
	}
	public function getResult() {
		return $this->result;
	}
	public function setTargetLanguage($targetLanguage) {
		$this->targetLanguages = array($targetLanguage);
//		$this->targetLanguages = array($targetLanguage => '');
	}
	public function setTargetLanguages($targetLanguages) {
		$this->targetLanguages = $targetLanguages;
	}
	public function getTargetLanguages() {
		return $this->targetLanguages;
	}
	public function getSourceText() {
		return $this->sourceText;
	}
	public function setSourceLanguageTag($sourceLanguageTag) {
		$this->sourceLanguageTag = $sourceLanguageTag;
	}
}
?>