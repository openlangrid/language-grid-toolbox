<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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

class PostAction extends Abstract_WebQABackendAction {

	public function PostAction() {

	}

	public function dispatch() {
		$context = array();
		$form = $this->_getForm();

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

		$categories = null;
		if (isset($form['category']) && !empty($form['category'])) {
			if(is_array($form['category'])){
				$categories = $form['category'];
			}else{
				$categories = array($form['category']);
			}
		}

		$qares = $qaclient->addRecord($name, $this->_makeQ($form), $this->_makeA($form), $categories);
		$id = $qares['contents']->id;
		PostLanguageAction::registerLanguage($form['use_lang'], $id);
		if (@$form['author_uname']) {
			PostAuthorAction::registerAuthor($form['author_uname'], $id);
		}
	}

	private function _getExpressionByUseLang($exps, $lang) {
		foreach ($exps as $exp) {
			if ($exp->language == $lang) {
				return $exp->expression;
			}
		}
	}

	private function _getForm() {
		$form = array();
		$form['use_lang'] = @$this->getParameter('use_lang');
		$form['title'] = @$this->getParameter('title');
		$form['question'] = @$this->getParameter('question');
		$form['category'] = @$this->getParameter('category');
		$form['author_uname'] = @$this->getParameter('author_uname');
		return $form;
	}

	private function _makeQ($params) {
		$question = new ToolboxVO_Resource_Expression();
		$question->language = $this->getParameter('use_lang');
		$question->expression = $this->getParameter('question');
		
		$langs = $this->_getLanguages();
		$return = array();
		$return[] = $question;
		foreach ($langs as $lang) {
			if ($lang == $question->language) continue;
			$q = new ToolboxVO_Resource_Expression();
			$q->language = $lang;
			$q->expression = $this->_translate($question->expression, $question->language, $lang);
			$return[] = $q;
		}

		return $return;
	}
	
	private function _getLanguages() {
		require_once dirname(__FILE__).'/GetLanguageAction.php';
		$action = new GetPostLanguageAction();
		$langs = $action->dispatch();
		return array_keys($langs);
	}

	private function _makeA($params) {
		return array();
		$answer = new ToolboxVO_Resource_Expression();
		$answer->language = '';
		$answer->expression = '';
		$answers = array($answer);

		return array($answers);
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