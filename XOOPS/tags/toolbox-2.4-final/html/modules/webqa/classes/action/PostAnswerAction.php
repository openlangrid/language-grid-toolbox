<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
require_once(dirname(__FILE__).'/../Abstract_WebQABackendAction.class.php');
require_once(dirname(__FILE__).'/PostLanguageAction.php');
require_once(dirname(__FILE__).'/PostAuthorAction.php');
//require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');

class PostAnswerAction extends Abstract_WebQABackendAction {

	public function PostAnswerAction() {

	}

	public function dispatch() {
		$context = array();
		$form = $this->_getForm();
		
		//log_info($form);

		require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
//		require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');
//		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');

		$qaclient =& new QAClient();
//		$lgclient =& new LangridAccessClient();
//		$bbsclient =& new BBSClient_WebQaImpl('forum', $form['use_lang']);

		$config = $this->getModuleConfig();
		$name = $config['webqa_posting'];

//		// BBSの利用言語を再現
//		$_COOKIE["selectedLanguage"] = $form['use_lang'];

		// BBSの全翻訳パスを取得
//		$pathList =& $lgclient->getAllMultihopTranslationBindings('SITE');

//		$topicExpArray = array();
//		$msgExpArray = array();

//		foreach ($pathList['contents'] as $path) {
//			$srcLang = $path->path[0];
//			$tgtLang = $path->path[count($path->path) - 1];
//
//			$exp = new ToolboxVO_BBS_TopicExpression();
//			$exp->language = $tgtLang;
//			$msg = new ToolboxVO_BBS_MessageExpression();
//			$msg->language = $tgtLang;
//			if ($form['use_lang'] == $srcLang) {
//				if ($form['use_lang'] == $tgtLang) {
////					$exp->title = $form['title'];
////					$msg->expression = $form['question'];
//				} else {
//					$this->root =& XCube_Root::getSingleton();
//					print_r($this->root->mContext->mXoopsUser);
//
//					$transres = $lgclient->translate($srcLang, $tgtLang, $form['title'], 'SITE');
//					$transresText = $transres['contents'][0];
//					$exp->title = $transresText->result;
//					$transres = $lgclient->translate($srcLang, $tgtLang, $form['question'], 'SITE');
//					$transresText = $transres['contents'][0];
//					$msg->body = $transresText->result;
//				}
//				$topicExpArray[] = $exp;
//				$msgExpArray[] = $msg;
//			}
//		}

//		$exp = new ToolboxVO_BBS_TopicExpression();
//		$exp->language = $form['use_lang'];
//		$exp->title = $form['title'];
//		$msg = new ToolboxVO_BBS_MessageExpression();
//		$msg->language = $form['use_lang'];
//		$msg->body = $form['question'];
//		$topicExpArray[] = $exp;
//		$msgExpArray[] = $msg;


//		$forumId = 0;
//		$config = $this->getModuleConfig();
//		$forumId = $config['webqa_for_bbs'];

//		$bbsres = $bbsclient->createTopic($forumId, $topicExpArray);
//		$topicId = $bbsres['contents']->id;
//		$bbsres = $bbsclient->postMessage($topicId, $msgExpArray);

		$id = $form['id'];
		
		$record = $qaclient->getRecord($name, $id);
		$record['contents']->answers[] = $this->_makeA($form);
		//log_info($record);
		
		$qares = $qaclient->updateRecord($name, $id, $record['contents']->question, $record['contents']->answers, $record['contents']->categoryIds);
		$answer = array_pop($qares['contents']->answers);
		PostLanguageAction::registerLanguage($form['use_lang'], $id, $answer->id);
		if (@$form['author_uname']) {
			PostAuthorAction::registerAuthor($form['author_uname'], $id, $answer->id);
		}

		//log_info($qares);
	}

	private function _getExpressionByUseLang($exps, $lang) {
		foreach ($exps as $exp) {
			if ($exp->language == $lang) {
				return $exp->expression;
			}
		}
	}
	
	private function _getLanguages() {
		require_once dirname(__FILE__).'/GetLanguageAction.php';
		$action = new GetPostLanguageAction();
		$langs = $action->dispatch();
		return array_keys($langs);
	}

	private function _getForm() {
		$form = array();
		$form['use_lang'] = @$this->getParameter('use_lang');
		$form['answer'] = @$this->getParameter('answer');
		$form['id'] = @$this->getParameter('id');
		$form['author_uname'] = @$this->getParameter('author_uname');
		return $form;
	}
	
	private function _makeA($form) {
		$answer = new ToolboxVO_QA_Answer();
		$exp = new ToolboxVO_Resource_Expression();
		$exp->language = $form['use_lang'];
		$exp->expression = $form['answer'];
		
		$langs = $this->_getLanguages();
		
		$answer->expression = array($exp);
		
		//log_info($langs);
		
		foreach($langs as $lang) {
			if ($lang == $form['use_lang']) continue;
			$exp = new ToolboxVO_Resource_Expression();
			//log_info($lang);
			$exp->language = $lang;
			$exp->expression = $this->_translate($form['answer'], $form['use_lang'], $lang);
			//log_info($exp);
			$answer->expression[] = $exp;
		}
		
		return $answer;
	}
	
	private function _translate($source, $sourceLang, $targetLang) {
		require_once dirname(__FILE__).'/../WebQA_TranslatorAdapter.php';
		$source = explode("\n", $source);
		
		$text = WebQA_TranslatorAdapter::translate($sourceLang, $targetLang, $source);
		
		if (!$text) return '';
		
		return implode("\n", $text);
	}
}

/**
 * <#if lang="ja">
 * 利用言語を外部からパラメータで設定できるようにする拡張
 * </#if>
 */
//class BBSClient_WebQaImpl extends BBSClient {
//	function __construct($moduleName, $useLang) {
//		parent::__construct($moduleName);
//		$this->m_selectedLanguage = $useLang;
//	}
//}
?>