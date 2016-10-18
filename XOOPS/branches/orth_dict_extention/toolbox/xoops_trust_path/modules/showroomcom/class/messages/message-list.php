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

require_once dirname(__FILE__).'/../../class/com-message.php';	
require_once dirname(__FILE__).'/../common_util.php';	

class MessageList {

	/**
	 *  
	 */
	public function __construct() {
		
	}
	
	/**
	 * 
	 * @param unknown_type $xoopsTpl
	 * @param unknown_type $topic_id
	 */
	public function getMessageTitle($topic_id) {
	
		// api
		$languageManager = LanguageManager::getManager();
		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
		
		$bbsClient = new BBSClient(USE_TABLE_PREFIX);
		
		// topic
		$topic = $bbsClient->getTopic($topic_id);
		$topicbody = $topic->body;
		$originalLang = CommonUtil::toLanguageAsName($topic->mVars['topic_original_language']['value']);
		// title
		foreach ($topicbody as $value){
			if($value->mVars['language_code']['value'] == $languageManager->getSelectedLanguage()){
				return $value->mVars['title']['value']."($originalLang)";
			}
		}
		
		return null;
	}
	
	/**
	 * 
	 * @param unknown_type $xoopsTpl
	 * @param unknown_type $topic_id
	 */
	public function getMessageList($xoopsTpl,$topic_id) {
	
		// api
		require_once dirname(__FILE__).'/../../class/com-content.php';	
		require_once dirname(__FILE__).'/../../class/com-content-list.php';
		require_once dirname(__FILE__).'/../../class/com-attach-content.php';
		require_once(XOOPS_TRUST_PATH.'/modules/'.$service->getModuleName().'/class/util/user.php');
		
		$languageManager = LanguageManager::getManager();
		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
		//$bbsClient = new BBSClient();
		$bbsClient = new BBSClient(USE_TABLE_PREFIX);
		$xoopsTpl->assign('topic_id',$topic_id);
		
		// get messages ----------
		
		// messages
		$messages = $bbsClient->getAllMessages($topic_id,0,1000);
		$messageContents = $messages['contents'];
		
		// time for message check
		$xoopsTpl->assign('checkTime',date("YmdHis",$messageContents[count($messageContents) - 1]->date));
		$xoopsTpl->assign('messageCount',count($messageContents));
		
		$messageList = array();
		
		foreach ($messageContents as $mcValue){
			$messageTexts = $mcValue->text;
			$messageExpressions = $messageTexts->expression; // post_body table
			// check content
			$comAttachContent = Com_Attach_Content::findByPostId($mcValue->id);
			$contentId = "";
			if($comAttachContent){
				$contentId = $comAttachContent -> getContentId();
			}
			
			$comContent = Com_Content::findById($comAttachContent -> getContentId());
			$deleteFlag  = 0;
			$contentName = "";
			if($comContent){
				$deleteFlag  = $comContent -> getDeleteFlag();
				$contentName = $comContent -> getContentTitle();				
			}
			
			// message
			foreach ($messageExpressions as $meValue){
				if($meValue->language == $languageManager->getSelectedLanguage()){
					
					// message
					$msg = $bbsClient->getMessage($mcValue->id);
					// user name
					$user = new User($msg->mVars['uid']['value']);

					$message['post_order'] = $msg->mVars['post_order']['value'];
					$message['body'] = $meValue->body;
					$message['language'] = $languageManager->getNameByTag($mcValue->language);
					$message['user'] = $user;
					
					$message['replyMessageIdCount'] = count($mcValue->replyMessageIds);
					$post_order_list = array();
					foreach($mcValue->replyMessageIds as $key => $replyitem){
						$replymsg = $bbsClient->getMessage($replyitem);
						$post_order_list[] = $replymsg->mVars['post_order']['value'];
					}
					$message['replyMessages'] = $post_order_list;

					$message['date'] = date("Y/m/d H:i",$mcValue->date);
					$message['name'] = $user->getName();
					$message['user_avatar'] = $user->getUserAvatar();
					$message['user_icon'] = $user->getIcon();
					$message['uid'] = $user -> getId();
					$message['id'] = $mcValue->id;

					$message['original'] = $this -> isOriginal($mcValue, $meValue);
					$message['loginUserIsOwner'] = getLoginUserUid() == $user -> getId();
					$message['editable'] = !$message['original'] || $message['loginUserIsOwner']; 

					$message['thisLang'] = $meValue -> language;
					$message['origLang'] = $mcValue -> language;
					
					$message['contentId']   = $contentId;
					$message['deleteFlag']  = $deleteFlag;
					$message['contentName'] = $contentName;
					
					$message['comMessage'] = Com_Message::findByMessageId($mcValue->id); 
					$message['comParentMessage'] = $message['comMessage'] -> getParentMessage(); 

					array_push($messageList,$message);
				}
			}
		}
		
		$xoopsTpl->assign('messageList',$messageList);

	}
	
	private function isOriginal($messageContent, $messageExpression) {
		return $messageContent->language == $messageExpression->language;
	}
	
	
	/**
	 * 
	 * @param $topic_id
	 * @return text
	 */
	public function checkMessageTime($topic_id) {
		
		// api
		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
		//$bbsClient = new BBSClient();
		$bbsClient = new BBSClient(USE_TABLE_PREFIX);
		
		// topic
		$messages = $bbsClient->getAllMessages($topic_id,0,1000);
		$messageContents = $messages['contents'];
		
		return date("YmdHis",$messageContents[count($messageContents) - 1]->date);
	}
	
	/**
	 * 
	 * @param $topic_id
	 * @return text
	 */
	public function countMessage($topic_id) {
		
		// api
		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
		//$bbsClient = new BBSClient();
		$bbsClient = new BBSClient(USE_TABLE_PREFIX);
		
		// topic
		$messages = $bbsClient->getAllMessages($topic_id,0,1000);
		$messageContents = $messages['contents'];
		
		return count($messageContents);
	}
	
	/**
	 * 
	 * @param $topic_id
	 * @return text
	 */
	public function reloadMessage($topicId) {
		
		$ret = '';
		
		// api
		require_once dirname(__FILE__).'/../../class/com-content.php';	
		require_once dirname(__FILE__).'/../../class/com-content-list.php';
		require_once dirname(__FILE__).'/../../class/com-attach-content.php';
		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
		require_once(XOOPS_TRUST_PATH.'/modules/'.$service->getModuleName().'/class/util/user.php');
		
		//$bbsClient = new BBSClient();
		$bbsClient = new BBSClient(USE_TABLE_PREFIX);
		$languageManager = LanguageManager::getManager();
		
		// messages
		// TODO topic_id
		$messages = $bbsClient->getAllMessages($topicId,0,1000);
		$messageContents = $messages['contents'];
		
		$messageList = array();
		
		foreach ($messageContents as $mcValue){
			$messageTexts = $mcValue->text;
			$messageExpressions = $messageTexts->expression;
			// check content
			$comAttachContent = Com_Attach_Content::findByPostId($mcValue->id);
			$contentId = "";
			if($comAttachContent){
				$contentId = $comAttachContent -> getContentId();
			}
			$comContent = Com_Content::findById($comAttachContent -> getContentId());
			$deleteFlag = 0;
			$contentName = "";
			if($comContent){
				$deleteFlag  = $comContent -> getDeleteFlag();
				$contentName = $comContent -> getContentTitle();
			}
			
			// message
			foreach ($messageExpressions as $meValue){
				if($meValue->language == $languageManager->getSelectedLanguage()){
					
					// message
					$msg = $bbsClient->getMessage($mcValue->id);
					// user name
					$user = new User($msg->mVars['uid']['value']);
					$message['user'] = $user;
					
					$message['post_order'] = $msg->mVars['post_order']['value'];
					$message['body'] = $meValue->body;
					$message['language'] = $languageManager->getNameByTag($mcValue->language);
					
					$message['replyMessageIdCount'] = count($mcValue->replyMessageIds);
					$post_order_list = array();
					foreach($mcValue->replyMessageIds as $key => $replyitem){
						$replymsg = $bbsClient->getMessage($replyitem);
						$post_order_list[] = $replymsg->mVars['post_order']['value'];
					}
					$message['replyMessages'] = $post_order_list;
					
					$message['date'] = date("Y/m/d H:i",$mcValue->date);
					$message['name'] = $user->getName();
					$message['user_avatar'] = $user->getUserAvatar();
					$message['user_icon'] = $user->getIcon();
					$message['uid'] = $user -> getId();
					$message['id'] = $mcValue->id;
					
					$message['loginUserIsOwner'] =  (getLoginUserUid() == $user -> getId())? 'true' : 'false';
					
					$message['original'] = $this -> isOriginal($mcValue, $meValue);
					$message['editable'] = XCube_Root::getSingleton()->mContext->mXoopsUser->get('uid') == $user -> getId();

					$message['thisLang'] = $meValue -> language;
					$message['origLang'] = $mcValue -> language;
					
					$message['contentId'] = $contentId;
					$message['deleteFlag'] = $deleteFlag;
					$message['contentName'] = $contentName;
					
					array_push($messageList,$message);
				}
			}
		}
		return $messageList;
	}

	/**
	 * 
	 * @param unknown_type $xoopsTpl
	 * @param unknown_type $action
	 */
	public function setEditMode($xoopsTpl,$action) {
		if($action == "edit" || $action == "translate" || $action == "new"){
			$xoopsTpl->assign('editMode',"none");
		}else{
			$xoopsTpl->assign('editMode',"");
		}
	}
	
}


?>