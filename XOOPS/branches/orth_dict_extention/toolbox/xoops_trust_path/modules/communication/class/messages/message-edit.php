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

class MessageEdit {

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
	public function getMessages($xoopsTpl,$topicId,$messageId) {
		$xoopsTpl->assign('topicId', $topicId);
		$xoopsTpl->assign('messageId', $messageId);
		$xoopsTpl->assign('mod_url', XOOPS_URL.'/modules/'.$GLOBALS['mydirname']);
		//$xoopsTpl->assign('mydirpath', $mydirpath);
		
		require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
		require_once(XOOPS_TRUST_PATH.'/modules/'.$service->getModuleName().'/class/util/user.php');
		
		//$bbsClient = new BBSClient("forum");
		$bbsClient = new BBSClient(USE_TABLE_PREFIX);
		$languageManager = LanguageManager::getManager();
		
		// get message object
		$msg = $bbsClient->getMessage($messageId);
		// user name
		$user = new User($msg->mVars['uid']['value']);
		$message['language'] = $languageManager->getNameByTag($msg->mVars['post_original_language']['value']);
		$message['date'] = date("Y/m/d H:i",$msg->mVars['post_time']['value']);
		//$message['date'] = date("Y/m/d H:i",$msg->mVars['update_date']['value']);
		$message['name'] = $user->getName();
		$message['user_avatar'] = $user->getUserAvatar();
		$message['user_icon'] = $user->getIcon();
		$message['id'] = $messageId;
		
		$msgbody = $msg->body;
		foreach ($msgbody as $value){
			if($value->mVars['language_code']['value'] == $languageManager->getSelectedLanguage()){
				$message['body'] = $value->mVars['description']['value'];
				$message['language_code'] = $value->mVars['language_code']['value'];
			}
		}
		$xoopsTpl->assign('message', $message);
		
		$translatedMessageList = array();
		foreach ($msgbody as $value){
			if($value->mVars['language_code']['value'] != $languageManager->getSelectedLanguage()){
				$translatedMessage['body'] = $value->mVars['description']['value'];
				$translatedMessage['language'] = $languageManager->getNameByTag($value->mVars['language_code']['value']);
				$translatedMessage['language_code'] = $value->mVars['language_code']['value'];
				$translatedMessageList[$value->mVars['language_code']['value']] = $translatedMessage;
			}
		}
		$xoopsTpl->assign('translatedMessageList', $translatedMessageList);
	}
	
}


?>