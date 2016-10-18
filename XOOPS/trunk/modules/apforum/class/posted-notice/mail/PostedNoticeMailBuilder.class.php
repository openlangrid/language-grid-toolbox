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
/* $Id: PostedNoticeMailBuilder.class.php 4540 2010-10-07 04:01:57Z uehara $ */

class PostedNoticeMailDirector {
	var $mBuilder;

	var $mUser;

	var $mXoopsConfig;

	var $mXoopsModuleConfig;

	var $mNoticeConfig;

	var $mBbsMessages;

	var $mBbsOther;

	var $mLanguageResource;

	function __construct($builder, $user, $xoopsConfig, $xoopsModuleConfig, $noticeConfig, $bbsMessages, $bbsOther, $languageResource) {
		$this->mBuilder = $builder;

		$this->mUser = $user;
		$this->mXoopsConfig =$xoopsConfig;
		$this->mXoopsModuleConfig = $xoopsModuleConfig;
		$this->mNoticeConfig = $noticeConfig;
		$this->mBbsMessages = $bbsMessages;
		$this->mBbsOther = $bbsOther;
		$this->mLanguageResource = $languageResource;
	}

	function contruct() {
		$this->mBuilder->setTemplate();
		$this->mBuilder->setToUsers($this->mUser);
		$this->mBuilder->setFromEmail($this->mXoopsConfig);
		$this->mBuilder->setSubject($this->mNoticeConfig, $this->mLanguageResource);
		$this->mBuilder->setBody($this->mNoticeConfig, $this->mBbsMessages, $this->mBbsOther, $this->mLanguageResource, $this->mXoopsModuleConfig);
	}
}

class PostedNoticeMailBuilder {
	var $mMailer;

	function __construct($language) {
//		$this->mMailer = getMailer();
		$this->mMailer = $this->getMailerByLanguage($language);
		$this->mMailer->useMail();
	}

	function setTemplate() {
		$this->mMailer->setTemplateDir(XOOPS_ROOT_PATH . '/modules/forum/class/posted-notice/mail/');
		$this->mMailer->setTemplate('posted-notice.mail.tpl');
	}

	function setToUsers($user) {
		$this->mMailer->setToUsers($user);
	}

	function setFromEmail($xoopsConfig) {
		$this->mMailer->setFromEmail($xoopsConfig['adminmail']);
		$this->mMailer->setFromName($xoopsConfig['sitename']);
	}

	function setSubject($noticeConfig, $languageResource) {
		$this->mMailer->setSubject($languageResource->getSubject($noticeConfig->get('language_code')));
	}

	function setBody($noticeConfig, $bbsMessages, $bbsOther, $languageResource, $xoopsModuleConfig) {
		$this->mMailer->assign('HEADER', $languageResource->getHeader($noticeConfig->get('language_code')));

		$body = '';
		foreach ($bbsMessages as $message) {
			$body .= $this->makeLine($noticeConfig->get('language_code'), $message, $bbsOther, $languageResource, $xoopsModuleConfig);
			$body .= PHP_EOL;
		}
		$this->mMailer->assign('BODY', $body);
	}

	private function makeLine($language, $message, $bbsOther, $languageResource, $xoopsModuleConfig) {
		$topic = $bbsOther['topicList'][$message->topicId];
		$forum = $bbsOther['forumList'][$topic->forumId];
		$category = $bbsOther['categoryList'][$forum->categoryId];

		$tmp = $languageResource->getBody($language);
		$tmp = str_replace('{CATEGORY}', $this->_getText($category, $language, 'title'), $tmp);
		$tmp = str_replace('{FORUM}', $this->_getText($forum, $language, 'title'), $tmp);
		$tmp = str_replace('{TOPIC}', $this->_getText($topic, $language, 'title'), $tmp);

		$text = $this->_getText($message, $language, 'body');
		$text = ereg_replace("\r|\n|\r\n", " ", $text);
		$len = $xoopsModuleConfig['notifyLimit'];
		$sText = mb_substr($text, 0, $len);

		$tmp = str_replace('{BODY}', $text, $tmp);
		$tmp = str_replace('{BODY_SHORT}', $sText, $tmp);

		$d = date($languageResource->getDateFormat($language), $message->date);

		$tmp = str_replace('{DATE}', $d, $tmp);
		$tmp = str_replace('{TOPIC_LINK}', $this->_getTopicLinkUrl($message, $topic), $tmp);
		$tmp = str_replace('{BR}', PHP_EOL, $tmp);

		return $tmp;
	}

	function getResult() {
		return $this->mMailer;
	}

	private function _getText($obj, $lang, $val) {
		foreach ($obj->text->expression as $exp) {
			if ($exp->language == $lang) {
				return $exp->$val;
			}
		}
		return '';
	}

	private function _getTopicLinkUrl($message, $topic) {
		$page = floor( ( $message->postOrder - 1 ) / 20 ) + 1;
		$url = XOOPS_URL.'/modules/'.USE_TABLE_PREFIX.'/?topicId=%d?page=%d#post-number-%d';
		return sprintf($url, $topic->id, $page, $message->postOrder);
	}

	/*
	 * @see /html/include/functions.php::getMailer()をHackして文字化け対策。
	 * noticeConfig->language_codeによりXoopsMailerを切り替える。
	 */
	private function getMailerByLanguage($language) {
		$ret = null;
	    require_once XOOPS_ROOT_PATH."/class/xoopsmailer.php";
	    require_once XOOPS_ROOT_PATH."/modules/cubeUtils/class/MultiLanguage.class.php";
	    $CUtil = new CubeUtil_MultiLanguage;
	    $languageName = $CUtil->getLangName($language);
	    if (!$languageName) {
	    	$languageName = 'english';
	    }
	    if ( file_exists(XOOPS_ROOT_PATH."/language/".$languageName."/xoopsmailerlocal.php") ) {
	        require_once XOOPS_ROOT_PATH."/language/".$languageName."/xoopsmailerlocal.php";
	        if ( class_exists("XoopsMailerLocal") ) {
	            $ret =& new XoopsMailerLocal();
				return $ret;
	        }
	    }
	    $ret = new XoopsMailer();
		return $ret;
	}

}


?>