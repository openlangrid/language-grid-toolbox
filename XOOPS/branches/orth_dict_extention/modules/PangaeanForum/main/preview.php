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

$bbsClient = new BBSClient(USE_TABLE_PREFIX);
$root = XCube_Root :: getSingleton();
$isAdmin = $root -> mContext -> mXoopsUser -> isAdmin();
$typeCode = $_POST['type_code'];

$id = ( isset( $_POST["id"] ) ) ? $_POST["id"] : null;
$replyId = "";
$topicPathArray = array();
$selectedLanguageTag = $languageManager->getSelectedLanguage();

require_once dirname( __FILE__ ) . "/../class/attachedFile/AttachedFileManager.php";
$attachedFileManager = new AttachedFileManager();
// ページの閲覧リクエスト時
switch ( $typeCode ) {
	case 'category_create':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TH_CATEGORYTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_TH_CATEGORYDESC , 'contents' => array() , 'rows' => 6
				)
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
		$topicPathArray = getTopicPathArray($id, 'category', $selectedLanguageTag);
		break;
	case 'forum_create':
		$parameters = array(
			array( 'header' => _MD_D3FORUM_TH_FORUMTITLE , 'contents' => array() , 'rows' => 1
				),
			array( 'header' => _MD_D3FORUM_TH_FORUMDESC , 'contents' => array() , 'rows' => 6
				)
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

		if ( !( $isAdmin || $topic -> mVars['uid']['value'] == $root -> mContext -> mXoopsUser -> get( 'uid' ) ) ) {
			die();
		}

		foreach ( $topic -> body as $body ) {
			$parameters[0]['contents'][$body -> mVars['language_code']['value']] = $body -> mVars['title']['value'];
		}
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
	// necessary condition
	$necessaryCondition = array( 'category_create' => array( 'admin' => false
			),
		'category_edit' => array( 'admin' => false
			),
		'forum_create' => array( 'admin' => false
			),
		'forum_edit' => array( 'admin' => false
			),
		'topic_create' => array(
			),
		'topic_edit' => array( 'owner' => true
			),
		'post_create' => array(
			),
		'post_edit' => array( 'owner' => true
			)
		);

	if ( $necessaryCondition[$typeCode]['admin'] && !$isAdmin ) {
		die();
	}

	$expressions = array();
	$resultSetFileRecord = null;

	switch ( $typeCode ) {
		case 'category_create':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = &new ToolboxVO_BBS_CategoryExpression();
				$expression -> title = $contents[0];
				$expression -> description = $contents[1];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}
			$category = $bbsClient -> createCategory( $expressions );
			$redirectURL = '?categoryId=' . $category['contents'] -> id;
			break;
		case 'category_edit':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_CategoryExpression();
				$expression -> title = $contents[0];
				$expression -> description = $contents[1];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}
			$category = $bbsClient -> editCategory( $id, $expressions );
			$redirectURL = '?categoryId=' . $category['contents'] -> id;
			break;
		case 'forum_create':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_ForumExpression();
				$expression -> title = $contents[0];
				$expression -> description = $contents[1];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}
			$forum = $bbsClient -> createForum( $id, $expressions );
			$redirectURL = '?forumId=' . $forum['contents'] -> id;
			break;
		case 'forum_edit':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_ForumExpression();
				$expression -> title = $contents[0];
				$expression -> description = $contents[1];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}
			$forum = $bbsClient -> editForum( $id, $expressions );
			$redirectURL = '?forumId=' . $forum['contents'] -> id;
			break;
		case 'topic_create':
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

			$resultSetFileRecord=$attachedFileManager->validateFile();
			if(!is_array($resultSetFileRecord)){
				$topic = $bbsClient -> createTopic( $id, $expressions );
				$message = $bbsClient -> postMessage( $topic['contents'] -> id, $messageExpressions, null, null, Tag::getPostedTagIds());
				$attachedFileManager -> SetFileRecord($message['contents'] -> id);
				$redirectURL = '?topicId=' . $topic['contents'] -> id;

				if ($message) {
					require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
					$postedNotice = new PostedNotice();
					$postedNotice->notifyPostedEachTime($message['contents'] -> id);
				}
			}
			break;
		case 'topic_edit':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_TopicExpression();
				$expression -> title = $contents[0];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}
			$topic = $bbsClient -> editTopic( $id, $expressions );
			$redirectURL = '?topicId=' . $topic['contents'] -> id;
			break;
		case 'post_create':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_MessageExpression();
				$expression -> body = $contents[0];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}

			$resultSetFileRecord=$attachedFileManager->validateFile();
			if(!is_array($resultSetFileRecord)){
				$message = $bbsClient -> postMessage( $id, $expressions ,null,null, Tag::getPostedTagIds());
				require_once dirname( __FILE__ ) . '/../class/manager/post-manager.php';
				$postManager = new PostManager();
				$post = $postManager -> getPost( $message['contents'] -> id );
				$page = floor( ( $post -> getNumber()-1 ) / 20 ) + 1;
				$redirectURL = '?topicId=' . $message['contents'] -> topicId . '&page=' . $page . '#post-number-' . $post -> getNumber();
				$attachedFileManager -> SetFileRecord($message['contents'] -> id);

				if ($message) {
					require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
					$postedNotice = new PostedNotice();
					$postedNotice->notifyPostedEachTime($message['contents'] -> id);
				}
			}
			break;
		case 'post_reply':
			$id = $message -> mVars['topic_id']['value'];
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_MessageExpression();
				$expression -> body = $contents[0];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}

			$resultSetFileRecord = $attachedFileManager->validateFile();

			if(!is_array($resultSetFileRecord)){
				if(is_numeric($_POST['reply_id'])){
					$parent_id = intval($_POST['reply_id']);
				}else{
					$parent_id = null;
				}

				$message = $bbsClient -> postMessage( $id, $expressions ,null,$parent_id, Tag::getPostedTagIds());
				require_once dirname( __FILE__ ) . '/../class/manager/post-manager.php';
				$postManager = new PostManager();
				$post = $postManager -> getPost( $message['contents'] -> id );
				$page = floor( ( $post -> getNumber()-1 ) / 20 ) + 1;
				$redirectURL = '?topicId=' . $message['contents'] -> topicId . '&page=' . $page . '#post-number-' . $post -> getNumber();
				$attachedFileManager -> SetFileRecord( $message['contents'] -> id );
				if ($message) {
					require_once dirname(__FILE__).'/../class/posted-notice/PostedNotice.class.php';
					$postedNotice = new PostedNotice();
					$postedNotice->notifyPostedEachTime($message['contents']->id);
				}
			}
			break;
		case 'post_edit':
			foreach ( $_POST['contents'] as $languageCode => $contents ) {
				$expression = new ToolboxVO_BBS_MessageExpression();
				$expression -> body = $contents[0];
				$expression -> language = $languageCode;
				$expressions[] = $expression;
			}

			$resultSetFileRecord = $attachedFileManager->validateFile();

			if(!is_array($resultSetFileRecord)){
				$message = $bbsClient -> editMessage( $id, $expressions );
				require_once dirname( __FILE__ ) . '/../class/manager/post-manager.php';
				$postManager = new PostManager();
				$post = $postManager -> getPost( $message['contents'] -> id );
				$page = floor( ( $post -> getNumber()-1 ) / 20 ) + 1;
				$redirectURL = '?topicId=' . $message['contents'] -> topicId . '&page=' . $page . '#post-number-' . $post -> getNumber();
				$attachedFileManager -> SetFileRecord( $message['contents'] -> id );

				$bbsTagClient = new TagBBSClientAdapter();
				$bbsTagClient->updateTagRelation($id, Tag::getPostedTagIds());
			}
			break;
		default:
			die();
			break;
	}

	//アップロードされたファイルに問題があれば、ページを戻す
	if ( is_array( $resultSetFileRecord ) ) {
		$parameters = array(
			array( 'header' => _MD_D3FORUM_MESSAGE , 'contents' => array() , 'rows' => 6
				)
			);
		foreach($expressions as $ex){
			$parameters[0]['contents'][$ex->language] = $ex->body;
		}

		$xoopsTpl -> assign( array( 'NotUploadedFiles' => $resultSetFileRecord ,'limitSize'=>$attachedFileManager->getFileLimitSize()) );
	}
	else {
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

$xoopsTpl -> assign( array( 'xoops_module_header' => $xoops_module_header,
		'topicPath' => $topicPath,
		'parameters' => $parameters,
		'separator' => '_SEPARATOR_',
		'typeCode' => $typeCode,
		'replyId' => $replyId,
		'bbsPreviewId' => ( isset( $id ) ) ? $id : null
		) );

include XOOPS_ROOT_PATH . '/footer.php' ;

?>
