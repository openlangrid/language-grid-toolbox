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
require_once dirname(__FILE__).'/language-manager.php';
require_once dirname(__FILE__).'/com-message.php';



/*
 * message wrapper for BBSClient::getAllMessages 
 */
class Com_MessageSimple {
	/*
	 * id, 
	 * topicId, 
	 * text
	 * 		expression[]
	 * 			{body, language}
	 * 		date
	 * 		creator
	 * replyMessageIds
	 * originalMessageId
	 * creator
	 * language
	 * date
	 */

	private $message;
	
	private $id;
	
	public function __construct($record = null) {
		$this -> message = $record;
		$this -> id = $record -> id;
	}
	
	public function _getBody($lang) {
		foreach($this -> message -> text -> expression as $ex) {
			if($ex -> language == $lang) return $ex -> body;
		}
		return null;
	}
	
	public function getId() {
		return $this -> id;
	}
	
	public function getTopicId() {
		return $this -> message -> topicId;
	}
	
	public function getTextForOriginal() {
		return $this -> _getBody($this -> getOriginalLanguage());
	}
	
	public function getTextForSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $this -> _getBody($this -> getSelectedLanguage());
	}
	
	public function getOriginalLanguage() {
		return $this -> message -> language;
	}
	
	public function getSelectedLanguage() {
		$languageManager = LanguageManager::getManager();
		return $languageManager->getSelectedLanguage();
	}
	
	public function getCreateDateAsFormatString() {
		return CommonUtil::formatTimestamp($this -> message -> date, _COM_DTFMT_YMDHI);
	}
	
	public function getReplyMessageIds() {
		return $this -> message -> replyMessageIds;
	}
		
	public function getParentMessage() {
		$parentId = $this->getParentMessageId();
		if ($parentId) {
			return Com_Message::findByMessageId($parentId);
		}	
	}
	
	public function getParentMessageId() {
		return $this -> originalMessageId;
	}
	
	public function toMessage() {
		$msg = Com_Message::findById($this -> id);
		$msg -> setReplyPostOrders($this -> getReplyPostOrders());
		return $msg;
	}
	
	
	private $replies;
	public function getReplies() {
		if($this -> replies) return $this -> replies;
		
		$results = array();
		$ids = $this -> getReplyMessageIds();
				
//		foreach(self::findAll(array('topicId' => $this -> getTopicId())) as $message) {
//			if(in_array($message -> getId(), $ids)) {
//				$results[] = new Com_MessageSimple($message);
//			}
//		}

		foreach($ids as $id) {
			$results[] = Com_Message::findById($id);
		}
		$this -> replies = $results;
		return $results;
	}
	
	public function getReplyPostOrders() {
		$results = array();
		foreach($this -> getReplies() as $msg)
			$results[] = $msg -> getPostOrder();
		return $results;
	}
	
	
	static public function findAll($options) {
		$results = array();
		
		$client = new BBSClient($GLOBALS['mydirname']);
		$responses = $client -> getAllMessages($options['topicId'], @$options['offset'], @$options['limit']);
		foreach($responses['contents'] as $message) {
			$results[] = new Com_MessageSimple($message); 
		}
		return $results;
	}
}
?>