<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
require_once( XOOPS_ROOT_PATH . '/api/class/client/BBSClient.class.php' );
require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
require_once dirname(__FILE__).'/../class/tag/Tag.class.php';
require_once dirname(__FILE__).'/../class/tag/adapter/TagBBSClientAdapter.class.php';

require_once dirname( __FILE__ ) . "/../class/attachedFile/AttachedFileManager.php";
require_once dirname( __FILE__ ) . "/../class/manager/group-manager.php";
require_once dirname( __FILE__ ) . "/../class/manager/category-manager.php";
require_once dirname( __FILE__ ) . "/../class/manager/forum-manager.php";
require_once dirname( __FILE__ ) . "/../class/manager/topic-manager.php";
require_once dirname( __FILE__ ) . "/../class/manager/post-manager.php";
require_once dirname(__FILE__).'/../class/permission/permission.php';
$attachedFileManager = new AttachedFileManager();
$groupManager = new GroupManager();
$categoryManager = new CategoryManager();
$forumManager = new ForumManager();
$topicManager = new TopicManager();
$postManager = new PostManager();


function getTopicPathArray($id, $type, $selectedLanguageTag) {
	$topicPathArray = array();
	$bbsClient = new BBSClient(USE_TABLE_PREFIX);
	if ($type == 'topic') {
		$topic = $bbsClient -> getTopic( $id );
		foreach ( $topic -> body as $body ) {
			if ($body -> mVars['language_code']['value'] == $selectedLanguageTag) {
				$topicPathArray[2] = array(
					'id' => $id,
					'value' => $body -> mVars['title']['value']
				);
			}
		}
		$id = $topic->mVars['forum_id']['value'];
		$type = 'forum';
	}
	if ($type == 'forum') {
		$forum = $bbsClient -> getForum( $id );
		foreach ( $forum -> body as $body ) {
			if ($body -> mVars['language_code']['value'] == $selectedLanguageTag) {
				$topicPathArray[1] = array(
					'id' => $id,
					'value' => $body -> mVars['title']['value']
				);
			}
		}
		$id = $forum->mVars['cat_id']['value'];
	}
	$category = $bbsClient -> getCategory( $id );
	foreach ( $category -> body as $body ) {
		if ($body -> mVars['language_code']['value'] == $selectedLanguageTag) {
			$topicPathArray[0] = array(
				'id' => $id,
				'value' => $body -> mVars['title']['value']
			);
		}
	}
	return $topicPathArray;
}



//未入力チェック
function no_input_validation($contents,$element_names,$selectedLanguageTag){
	global $toLanguages;
	$error_message = '';
	
	
	foreach($contents as $langTag => $content){
		if($selectedLanguageTag == $langTag){
			for($i=0;$i<count($content);$i++){
				if($content[$i]==""){
					$error_message .= str_replace("%s",$element_names[$i],_MD_D3FORUM_PREVIEW_NO_SOURCE)."\\n";
				}
			}
		}else{
			$lang_name = $toLanguages[$langTag];
			for($i=0;$i<count($content);$i++){
				if($content[$i]==""){
					$error_message .= str_replace("{0}",$lang_name,
						str_replace("{1}",$element_names[$i],_MD_D3FORUM_PREVIEW_NO_TRANSLATOIN_RESULT))."\\n";
				}
			}
		}
	}
	
	return $error_message;
}
//アクセス権限の共通な入力チェック
function permission_common_validation($permission){
	global $root;
	
	$login_user_group = $root->mContext->mXoopsUser->getGroups();
	$is_admin = $root->mContext->mXoopsUser->isAdmin();
	$error_message = '';
	if(!($is_admin||!is_null($permission)&&count(array_intersect($login_user_group,$permission))!=0)){
		$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_AUTH."\\n";
	}
	if(is_null($permission)){
		$error_message .= _MD_D3FORUM_PREVIEW_CHECKBOX_NO_CHECK.'\\n';
	}
	
	return $error_message;
}



function choice_all_attribute($groups,$permission){
	if($permission==1){
		$all='checked';
	}else{
		$disabled=0;
		foreach($groups as $group){
			$disabled+=$group["disabled"];
		}
		if($disabled!=0){
			$all='disabled';
		}else{
			$all='';
		}
	}
	return $all;
}

function choice_all_attrinbute_valid($all_attribute,$post_all=null){
	if('disabled'==$all_attribute){
		$all ='disabled';
	}else if(!is_null($post_all)&&$post_all=='all'){
		$all = 'checked';
	} else{
		$all ='';
	}
	
	return $all;
}




$bbsClient = new BBSClient(USE_TABLE_PREFIX);
$root = XCube_Root :: getSingleton();
$isAdmin = $root -> mContext -> mXoopsUser -> isAdmin();
$typeCode = $_POST['type_code']; 

$id = ( isset( $_POST["id"] ) ) ? $_POST["id"] : null;
$replyId = "";
$topicPathArray = array();
$selectedLanguageTag = $languageManager->getSelectedLanguage();

// switch($typeCode){
	// case 'category_create':
		
	// case 'category_edit': 
	// case 'forum_create':
	// case 'forum_edit':
	// case 'topic_create':
	// case 'topic_edit':
	// case 'post_create':
	// case 'post_edit':
	// case 'post_reply':
// }

// redirect_header( XOOPS_URL . '/modules/' . $mydirname . '/' . $redirectURL );
		// die();



// ページの閲覧リクエスト時
switch ( $typeCode ) {
	case 'category_create':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TH_CATEGORYTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_TH_CATEGORYDESC , 'contents' => array() , 'rows' => 6
				)
			); 
		
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groupManager->getGroupsByParentId($id,'category'),
			'all' => 'checked',
			'setting_permission'=>new Permission()
		);
		
		break;
	case 'category_edit':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TH_CATEGORYTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_TH_CATEGORYDESC , 'contents' => array() , 'rows' => 6
				)
			);
		$category = $bbsClient -> getCategory( $id );
		foreach ( $category -> body as $body ) {
			
			$parameters[0]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['title']['value'];
			$parameters[1]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['description']['value'];
		}
		$params = array('categoryId' => $id);
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groupManager->getGroupsById($id,'category'),
			'all' => $categoryManager->getAllUserPermission($id)==1?'checked':'',
			'setting_permission'=>new Permission($params)
		);
		$topicPathArray = getTopicPathArray($id, 'category', $selectedLanguageTag);
		break;
	case 'forum_create':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TH_FORUMTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_TH_FORUMDESC , 'contents' => array() , 'rows' => 6
				)
			);
		$groups=$groupManager->getGroupsByParentId($id,'forum');		
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groups,
			'all' => choice_all_attribute($groups,$categoryManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission()
		);
		$topicPathArray = getTopicPathArray($id, 'category', $selectedLanguageTag);
		break;
	case 'forum_edit':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TH_FORUMTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_TH_FORUMDESC , 'contents' => array() , 'rows' => 6
				)
			);
		$forum = $bbsClient -> getForum( $id );

		foreach ( $forum -> body as $body ) {
			$parameters[0]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['title']['value'];
			$parameters[1]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['description']['value'];
		}
		
		$groups=$groupManager->getGroupsById($id,'forum');
		$params = array('forumId'=>$id);
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groups,
			'all' => choice_all_attribute($groups,$forumManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission($params),
		);
		$topicPathArray = getTopicPathArray($id, 'forum', $selectedLanguageTag);
		break;
	case 'topic_create':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TOPICTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6
				)
			);
		$limitNumber = $root -> mContext -> mModuleConfig['file_limit_number'];
		if ($limitNumber == '') {
			$limitNumber = 1000000;
		}
		
		$groups=$groupManager->getGroupsByParentId($id,'topic');
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' =>$groups ,
			'all' => choice_all_attribute($groups,$forumManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission()
		);

		$topicPathArray = getTopicPathArray($id, 'forum', $selectedLanguageTag);
		$xoopsTpl -> assign( array( 'FileCountLimit' => $limitNumber, 'FileListCount' => 0 ) );

		$tag = new Tag();
		$xoopsTpl -> assign ('tagResource', json_encode($tag->loadTag()));
		$xoopsTpl -> assign ('showTagBox', 'yes');
		break;
	case 'topic_edit':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TOPICTITLE , 'contents' => array() , 'rows' => 1
				)
			);
		$topic = $bbsClient -> getTopic( $id );
		// if ( !( $isAdmin || $topic -> mVars['uid']['value'] == $root -> mContext -> mXoopsUser -> get( 'uid' ) ) ) {
			// die();
		// }

		foreach ( $topic -> body as $body ) {
			$parameters[0]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['title']['value'];
		}
		$groups = $groupManager->getGroupsById($id,'topic');
		$params = array('topicId'=>$id);
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groups,
			'all' => choice_all_attribute($groups,$topicManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission($params)
		);

		$topicPathArray = getTopicPathArray($id, 'topic', $selectedLanguageTag);

		break;
	case 'post_create':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6
				)
			);
		$parameters[0]['contents'][$languageManager -> getSelectedLanguage()] = $_POST['message'];
		// var FileCountLimit=<{$FileCountLimit}>;
		$limitNumber = $root -> mContext -> mModuleConfig['file_limit_number'];
		if ($limitNumber == '') {
			$limitNumber = 1000000;
		}
		$xoopsTpl -> assign( array( 'FileCountLimit' => $limitNumber, 'FileListCount' => 0 ) );
		$groups = $groupManager->getGroupsByParentId($id,'post');
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groups,
			'all' => choice_all_attribute($groups,$topicManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission()
		);

		$topicPathArray = getTopicPathArray($id, 'topic', $selectedLanguageTag);

		$tag = new Tag();
		$xoopsTpl -> assign ('tagResource', json_encode($tag->loadTag()));
		$xoopsTpl -> assign ('showTagBox', 'yes');
		break;
	case 'post_edit':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6
				)
			);
		$message = $bbsClient -> getMessage( $id );
		if ( !( $isAdmin || $message -> mVars['uid']['value'] == $root -> mContext -> mXoopsUser -> get( 'uid' ) ) ) {
			die();
		}
		foreach ( $message -> body as $body ) {
			$parameters[0]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['description']['value'];
		}

		$FileList = $attachedFileManager -> getFileRecord( $id );
		$limitNumber = $root -> mContext -> mModuleConfig['file_limit_number'];
		if ($limitNumber == '') {
			$limitNumber = 1000000;
		}
		$listCount = $attachedFileManager -> getFileCount( $id );
		$xoopsTpl -> assign( array( 'FileList' => $FileList, 'FileCountLimit' => $limitNumber, 'FileListCount' => $listCount ) );
		
		$type = ($message->mVars['reply_post_id']['value']==0)?'post':'reply';
		
		$groups = $groupManager->getGroupsById($id,$type);
		$params = array('postId'=>$id);
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groups,
			'all' => choice_all_attribute($groups,$postManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission($params)
		);
		
		$topicPathArray = getTopicPathArray($message -> mVars['topic_id']['value'], 'topic', $selectedLanguageTag);

		$tag = new Tag();
		$xoopsTpl -> assign ('tagResource', json_encode($tag->loadTag()));
		$xoopsTpl -> assign ('showTagBox', 'yes');

		$tagClient = new TagBBSClientAdapter();
		$bindTags = $tagClient->getBindTags($id);
		if ($bindTags != null && is_array($bindTags)) {
			$selectedTags = array();
			foreach ($bindTags as $bindTag) {
				$selectedTags[] = $bindTag['tag_id'];
			}
			$xoopsTpl->assign('tagSelected', json_encode($selectedTags));
		}

		break;
	case 'post_reply':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6
				)
			);
		$isCitation = false;
		$message = $bbsClient -> getMessage( $id );
		if($isCitation){
			foreach ( $message -> body as $body ) {
				if ( $body -> mVars['language_code']['value'] == $languageManager -> getSelectedLanguage() ) {
					$replys = explode( "\n", $body -> mVars['description']['value'] );
					for ( $i = 0, $length = count( $replys ); $i < $length; $i++ ) {
						$replys[$i] = '>' . $replys[$i];
					}
					$parameters[0]['contents'][$body -> mVars['language_code']['value']] = implode( "\n", $replys ) . "\n";
				}
			}
		}
		$replyId = $id;
//		$id = $message -> mVars['topic_id']['value'];
		$limitNumber = $root -> mContext -> mModuleConfig['file_limit_number'];
		if ($limitNumber == '') {
			$limitNumber = 1000000;
		}
		$xoopsTpl -> assign( array( 'FileCountLimit' => $limitNumber, 'FileListCount' => 0 ) );
		
		$groups=$groupManager->getGroupsByParentId($id,'reply');
		$parameters_auth = array(
			'header' => _MD_D3FORUM_TH_AUTH ,
			'contents' => $groups,
			'all' => choice_all_attribute($groups,$postManager->getAllUserPermission($id)),
			'setting_permission'=>new Permission()
		);

		$topicPathArray = getTopicPathArray($message -> mVars['topic_id']['value'], 'topic', $selectedLanguageTag);

		$tag = new Tag();
		$xoopsTpl -> assign ('tagResource', json_encode($tag->loadTag()));
		$xoopsTpl -> assign ('showTagBox', 'yes');
		break;
	default:
		die();
		break;
}
// データが送信されてきた場合の処理
if ( isset( $_POST['phaze'] ) && $_POST['phaze'] == 'post' ) {


	if($_SESSION['forum_auth_token']===$_POST['t']){
		unset($_SESSION['forum_auth_token']);
	}else{
		redirect_header( XOOPS_URL . '/modules/' . $mydirname );
		die();
	}
	

/* 	$user_group_ids=is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
	$post_group_ids=$_POST['groupIds'];
	$check_array =  array_diff($post_group_ids,$user_group_ids);
	
	if(!is_array($post_group_ids)||is_array($post_group_ids)&&count($check_array)!=0){
	
		redirect_header( XOOPS_URL . '/modules/' . $mydirname );
		die();
	} */
	// necessary condition
	// $necessaryCondition = array( 'category_create' => array( 'admin' => false
			// ),
		// 'category_edit' => array( 'admin' => false
			// ),
		// 'forum_create' => array( 'admin' => false
			// ),
		// 'forum_edit' => array( 'admin' => false
			// ),
		// 'topic_create' => array(
			// ),
		// 'topic_edit' => array( 'owner' => true
			// ),
		// 'post_create' => array(
			// ),
		// 'post_edit' => array( 'owner' => true
			// )
		// );

	// if ( $necessaryCondition[$typeCode]['admin'] && !$isAdmin ) {
		// die();
	// }

	$expressions = array();
	$resultSetFileRecord = null;
	
	//$error_message = post_validation($typeCode,$_POST['contents'],isset($_POST['authGroup'])?$_POST['authGroup']:null,$selectedLanguageTag,$id);		
	
	$l_uid = $xoopsUser->getVar('uid');
	$post_permission = isset($_POST["authGroup"])?$_POST["authGroup"]:array();
	$error_message = '';
	switch ( $typeCode ) {
		case 'category_create':
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_TH_CATEGORYTITLE,_MD_D3FORUM_TH_CATEGORYDESC),$selectedLanguageTag);
			$error_message .= permission_common_validation($_POST['authGroup']);
			if($error_message == ''){
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = &new ToolboxVO_BBS_CategoryExpression();
					$expression -> title = $contents[0];
					$expression -> description = $contents[1];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}
				
				$category = $bbsClient -> createCategory( $expressions );
				//排他制御の為に書き直す必要あり（他の部分含む）
				$categoryManager->insertCategoryUser($category['contents'] -> id,$l_uid);
				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$categoryManager->SetAllUserPermission($category['contents'] -> id,1);
				}else{
					$categoryManager->SetAllUserPermission($category['contents'] -> id,0);
					$categoryManager->createCategoryAuth($category['contents'] -> id, $_POST['authGroup']);
				}
				$redirectURL = '?categoryId=' . $category['contents'] -> id;
			
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_TH_CATEGORYTITLE , 'contents' => array() , 'rows' => 1),
					array( 'header' => _MD_D3FORUM_TH_CATEGORYDESC , 'contents' => array() , 'rows' => 6)
				);
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
			
					$parameters[0]['contents'][$languageCode] = $contents[0];
					$parameters[1]['contents'][$languageCode] = $contents[1];
				}
		
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($id,'category',$post_permission),
					'all' => (isset($_POST["all_user"])&&$_POST["all_user"]=='all')?'checked':'',
					'setting_permission'=>new Permission()
				);
			}
			break;
		case 'category_edit':
			$params = array('categoryId' => $id);
			$permission = new Permission($params);
			
			if(!$permission->categoryEdit()){
				$redirectURL = '';
				break;
			}
			
			$uid = $categoryManager->getCategory($id)->getUser()->getId();
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_TH_CATEGORYTITLE,_MD_D3FORUM_TH_CATEGORYDESC),$selectedLanguageTag);
			if($isAdmin||$l_uid == $uid){
				$error_message .= permission_common_validation($_POST['authGroup']);

				if(count($categoryManager->getChildIds($id))!=0&&count(array_diff($groupManager->getGroupsByChildren($id,'category'),$post_permission))!=0){
					$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_CHILD_AUTH."\\n";
				}
			}
			
			if($error_message == ''){
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_CategoryExpression();
					$expression -> title = $contents[0];
					$expression -> description = $contents[1];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}
				$category = $bbsClient -> editCategory( $id, $expressions );
				$categoryManager->insertCategoryUser($id,$uid);
				if($permission->settingPermission('category_edit')){
					if(isset($_POST['all_user'])&&$_POST['all_user']=='all'){
						$categoryManager->deleteCategoryAuth($category['contents'] -> id);
						$categoryManager->SetAllUserPermission($category['contents'] -> id,1);
					}else{
						$categoryManager->SetAllUserPermission($category['contents'] -> id,0);
						$categoryManager->modifyCategoryAuth($category['contents'] -> id, $_POST['authGroup']);
					}
				}

				$redirectURL = '?categoryId=' . $category['contents'] -> id;
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_TH_CATEGORYTITLE , 'contents' => array() , 'rows' => 1),
					array( 'header' => _MD_D3FORUM_TH_CATEGORYDESC , 'contents' => array() , 'rows' => 6)
				);
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
			
					$parameters[0]['contents'][$languageCode] = $contents[0];
					$parameters[1]['contents'][$languageCode] = $contents[1];
				}
				
				
				$params = array('categoryId' => $id);		
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($id,'category',isset($_POST['authGroup'])?$_POST['authGroup']:array()),
					'all' => (isset($_POST["all_user"])&&$_POST["all_user"]=='all')?'checked':'',
					'setting_permission'=>$permission
				);
			}
			break;
		case 'forum_create':
			$params = array('categoryId' =>$id);
			$permission = new Permission($params);
			if(!$permission->forumCreate()){
				$redirectURL = '';
				break;
			}
			
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_TH_FORUMTITLE,_MD_D3FORUM_TH_FORUMDESC),$selectedLanguageTag);
			$error_message .= permission_common_validation($_POST['authGroup']);
			$category_permission = $categoryManager->getCategoryAuth($id);
			if(!is_null($_POST['authGroup'])&&count(array_diff($_POST['authGroup'],$category_permission)) !=0){
				$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
			}
			$all_attribute =choice_all_attribute($groupManager->getGroupsByParentId($id,'forum'),$categoryManager->getAllUserPermission($id));
			if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
				$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
			}
			
			if($error_message==''){
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_ForumExpression();
					$expression -> title = $contents[0];
					$expression -> description = $contents[1];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}
				$forum = $bbsClient -> createForum( $id, $expressions );
				
				
				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$forumManager->SetAllUserPermission($forum['contents'] -> id,1);
				}else{
					$forumManager->SetAllUserPermission($forum['contents'] -> id,0);
					$forumManager->createForumAuth($forum['contents'] -> id, $_POST['authGroup']);
				}
				$redirectURL = '?forumId=' . $forum['contents'] -> id;
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_TH_FORUMTITLE , 'contents' => array() , 'rows' => 1),
					array( 'header' => _MD_D3FORUM_TH_FORUMDESC , 'contents' => array() , 'rows' => 6)
				);
				
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$parameters[0]['contents'][$languageCode] = $contents[0];
					$parameters[1]['contents'][$languageCode] = $contents[1];
				}
/* 				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				}else if('disabled'==$all_attribute){
					$all ='disabled';
				}else{
					$all ='';
				} */
				
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($id,'forum',isset($_POST['authGroup'])?$_POST['authGroup']:array()),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>$permission,
				);
			}
			break;
		case 'forum_edit':
			$params = array('forumId' => $id);
			$permission = new Permission($params);
			if(!$permission->forumEdit()){
				$redirectURL = '';
				break;
			}
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_TH_FORUMTITLE,_MD_D3FORUM_TH_FORUMDESC),$selectedLanguageTag);
			$uid = $forumManager->getForum($id)->getUser()->getId();
			$all_attribute =choice_all_attribute($groupManager->getGroupsById($id,'forum'),$forumManager->getAllUserPermission($id));
			if($isAdmin||$l_uid == $uid){
				$error_message .= permission_common_validation($_POST['authGroup']);
				$category_permission = $categoryManager->getCategoryAuth($forumManager->getParentId($id));
				if(count(array_diff($post_parmission,$category_permission)) !=0){
					$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
				}
				
				if(count($forumManager->getChildIds($id))!=0 && count(array_diff($groupManager->getGroupsByChildren($id,'forum'),$post_permission))!=0){
						$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_CHILD_AUTH."\\n";
				}
				
				
				if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
				}
			}

			if($error_message == ''){	
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_ForumExpression();
					$expression -> title = $contents[0];
					$expression -> description = $contents[1];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}
				$forum = $bbsClient -> editForum( $id, $expressions );

				if($permission->settingPermission('forum_edit')){
					if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
						$forumManager->deleteForumAuth($forum['contents'] -> id);
						$forumManager->SetAllUserPermission($forum['contents'] -> id,1);
					}else{
						$forumManager->SetAllUserPermission($forum['contents'] -> id,0);
						$forumManager->modifyForumAuth($forum['contents'] -> id, $_POST['authGroup']);
					}
					
				}
				$redirectURL = '?forumId=' . $forum['contents'] -> id;
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_TH_FORUMTITLE , 'contents' => array() , 'rows' => 1),
					array( 'header' => _MD_D3FORUM_TH_FORUMDESC , 'contents' => array() , 'rows' => 6)
				);
				
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$parameters[0]['contents'][$languageCode] = $contents[0];
					$parameters[1]['contents'][$languageCode] = $contents[1];
				}
				
/* 				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				}else if('disabled'==$all_attribute){
					$all ='disabled';
				}else{
					$all ='';
				} */
				
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($groupManager->getGroupsById($id,'forum'),'forum',isset($_POST['authGroup'])?$_POST['authGroup']:array()),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>$permission,
				);
			}
			break;
		case 'topic_create':
			$params = array('forumId' => $id);
			$permission = new Permission($params);
			if(!$permission->topicCreate()){
				$redirectURL = '';
				break;
			}
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_TOPICTITLE,_MD_D3FORUM_MESSAGE),$selectedLanguageTag);
			$error_message .= permission_common_validation($_POST['authGroup']);
			$forum_permission = $forumManager->getForumAuth($id);
			if(!is_null($forum_permission)&&!is_null($_POST['authGroup'])&&count(array_diff($_POST['authGroup'],$forum_permission)) !=0){
				$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
			}
			$all_attribute =choice_all_attribute($groupManager->getGroupsByParentId($id,'topic'),$forumManager->getAllUserPermission($id));
			if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
				$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
			}
			
			$resultSetFileRecord=$attachedFileManager->validateFile();
			if($error_message==''&&!is_array($resultSetFileRecord)){
				$messageExpressions = array();
				
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_TopicExpression();
					$expression -> title = $contents[0];
					$expression -> language = $languageCode;
					$expressions[] = $expression;

					$expression = new ToolboxVO_BBS_MessageExpression();
					$expression -> body = $contents[1];
					$expression -> language = $languageCode;
					$messageExpressions[] = $expression;
				}
				
				$topic = $bbsClient -> createTopic( $id, $expressions );
				$message = $bbsClient -> postMessage( $topic['contents'] -> id, $messageExpressions, null, null, Tag::getPostedTagIds());
				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$topicManager->SetAllUserPermission($topic['contents'] -> id,1);
					$postManager->CreateAllUserPermission($message['contents'] -> id,1);
				}else{
					$topicManager->SetAllUserPermission($topic['contents'] -> id,0);
					$postManager->CreateAllUserPermission($message['contents'] -> id,0);
					$topicManager->createTopicAuth($topic['contents'] -> id,$_POST['authGroup']); 
					$postManager->createPostAuth($message['contents'] -> id,$_POST['authGroup']);

				}
				$attachedFileManager -> SetFileRecord($message['contents'] -> id);
				$redirectURL = '?topicId=' . $topic['contents'] -> id;

				if ($message) {
					require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
					$postedNotice = new PostedNotice();
					$postedNotice->notifyPostedEachTime($message['contents'] -> id);
				}
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_TOPICTITLE , 'contents' => array() , 'rows' => 1),
					array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6)
				);
				
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$parameters[0]['contents'][$languageCode] = $contents[0];
					$parameters[1]['contents'][$languageCode] = $contents[1];
				}
				
				$limitNumber = $root -> mContext -> mModuleConfig['file_limit_number'];
				if ($limitNumber == '') {
					$limitNumber = 1000000;
				}
				
/* 				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				}else if('disabled'==$all_attribute){
					$all ='disabled';
				}else{
					$all ='';
				} */
				
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($id,'topic',isset($_POST['authGroup'])?$_POST['authGroup']:array()),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>$permission
				);

				$topicPathArray = getTopicPathArray($id, 'forum', $selectedLanguageTag);
				$xoopsTpl -> assign( array( 'FileCountLimit' => $limitNumber, 'FileListCount' => 0 ) );

				$tag = new Tag();
				$xoopsTpl -> assign ('tagResource', json_encode($tag->loadTag()));
				$xoopsTpl -> assign ('showTagBox', 'yes');
			}

			break;
		case 'topic_edit':
		
			$params = array('topicId' => $id);
			$permission = new Permission($params);
			if(!$permission->topicEdit()){
				$redirectURL = '';
				break;
			}
			$uid = $topicManager->getTopic($id)->getUid();
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_TOPICTITLE),$selectedLanguageTag);
			
			$all_attribute =choice_all_attribute($groupManager->getGroupsById($id,'topic'),$topicManager->getAllUserPermission($id));
			if($isAdmin || $l_uid == $uid){
				$error_message .= permission_common_validation($_POST['authGroup']);
				
				$forum_permission = $forumManager->getForumAuth($topicManager->getParentId($id));				
				if(!is_null($forum_permission)&&!is_null($_POST['authGroup'])&&count(array_diff($_POST['authGroup'],$forum_permission)) !=0){
					$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
				}
				
				if(is_null($_POST['authGroup'])||count(array_diff($groupManager->getGroupsByChildren($id,'topic'),$_POST['authGroup']))!=0){
					$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_CHILD_AUTH."\\n";
				}
				
				
				if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
				}
			}
			if($error_message==''){	
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_TopicExpression();
					$expression -> title = $contents[0];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}
				$topic = $bbsClient -> editTopic( $id, $expressions );
				if($permission->settingPermission('topic_edit')){
					if(isset($_POST['all_user'])&&$_POST['all_user']=='all'){
						$topicManager->deleteTopicAuth($topic['contents'] -> id);
						$topicManager->SetAllUserPermission($topic['contents'] -> id,1);
					}else{
						$topicManager->SetAllUserPermission($topic['contents'] -> id,0);
						$topicManager->modifyTopicAuth($topic['contents'] -> id,$_POST['authGroup']);
					}
				}
				$redirectURL = '?topicId=' . $topic['contents'] -> id;
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_TOPICTITLE , 'contents' => array() , 'rows' => 1)
				);
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$parameters[0]['contents'][$languageCode] = $contents[0];
				}
				
/* 				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				}else if('disabled'==$all_attribute){
					$all ='disabled';
				}else{
					$all ='';
				} */
				
				$params = array('topicId'=>$id);
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($topicManager->getParentId($id),'topic',isset($_POST['authGroup'])?$_POST['authGroup']:array()),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>$permission
				);

				$topicPathArray = getTopicPathArray($id, 'topic', $selectedLanguageTag);
				
			}
			break;
		case 'post_create':
			$params = array('topicId' => $id);
			$permission = new Permission($params);
			if(!$permission->postCreate()){			
				$redirectURL = '';
				break;
			}
			$resultSetFileRecord=$attachedFileManager->validateFile();
			
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_MESSAGE),$selectedLanguageTag);
			$error_message .= permission_common_validation($_POST['authGroup']);
			$topic_permission = $topicManager->getTopicAuth($id);
			if(!is_null($topic_permission)&&!is_null($_POST['authGroup'])&&count(array_diff($_POST['authGroup'],$topic_permission)) !=0){
				$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
			}
			$all_attribute =choice_all_attribute($groupManager->getGroupsByParentId($id,'post'),$topicManager->getAllUserPermission($id));
			if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
				$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
			}
			if($error_message == ''&&!is_array($resultSetFileRecord)){
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_MessageExpression();
					$expression -> body = $contents[0];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}

				$message = $bbsClient -> postMessage( $id, $expressions ,null,null, Tag::getPostedTagIds());
				$post = $postManager -> getPost( $message['contents'] -> id );
				
				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$postManager->CreateAllUserPermission($message['contents'] -> id,1);
				}else{
					$postManager->CreateAllUserPermission($message['contents'] -> id,0);
					$postManager->createPostAuth($message['contents'] -> id,$_POST['authGroup']);				
				}
				$page = floor( ( $post -> getNumber()-1 ) / 20 ) + 1;
				$redirectURL = '?topicId=' . $message['contents'] -> topicId . '&page=' . $page . '#post-number-' . $post -> getNumber();
				$attachedFileManager -> SetFileRecord($message['contents'] -> id);

				if ($message) {
					require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
					$postedNotice = new PostedNotice();
					$postedNotice->notifyPostedEachTime($message['contents'] -> id);
				}
				
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6)
				);
				foreach($_POST['contents'] as $languageCode => $contents){
					$parameters[0]['contents'][$languageCode] = $contents[0];
				}
				
/* 				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				}else if('disabled'==$all_attribute){
					$all ='disabled';
				}else{
					$all ='';
				} */
				
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($id,'post',$_POST['authGroup']),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>new Permission()
				);

			}
			break;
		case 'post_reply':
			$tid = $message -> mVars['topic_id']['value'];
			$params = array('topicId' => $tid);
			$permission = new Permission($params);

			if(!$permission->postCreate()){				
				$redirectURL ='';
				break;
			}

			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_MESSAGE),$selectedLanguageTag);
			$error_message .= permission_common_validation($_POST['authGroup']);
						
			$topic_permission = $topicManager->getTopicAuth($tid);
			if(!is_null($topic_permission)&&!is_null($_POST['authGroup'])&&count(array_diff($_POST['authGroup'],$topic_permission)) !=0){
				$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
			}
			
			$all_attribute =choice_all_attribute($groupManager->getGroupsByParentId($id,'reply'),$postManager->getAllUserPermission($id));
			if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
				$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
			}
			$resultSetFileRecord = $attachedFileManager->validateFile();
			if($error_message=='' && !is_array($resultSetFileRecord)){
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_MessageExpression();
					$expression -> body = $contents[0];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}

				if(is_numeric($_POST['reply_id'])){
					$parent_id = intval($_POST['reply_id']);
				}else{
					$parent_id = null;
				}

				$message = $bbsClient -> postMessage( $tid, $expressions ,null,$parent_id, Tag::getPostedTagIds());
				$post = $postManager -> getPost( $message['contents'] -> id );
				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$postManager->CreateAllUserPermission($message['contents'] -> id,1);
				}else{
					$postManager->CreateAllUserPermission($message['contents'] -> id,0);
					$postManager->createPostAuth($message['contents'] -> id,$_POST['authGroup']);
				}			
				$page = floor( ( $post -> getNumber()-1 ) / 20 ) + 1;
				$redirectURL = '?topicId=' . $message['contents'] -> topicId . '&page=' . $page . '#post-number-' . $post -> getNumber();
				$attachedFileManager -> SetFileRecord( $message['contents'] -> id );
				
				if ($message) {
					require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
					$postedNotice = new PostedNotice();
					$postedNotice->notifyPostedEachTime($message['contents']->id);
				}
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6)
				);
				foreach($_POST['contents'] as $languageCode => $contents){
					$parameters[0]['contents'][$languageCode] = $contents[0];
				}
				
/* 				if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				}else if('disabled'==$all_attribute){
					$all ='disabled';
				}else{
					$all ='';
				} */
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($tid,'post',$_POST['authGroup']),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>new Permission()
				);
			}
			break;
		case 'post_edit':
			
			$params = array('postId' => $id);
			$permission = new Permission($params);
			if(!$permission->postEdit()){
				$redirectURL ='';
				break;
			}
			
			$error_message .= no_input_validation($_POST['contents'],array(_MD_D3FORUM_MESSAGE),$selectedLanguageTag);
			$message = $bbsClient -> getMessage( $id );
			$type = ($message->mVars['reply_post_id']['value']==0)?'post':'reply';
			$all_attribute =choice_all_attribute($groupManager->getGroupsByParentId($id,$type),$postManager->getAllUserPermission($id));
			$uid = $message->mVars['uid']['value'];
			if($isAdmin||$l_uid == $uid){
				$error_message .= permission_common_validation($_POST['authGroup']);
				
				$tid = $postManager->getParentId($id);
				$topic_permission = $topicManager->getTopicAuth($tid);
				if(!is_null($topic_permission)&&!is_null($_POST['authGroup'])&&count(array_diff($_POST['authGroup'],$topic_permission)) !=0){
					$error_message .= _MD_D3FORUM_PREVIEW_NO_CHECK_PARENT_AUTH."\\n";
				}
				
			
				if("disabled"==$all_attribute&&isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$error_message .= _MD_D3FORUM_PREVIEW_CAN_NOT_ALL_GROUPS."\\n";
				}
			}
			$resultSetFileRecord = $attachedFileManager->validateFile();

			if($error_message==''&&!is_array($resultSetFileRecord)){
				foreach ( $_POST['contents'] as $languageCode => $contents ) {
					$expression = new ToolboxVO_BBS_MessageExpression();
					$expression -> body = $contents[0];
					$expression -> language = $languageCode;
					$expressions[] = $expression;
				}


				$message = $bbsClient -> editMessage( $id, $expressions );
				if($permission->settingPermission('post_edit')){
					if(isset($_POST['all_user'])&&$_POST['all_user']=='all'){
						$postManager->deletePostAuth($message['contents'] -> id);
						$postManager->SetAllUserPermission($message['contents'] -> id,1);
					}else{
						$postManager->SetAllUserPermission($message['contents'] -> id,0);
						$postManager ->modifyPostAuth($message['contents'] -> id,$_POST['authGroup']);
					}
				}
				$post = $postManager -> getPost( $message['contents'] -> id );
				
				$page = floor( ( $post -> getNumber()-1 ) / 20 ) + 1;
				$redirectURL = '?topicId=' . $message['contents'] -> topicId . '&page=' . $page . '#post-number-' . $post -> getNumber();
				$attachedFileManager -> SetFileRecord( $message['contents'] -> id );

				$bbsTagClient = new TagBBSClientAdapter();
				$bbsTagClient->updateTagRelation($id, Tag::getPostedTagIds());
			}else{
				$parameters = array(
					array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6)
				);
				foreach($_POST['contents'] as $languageCode => $contents){
					$parameters[0]['contents'][$languageCode] = $contents[0];
				}
/* 				 if('disabled'==$all_attribute){
					$all ='disabled';
				}else if(isset($_POST["all_user"])&&$_POST["all_user"]=='all'){
					$all = 'checked';
				} else{
					$all ='';
				} */
				
				$parameters_auth = array(
					'header' => _MD_D3FORUM_TH_AUTH ,
					'contents' => $groupManager->getGroupsBySelected($tid,$type,$_POST['authGroup']),
					'all' => choice_all_attrinbute_valid($all_attribute,$_POST["all_user"]),
					'setting_permission'=>$permission
				);
			}
			break;
		default:
			die();
			break;
	}

	//アップロードされたファイルに問題があれば、ページを戻す
	if ( is_array( $resultSetFileRecord ) ) {
/* 		$parameters = array(
			array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6
				)
			);
		foreach($expressions as $ex){
			$parameters[0]['contents'][$ex->language] = $ex->body;
		}

		$xoopsTpl -> assign( array( 'NotUploadedFiles' => $resultSetFileRecord ,'limitSize'=>$attachedFileManager->getFileLimitSize()) );
 */	
		$notExistFiles =array();
		$overLimitFiles = array();
		$limitSize = $attachedFileManager->getFileLimitSize();
		foreach($resultSetFileRecord as $file){
			if($file['fileSize']<=0){
				$notExistFiles[] = $file['fineName'];
			}else{
				$overLimitFiles[] = $file['fileName'];
			}
		}
		
		if($overLimitFiles) $error_message .= str_replace('{0}',$limitSize,_MD_D3FORUM_FILE_OVER_LIMIT).'\\n'.implode( '\\n', $overLimitFiles ).'\\n';
		if($notExistFiles) $error_message .= _MD_D3FORUM_FILE_NOT_EXIST.'\\n'.implode( '\\n', $notExistFiles );
	}
	if($error_message!=''){
		$xoopsTpl -> assign( array( 'error_message' => $error_message));
		$pageHistory =$_POST['history']-1;
	}else {
		redirect_header( XOOPS_URL . '/modules/' . $mydirname . '/' . $redirectURL );
		die();
	}
}

$xoopsOption['template_main'] = $mydirname . '_main_preview.html' ;
include XOOPS_ROOT_PATH . '/header.php' ;

$js = array( 'class/framework/validator',
	'class/request/request',
	'class/request/request-queue',
	'class/thread/thread',
	'class/translator/transport-wrapper',
	'class/translator/translator',
	'class/translator/sentence-translator',
	'class/panel/license-area',
	'class/panel/translation-panel',
	'class/panel/preview-main-panel',
	'class/auth/auth-preview',
	'class/auth/auth-child-preview',
	'class/auth/permission',
	'main/preview-main',
	'class/attachedFile/fileUpload',
	'class/panel/templates',
	'class/panel/panel',
	'class/panel/light-popup-panel',
	'class/attachedFile/FileList',
	'class/tag/tag',
	);

$xoops_module_header = '';
$xoops_module_header .= $xoopsTpl -> get_template_vars( "xoops_module_header" );
foreach ( $js as $jsPath ) {
	$xoops_module_header .= '<script charset="UTF-8" type="text/javascript" src="' . XOOPS_URL . '/modules/' . $mydirname . '/js/' . $jsPath . '.js"></script>' . "\n";
}

$xoops_module_header.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/langrid-setting-module.css' type='text/css' rel='stylesheet'>";
$xoops_module_header.="<link href='".XOOPS_URL.'/modules/'.$mydirname."/css/imported-services.css' type='text/css' rel='stylesheet'>";

if (count($topicPathArray)) {
	$topicPath = '<ol><li><a href="./">'._MD_D3FORUM_RETURN_COMMUNITY_TOP.'</a></li>'."\n";
//	foreach ($topicPathArray as $key => $value) {
	for ($i = 0; $i < count($topicPathArray); $i++) {
		$value = $topicPathArray[$i];
		switch ($i) {
			case 0:
				$location = 'categoryId';
				break;
			case 1:
				$location = 'forumId';
				break;
			case 2:
				$location = 'topicId';
				break;
//			case 3:
//				$location = 'postId';
//				break;
		}
		if ($key == 'mode' || count($topicPathArray) == ($i + 1)) {
			$topicPath .= '<li>'.$value['value'].'</li>'."\n";
		} else {
			$topicPath .= '<li><a href="./?'.$location.'='.$value['id'].'">'.$value['value'].'</a></li>'."\n";
		}
	}
	$topicPath .= '</ol>';
}

if(!$pageHistory){
	$pageHistory = -1;
}
$token = md5(uniqid(mt_rand()));
$_SESSION['forum_auth_token'] = $token; 
$type_array = explode('_',$typeCode);
$xoopsTpl -> assign( array( 'xoops_module_header' => $xoops_module_header,
		'topicPath' => $topicPath,
		'parameters' => $parameters,
		'parameters_auth' => $parameters_auth,
		'groupIds' => is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array(),
		'separator' => '_SEPARATOR_',
		'typeCode' => $typeCode,
		'replyId' => $replyId,
		'submit_token' => $token,
		'bbsPreviewId' => ( isset( $id ) ) ? $id : null,
		'pageHistory' => $pageHistory,
		'operation_type' => $type_array[1]
		) );

include XOOPS_ROOT_PATH . '/footer.php' ;


?>