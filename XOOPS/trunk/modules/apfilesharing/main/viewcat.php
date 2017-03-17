<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to share
// files with other users.
// Copyright (C) 2009  NICT Language Grid Project
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

include "header.php" ;
$myts =& MyTextSanitizer::getInstance() ; // MyTextSanitizer object

// GET variables
$cid = empty( $_GET['cid'] ) ? 1 : intval( $_GET['cid'] ) ;
$lsize = empty( $_GET['num'] ) ? intval( $apfilesharing_perpage ) : intval( $_GET['num'] ) ;
if( $lsize < 1 ) $lsize = $apfilesharing_perpage ;
$pos = empty( $_GET['pos'] ) ? 0 : intval( $_GET['pos'] ) ;
$view = empty( $_GET['view'] ) ? $apfilesharing_viewcattype : $_GET['view'] ;

$xoopsOption['template_main'] = "apfilesharing_viewcat_list.html" ;

include( XOOPS_ROOT_PATH . "/header.php" ) ;

include( 'include/assign_globals.php' ) ;
$xoopsTpl->assign( $apfilesharing_assign_globals ) ;



if( $cid > 1 ){
	require_once( 'class/folderManager.php' );
	require_once( 'class/folder.php' );
	$folderM = new FolderManager();	
	$folder = $folderM->getFolder($cid);
	$r_type = $folder->getReadType();
	$user_id = $folder->getUserId();
	
	if( $isadmin || $r_type == 'public' || (($r_type == 'user' || $r_type == 'protected' )&& $user_id == $my_uid)||($r_type == 'protected' && check_read_permission_group($cid))) {
		require_once ('class/contentManager.php');
		$contentManager = new ContentManager($xoopsDB,$table_folder,$table_files );
		$file_small_sum = $contentManager -> getContentCount($cid);

		$xoopsTpl->assign( 'file_small_sum' , $file_small_sum ) ;
		

		$folder_title = $folder->getTitle();
		$folder_desc = $folder->getDescription();
		$e_type = $folder->getEditType();
		
		
		if($isadmin || $e_type == 'public' || (($e_type == 'user' || $e_type == 'protected' )&& $user_id == $my_uid)||($e_type == 'protected' && check_edit_permission_group($cid))){
			$xoopsTpl->assign( 'lang_add_file' , _MD_ALBM_ADDFILE );
			$xoopsTpl->assign( 'lang_add_folder' , _MD_ALBM_ADDFOLDER );
		}
		$xoopsTpl->assign( 'folder_title' , $myts->makeTboxData4show($folder_title) );
		$xoopsTpl->assign( 'folder_description' , $myts->makeTboxData4show($folder_desc) );
		$xoopsTpl->assign( 'lang_album_main' , _MD_ALBM_MAIN ) ;

		// Category Specified
		$xoopsTpl->assign( 'category_id' , $cid ) ;

		
		$bread_list = array();
		$ftree = get_folder_tree($cid);
		foreach($ftree as $folders){
			foreach($folders as $val){
				if($val['selected']){
					if($val['id'] == $cid){
						$bread_list[] = $val['title'];
					}else{
						$bread_list[] = '<a href="?page=viewcat&cid='.$val['id'].'">'.$val['title'].'</a>';
					}
				}
			}
		}
		$xoopsTpl->assign( 'bread_list' , $bread_list ) ;

		if( $file_small_sum > 0 ) {
			if(isset($_GET['sortkey'])){
				$sortkey = intval($_GET['sortkey']);
			}else{
				$sortkey = 0;
			}

			require_once './class/sortheader.php';
			$sortheader = new SortHeader(5,$sortkey);
			$xoopsTpl->assign( 'nowpos' , $pos );
			$xoopsTpl->assign( 'sortheader' , $sortheader );
			
			$files = $contentManager->getContentList($cid,$lsize,$pos,$sortkey,$my_uid);
				
			//if 2 or more items in result, num the navigation menu
			if( $file_small_sum > 1 ) {

				include_once( XOOPS_ROOT_PATH . '/class/pagenav.php' );
				$nav = new XoopsPageNav( $file_small_sum , $lsize , $pos , 'pos' , "page=viewcat&cid=".$cid."&num=".$lsize."&sortkey=".$sortkey."" ) ;
				$nav_html = $nav->renderNav( 10 ) ;
				include_once("./include/pager.php");
				$nav_html = format_pager($nav_html);
				
				$last = $pos + $lsize ;
				if( $last > $file_small_sum ) $last = $file_small_sum ;
				$filenavinfo = sprintf( _MD_ALBM_AM_FILENAVINFO , $pos + 1 , $last , $file_small_sum ) ;
				$xoopsTpl->assign( 'filenav' , $nav_html ) ;
				$xoopsTpl->assign( 'filenavinfo' , $filenavinfo ) ;
			}

			// Display files
			$count = 1 ;
			$xoopsTpl->assign( 'files' , $files );

		}
	}else{
		redirect_header($mod_url);
	}
} else {
	redirect_header($mod_url);
}


include( XOOPS_ROOT_PATH . "/footer.php" ) ;
?>