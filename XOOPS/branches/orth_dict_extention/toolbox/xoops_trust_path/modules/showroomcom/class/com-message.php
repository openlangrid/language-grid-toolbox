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
require_once dirname(__FILE__).'/common_util.php';
require_once dirname(__FILE__).'/util/user.php';
require_once dirname(__FILE__).'/com-attach-content.php';
require_once dirname(__FILE__).'/language-manager.php';

/*
 * wrapper class for BBSClient#getMessage value 
 */
class Com_Message {
	
	/*** record definition *****************
	 * body[]
	 * 		mVars{}
	 * 			post_id
	 * 			language_code
	 * 			title
	 * 			description
	 * 			update_time
	 * m_bodyLoaded
	 * mVars{}
	 * 		post_id
	 * 		topic_id
	 * 		uid
	 * 		poster_id
	 * 		post_original_language
	 * 		post_time
	 * 		post_order
	 * 		reply_post_id
	 * 		delete_flag
	 * 		update_date
	 * 	mIsNew
	 * 	_mAllowType
	 */
	private $record;
	
	private $expressions;
	
	private $params;
	
	private $attachContent = null;
	
	public function __construct($record = null) {
		if($record) {
			$this -> record = $record;
			$this -> expressions = $record -> body;
		}
	}
	
	protected function _get($key) {
		return $this -> record -> mVars[$key]['value']; 
	}
	
	protected function _getBody($lang, $key = null) {
		foreach($this -> expressions as $ex) {
			if($ex -> mVars['language_code']['value'] == $lang) {
				return $key ? $ex -> mVars[$key]['value'] : $ex -> mVars;
			}
		}
		return null;
	}
	
	// --------- property access ----------------------------------
	public function getId() {
		return $this -> _get('post_id');
	}
	
	public function getTopicId() {
		return $this -> _get('topic_id');
	}
	
	public function getOriginalLanguage() {
		return $this -> _get('post_original_language');
	}
	
	public function getOriginalLanguageAsName() {
		return CommonUtil::toLanguageAsName($this -> _get('post_original_language')); 
	}
	
	public function getDescriptionForOriginal() {
		return $this -> _getBody($this -> getOriginalLanguage(), 'description');
	}
	
	public function getSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $languageManager->getSelectedLanguage();
	}
	
	public function getDescriptionForSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $this -> _getBody($languageManager->getSelectedLanguage(), 'description');
	}
	
	public function getDescriptionForLang($lang) {
		return $this -> _getBody($lang, 'description');
	}
	
	public function getCreateDateAsFormatString() {
		return CommonUtil::formatTimestamp($this -> _get('update_date'), _COM_DTFMT_YMDHI);
	}
	
	public function getPostOrder() {
		return $this -> _get('post_order');
	}
	
	public function hasParentMessage() {
		return !!$this->_get('reply_post_id'); 
	}
		
	public function getParentMessage() {
		$parentId = $this->_get('reply_post_id');
		if ($parentId) {
			return Com_Message::findByMessageId($parentId);
		}
	}
	
	public function getParentPostOrder() {
		return $this -> getParentMessage() -> getPostOrder();
	}
	
	public function isSelectedLanguageOriginal() {
		return $this -> getSelectedLanguage() == $this -> getOriginalLanguage();
	}
	
	public function isOwner($uid) {
		return $this -> _get('uid') == $uid;
	}
	
	public function canEdit() {
		return $this -> canEditOriginal()
			|| $this -> canEditTranslation()
			|| $this -> canDelete();
	}
	
	public function canEditOriginal() {
		return $this -> isSelectedLanguageOriginal() && $this -> isOwner(getLoginUserUID());
	}
	
	public function canEditTranslation() {
		return !$this -> isSelectedLanguageOriginal();
	}
	
	public function canDelete() {
		return $this -> isOwner(getLoginUserUID());		
	}
	
	public function isDeleted() {
		return $this -> _get('delete_flag');
	}
	
	public function hasContent() {
		if(is_null($this -> attachContent)) 
			$this -> attachContent = Com_Attach_Content::findByMessageId($this -> getId());
		return !is_null($this -> attachContent);
	}
	
	public function hasContentMarker() {
		return $this -> hasContent() && $this -> attachContent -> hasMarker();
	}
	
	public function isContentAvailable() {
		return $this -> hasContent() && !$this -> getContent() -> getDeleteFlag();
	}
	

	public function getContent() {
		if($this -> hasContent())
			return $this -> attachContent -> getContent();
	}
	
	public function getContentTitle() {
		return $this -> getContent() -> getContentTitle();
	}
	
	public function getContentId() {
		return $this -> getContent() -> getContentId();
	}
	
	public function getContentMarker() {
		if($this -> hasContentMarker())
			return $this -> attachContent -> getContentMarker();
	}
	
	
	protected $replyPostOrders = array();
	public function setReplyPostOrders($replyPostOrders) {
		$this -> replyPostOrders = $replyPostOrders;
	}
	
	public function hasReplies() {
		return count($this -> replyPostOrders) != 0; 
	}
	
	public function getReplyPostOrders() {
		return $this -> replyPostOrders;
	}
	
	
	private $user;
	public function getUser() {
		if(!$this -> user) $this -> user = new User($this -> _get('uid'));
		return $this -> user;
	}
	
	public function getUserName() {
		return $this -> getUser() -> getName();
	}
	
	public function getUserIcon() {
		return $this -> getUser() -> getIcon();
	}
	
	public function getUserId() {
		return $this -> getUser() -> getId();
	}
	
	// ------------ crud this object ----------------------------------------------
	public function insert() {
		if($this -> validateOnInsert()) {
			die();
		}

		$id = $this -> insertMessages();
		
		if(@$this -> params['contentId']) {
			$this -> insertAttachContent($id);	
		}
		
		$insertMsg = self::findById($id);
		
		$this -> __construct($insertMsg -> record);
	}
	
	public function insertMessages() {
		$bbsClient = new BBSClient($GLOBALS['mydirname']);
		$messageExpressions = $this -> messagesToExpressions();
		$post = $bbsClient->postMessage($this->params['topicId'], $messageExpressions, null, @$this->params['parentId']);
		return $post['contents'] -> id;
	}
	
	public function insertAttachContent($postId) {
		//set attachement content
		$attachContent = Com_Attach_Content::createFromParams(array(
			'post_id' => $postId,
			'content_id' => $this -> params['contentId'],
			'image_id' => null,
			'marker' => $this -> params['marker'])	 
		);

		$attachContent -> insert();
	}
	
	public function delete() {
		if($this -> validateOnDelete()) {
			die();
		}
		$client = new BBSClient($GLOBALS['mydirname']);
		
		// message delete
		$client->deleteMessage($this->getId());
		
		// delete attach content and it's marker.
		$comAttachContent = Com_Attach_Content::findByMessageId($this->getId());
		if($comAttachContent) {
			$comAttachContent -> delete();			
		}
	}
	
	public function updateAttributes($params) {
		$this -> params = $params;
		$this -> update();
	}
	
	public function update() {
		if($this -> validateOnUpdate()) {
			die();
		}

		$this -> updateMessages();
		
		if($this -> isSelectedLanguageOriginal()) {
			$this -> updateAttachContent();			
		}
	}
	
	protected function updateMessages() {
		$bbsClient = new BBSClient($GLOBALS['mydirname']);
		$messageExpressions = $this -> messagesToExpressions();
		$post = $bbsClient->editMessage($this->getId(), $messageExpressions);
		return $post['contents'] -> id;
	}
	
	public function updateAttachContent() {
		
		$attach = Com_Attach_Content::findByMessageId($this->getId());
		if($attach) {
			$attach -> delete();
		}
		
		if($this -> params['contentId'])
			$this -> insertAttachContent($this -> getId());

	}
	
	protected function messagesToExpressions() {
		$messageExpressions = array();
		
		foreach ($this -> params['message'] as $languageCode => $contents ) {
			$expression = new ToolboxVO_BBS_MessageExpression();
			$expression -> body = trim($contents);
			$expression -> language = $languageCode;
			$messageExpressions[] = $expression;
		}
		
		return $messageExpressions;
	}
	
	public function validateOnInsert() {

		foreach ( $this -> params['message'] as $languageCode => $contents ) {
			if(trim($contents) == "") return true;
		}
		
		if(!$this -> params['topicId']) return true;

		return false;
	}
	
	public function validateOnDelete() {
		if(!$this -> canDelete()) {
			return true; 
		}
		return false;
	}
	
	public function validateOnUpdate() {
		$isOriginal = @$this -> params['message'][$this -> getOriginalLanguage()];
		if($isOriginal && !$this -> canDelete()) {
			return true;
		}
		return false;
	}
	
	// ---------------- html helper ---------------------------------
	public function htmlBtnStyleClass() {
		return $this -> canEdit() ? "btn" : "btn-disable";
	}
	
	public function htmlQueryString() {
		return CommonUtil::toQueryString(array(
			"topicId" => $this -> getTopicId(),
			"messageId" => $this -> getId())
		);
	}
	
	public function htmlMessageBodyClass() {
		return $this->hasContent() || $this->hasReplies() ? 'ad-content' : '';
	}
	
	public function htmlLinkParentPostOrder() {
		$order = $this -> getParentPostOrder();
		return "<a href='#$order'>#$order</a>";
	}
	
	
	// ---------------- factory -------------------------------------
	static public function findById($messageId) {
		$client = new BBSClient($GLOBALS['mydirname']);
		$msg = new Com_Message($client -> getMessage($messageId));
		$msg -> id = $messageId;
		return $msg;
	}
	
	static public function findByMessageId($messageId) {
		return self::findById($messageId);
	}	

	static public function createFromParams($topicId, $params) {
		$newMessage = new Com_Message();
		$params['topicId'] = $topicId;
		$newMessage -> params = $params;
		return $newMessage;
	}
	
	// --------------- test util -----------------------------------
	static public function truncate() {
		$client = new BBSClient($GLOBALS['mydirname']);
		$msgs = $client -> getAllMessages(1);
		foreach($msgs['contents'] as $msg) {
			$client->deleteMessage($msg -> id);	
		}
	}
}
?>