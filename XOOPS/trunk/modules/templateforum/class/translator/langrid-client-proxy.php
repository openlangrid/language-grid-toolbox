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
require_once XOOPS_ROOT_PATH.'/modules/langrid/php/langrid-client.php';

class LangridClientProxy {

	protected $sourceLanguageCode;
	protected $targetLanguageCode;
	protected $sourceText;
	protected $logOptions;

	protected $langridClient;

	/**
	 * Constructor
	 *
	 * @param String $sourceLanguageCode
	 * @param String $targetLanguageCode
	 * @param Array $logOptions
	 */
	public function __construct($sourceLanguageCode = ''
		, $targetLanguageCode = '', $logOptions = array()
	) {
		$this->setSourceLanguageCode($sourceLanguageCode);
		$this->setTargetLanguageCode($targetLanguageCode);
		$this->setLogOptions($logOptions);
	}


	/**
	 * translate
	 */
	public function translate($sourceText) {
		if (!$sourceText) {
			return;
		}
		return $this->adapter($this->doTranslate($sourceText));
	}

	/**
	 * real process
	 */
	protected function doTranslate($sourceText) {
		$langridClient = new LangridClient(array(
			'sourceLang' => $this->getSourceLanguageCode(),
			'targetLang' => $this->getTargetLanguageCode()
		)
		, $this->getLogOptions());

		preg_match('/^>* */', $sourceText, $matches);
		$sourceText = preg_replace('/^>* */', '', $sourceText);
		$result = $langridClient->translate($sourceText);
		$result['contents']['targetText']['contents'] = $matches[0].$result['contents']['targetText']['contents'];

		return $result;
	}

	/**
	 * adapter
	 */
	protected function adapter($response) {
		$result = $response;
		$result['contents'][$response['contents']['targetLanguage']]['translation']
			= $response['contents']['targetText'];
		$result['contents'][$response['contents']['targetLanguage']]['backTranslation']
			= $response['contents']['targetText'];
		return $result;
	}

	/**
	 * getter/setter
	 */
	public function getSourceLanguageCode() {
		return $this->sourceLanguageCode;
	}
	public function setSourceLanguageCode($sourceLanguageCode) {
		$this->sourceLanguageCode = $sourceLanguageCode;
	}
	public function getTargetLanguageCode() {
		return $this->targetLanguageCode;
	}
	public function setTargetLanguageCode($targetLanguageCode) {
		$this->targetLanguageCode = $targetLanguageCode;
	}
	public function getSourceText() {
		return $this->sourceText;
	}
	public function setSourceText($sourceText) {
		$this->sourceText = $sourceText;
	}
	public function getLogOptions() {
		return $this->logOptions;
	}
	public function setLogOptions($logOptions = array()) {
		// log
		$this->logOptions = array_merge(
			array(
					'appName'=> 'BBS',
					'key01' => '',//01:category create, 02:category edit
					'key02' => '',//0:category id, 1:forum id, 2:topic id, 3:post id
					'key03' => 0,// id of key02
					'key04' => 1,//unused
					'key05' => 0,//unused
					'mtFlg' => '1',
					'note1'=>'ajax',//0:ajax, 1:modify, 2:edit, 3:post
					'note2'=> 0//0:translation, 1:back-translation
				), $logOptions
		);
	}
}
?>