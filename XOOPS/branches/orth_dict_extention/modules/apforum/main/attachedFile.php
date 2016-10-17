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
//error_reporting( E_ALL );
// $url=XOOPS_ROOT_PATH.'/modules/forum/class/attachedFile/AttachedFileManager.php';
// require $url;
// $_GET["mode"]="delete";
// $_GET["id"]=1;
$url = dirname( __FILE__ ) . '/../class/attachedFile/AttachedFileManager.php';
require_once( $url );

require_once dirname(__FILE__).'/../class/manager/post-manager.php';
$postManager = new PostManager();
$attachedFileManager = new AttachedFileManager();

$postId = $attachedFileManager->getPostIdById($_GET["id"]);
$postAuth = $postManager->getPostAuth($postId);

$isadmin=is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->isAdmin():false;
$gid_arr=is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups():array();
$arr_int = array_intersect($postAuth,$gid_arr);
if($isadmin||is_array($arr_int)&&count($arr_int) != 0){
	if ( $_GET['mode'] == 'download' ) {
		$attachedFileManager -> DownloadFile( $_GET["id"] );
	}
	elseif ( $_GET['mode'] == 'delete' ) {
		$attachedFileManager -> DeleteFileRecord( $_GET["id"] );
	} 
}
?>
