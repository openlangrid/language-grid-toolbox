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

include( 'header.php' ) ;
include_once( 'class/myuploader.php' ) ;
include_once( 'class/apfilesharing.textsanitizer.php' ) ;

// check file_uploads = on
if( ! ini_get( "file_uploads" ) ) $file_uploads_off = true ;

// get flag of safe_mode
$safe_mode_flag = ini_get( "safe_mode" ) ;

// check or make files_dir
if( ! is_dir( $files_dir ) ) {
	if( $safe_mode_flag ) {echo_message("At first create & chmod 777 '$files_dir' by ftp or shell.",2);}

	$rs = mkdir( $files_dir , 0777 ) ;
	if( ! $rs ) {
		echo_message($files_dir." is not a directory",2);
	} else @chmod( $files_dir , 0777 ) ;
}

// check or set permissions of files_dir
if( ! is_writable( $files_dir ) || ! is_readable( $files_dir ) ) {
	$rs = chmod( $files_dir , 0777 ) ;
	if( ! $rs ) {
		echo_message("chmod 0777 into ".$files_dir." failed",2);
	}
}

$file = array(
	'cid' => ( empty( $_GET['cid'] ) ? 0 : intval( $_GET['cid'] ) ) ,
	'description' => '' ,
	'title' => '',
	'edit' => 'public',
	'read' => 'public',
) ;

$error_msg = "";
if( ! empty( $_POST['submit'] ) ) {

	$myts =& MyAlbumTextSanitizer::getInstance() ;

	$file['cid'] = @$_POST['cid'];
	$file['description'] = trim($myts->stripSlashesGPC( @$_POST["desc_text"] )) ;
	$file['edit'] = $myts->stripSlashesGPC( @$_POST["edit_permission"] ) ;
	$file['read'] = $myts->stripSlashesGPC( @$_POST["read_permission"] ) ;

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	switch($dummy){
		default:
			$submitter = $my_uid ;
			$file['cid'] = !is_numeric($file['cid']) ? 1 : intval( $file['cid'] ) ;
			$cid = $file['cid'];
			$newid = $xoopsDB->genId( $table_files."_lid_seq" ) ;

			// Check if cid is valid
			if( $cid <= 0 ) {
				$error_msg .= 'Category is not specified.<br>';
			}

			if($cid == 1){
				if(!$isadmin && $apfilesharing_rootedit == 0){
					$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
				}
			}else{
				$sql  = " SELECT edit_permission_type,edit_permission_user ";
				$sql .= " FROM ".$table_folder." ";
				$sql .= " WHERE cid = ".$cid." ";
				$rs = $xoopsDB->query( $sql );
				list($e_type,$e_user) = $xoopsDB->fetchRow( $rs );
				if($isadmin || $e_type == 'public' || ($e_type == 'user' && $e_user == $my_uid)){
					//can create
				}else{
					$error_msg .= _MD_ALBM_NOT_FOLDER_PERMISSION."<br>";
				}
			}

			// Check if upload file name specified
			$field = @$_POST["xoops_upload_file"][0] ;
			if( empty( $field ) || $field == "" ) {
				$error_msg .= "UPLOAD error: file name not specified<br>";
			}

			if( $_FILES[$field]['name'] == '' ) {
				$error_msg .= _MD_ALBM_NOIMAGESPECIFIED."<br>";
				break;
			}else{
				if(!check_samename_file($cid,$_FILES[$field]['name'][0])){
					$error_msg .= _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
				}
			}

			if( $_FILES[$field]['tmp_name'] == "" ) {
				$error_msg .= _MD_ALBM_FILEERROR."<br>";
				break;
			}

			if($error_msg != ""){break;}

			$ErrCode = 0;
			switch($dummy){
				default:
					if($_FILES[$field]["error"]){$ErrCode=1;break;}
					if(!is_uploaded_file($_FILES[$field]["tmp_name"])){$ErrCode=2;break;}
					if($_FILES[$field]["size"] > ($apfilesharing_fsize * 1000000)){$ErrCode=2;break;}
				break;
			}
			if($ErrCode != 0){
				$error_msg .= _MD_ALBM_FILEERROR."<br>";
			}
		
		for($i=0;$i<count($_FILES["upload_file"]["name"]);$i++){
			
			set_time_limit(0);
			$uploader = new MyXoopsMediaUploader( $files_dir , $array_allowed_mimetypes , ($apfilesharing_fsize * 1000000) , $apfilesharing_width , $apfilesharing_height , $array_allowed_exts ) ;

			$uploader->setPrefix( 'tmp_' ) ;
			if( $uploader->fetchMedia( $field,$i ) && $uploader->upload() ) {
				// Succeed to upload
				$title = $uploader->getMediaName() ;
				$tmp_name = $uploader->getSavedFileName() ;
			} else {
				@unlink( $uploader->getSavedDestination() ) ;
				$error_msg = $uploader->getErrors();
				continue;
			}

			if( ! is_readable( "$files_dir/$tmp_name" ) ) {
				$error_msg = _MD_ALBM_FILEREADERROR;
				break;
			}

			if(!check_samename_file($cid,$title[$i])){
				$error_msg = _MD_ALBM_FILE_SAME_NAME_ERROR."<br>";
				continue;
			}

			$desc_text = $file['description'] ;
			$date = time() ;
			$ext = substr( strrchr( $tmp_name , '.' ) , 1 ) ;
			$status = 1 ;

			$sql1 = "";$sql2 = "";
			$sql1 .= "lid,";                    $sql2 .= "".$newid.",";
			$sql1 .= "cid,";                    $sql2 .= "".$cid.",";
			$sql1 .= "title,";                  $sql2 .= "'".addslashes($title)."',";
			$sql1 .= "ext,";                    $sql2 .= "'".$ext."',";
			$sql1 .= "submitter,";              $sql2 .= "".$submitter.",";
			$sql1 .= "status,";                 $sql2 .= "".$status.",";
			$sql1 .= "date,";                   $sql2 .= "".time().",";
			$sql1 .= "description,";            $sql2 .= "'".addslashes($desc_text)."',";
			$sql1 .= "create_date,";            $sql2 .= "".time().",";
			$sql1 .= "edit_date,";              $sql2 .= "".time().",";
			$sql1 .= "read_permission_type,";   $sql2 .= "'".trim($file['read'])."',";
			$sql1 .= "read_permission_user,";   $sql2 .= "".$submitter.",";
			$sql1 .= "edit_permission_type,";   $sql2 .= "'".trim($file['edit'])."',";
			$sql1 .= "edit_permission_user ";   $sql2 .= "".$submitter." ";

			$sql  = "INSERT INTO ".$table_files."(".$sql1.")VALUES(".$sql2.")";
			$xoopsDB->query( $sql ) or die( "DB error: INSERT file table" ) ;
			if( $newid == 0 ) {
				$newid = $xoopsDB->getInsertId();
			}

			apfilesharing_modify_file( "$files_dir/$tmp_name" , "$files_dir/$newid.$ext" ) ;
			$newid = $xoopsDB->genId( $table_files."_lid_seq" ) ;
			// Clear tempolary files
		}
			apfilesharing_clear_tmp_files( $files_dir ) ;

			echo_message(_MD_ALBM_RECEIVED);
			exit ;
		break;
	}
}else{
	// check Categories exist
	$sql  = " SELECT cid,edit_permission_type,edit_permission_user ";
	$sql .= " FROM ".$table_folder." ";
	$sql .= " WHERE cid = ".intval($file['cid'])." ";
	$rs = $xoopsDB->query( $sql );
	$row = $xoopsDB->fetchRow( $rs );
	if($row == false){
		echo_message(_MD_ALBM_MUSTADDCATFIRST,2);
	}else{
		list($mycid,$e_type,$e_user) = $row;
		if($mycid == 1){
			if(!$isadmin && $apfilesharing_rootedit == 0){
				echo_message(_MD_ALBM_NOT_FOLDER_PERMISSION,2);
			}
		}else{
			if(!$isadmin && ($e_type == 'user' && $e_user != $my_uid)){
				echo_message(_MD_ALBM_NOT_FOLDER_PERMISSION,2);
			}
		}
	}
}


//include_once( "../../include/xoopscodes.php" ) ;

$maxfilesize = $apfilesharing_fsize . ( empty( $file_uploads_off ) ? "" : ' &nbsp; <b>"file_uploads" off</b>' );
$parent_folder = _MD_ALBM_MAIN;
$ftree = get_folder_tree($file['cid']);
foreach($ftree as $folders){
	foreach($folders as $val){
		if($val['selected']){
			$parent_folder .= ' > '.$val['title'];
		}
	}
}

echo_header();
?>
		<?if($error_msg != ""){?>
			<ul class="errorMsg" style="color:red;">
				<li><?=$error_msg?></li>
			</ul>
		<?}?>
		<FORM id="uploadfile" encType="multipart/form-data" method="post" name="uploadfile" action="upload.php">
			<TABLE style="text-align:left;" class="outer" cellSpacing="1">
				<tr>
					<th colSpan=2><?=_MD_ALBM_FILEUPLOAD?></th>
				</tr>
				<tr>
					<td class="list_line03"><?=_MD_ALBM_SELECTFILE?></td>
					<td>
						<INPUT value="<?php printf("%.0f", (string)($apfilesharing_fsize*1000000));?>" type="hidden" name="MAX_FILE_SIZE" class="multi">
						<INPUT id="upload_file" size="70" type="file" name="upload_file[]" class="multi">
						<INPUT id="xoops_upload_file[]" value="upload_file" type="hidden" name="xoops_upload_file[]"class="multi">
					</td>
				</tr>
				<tr>
					<td class="list_line03"><?=_MD_ALBM_FILEDESC?></td>
					<td>
						<INPUT id="desc_text" maxLength="255" size="50" type="text" name="desc_text" value="<?=$file['description']?>">
					</td>
				</tr>
				<tr>
					<td class="list_line03"><?=_MD_ALBM_FOLDER?></td>
					<td>
						<?=$parent_folder?>
					</td>
				</tr>
				<tr>
					<td class="list_line03"><?=_MD_ALBM_EDIT_PERMISSION?></td>
					<td>
						<select name="edit_permission" onchange="change_read_perm(this);" style="width:200px;">
							<option value="public"<?if($file['edit'] == 'public'){?> selected<?}?>><?=_MD_ALBM_FOR_ALL_USERS?></option>
							<option value="user"<?if($file['edit'] == 'user'){?> selected<?}?>><?=_MD_ALBM_FOR_THE_CURRENT_USER_ONLY?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="list_line03"><?=_MD_ALBM_READ_PERMISSION?></td>
					<td>
						<select name="read_permission" id="read_perm" style="width:200px;">
							<option value="public"<?if($file['read'] == 'public'){?> selected<?}?>><?=_MD_ALBM_FOR_ALL_USERS?></option>
							<?if($file['edit'] != 'public'){?>
							<option value="user"<?if($file['read'] == 'user'){?> selected<?}?>><?=_MD_ALBM_FOR_THE_CURRENT_USER_ONLY?></option>
							<?}?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:left;border-left:none;border-right:none;border-bottom:none;">
					<?=_MD_ALBM_MAXSIZE?>:&nbsp;<?=$maxfilesize?> <?=_MD_ALBM_FILESIZE_UNIT?>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:center;border:none;">
						<INPUT id="back"   class="btn_blue01" value="<?=_MD_ALBM_BTN_CLOSE?>"   type="button" name="close" onclick="window.close();" style="width:70px;"> &nbsp;
						<INPUT id="submit" class="btn_blue01" value="<?=_MD_ALBM_BTN_OK?>" type="submit" name="submit" style="width:70px;"> &nbsp;
					</td>
				</tr>
			</TABLE>
			<INPUT id="cid" value="<?=$file['cid']?>" type="hidden" name="cid">
			<INPUT id="fieldCounter"   value="1"   type="hidden" name="fieldCounter">
			<INPUT id="op"             value="submit" type="hidden" name="op">
			<?=$GLOBALS['xoopsGTicket']->getTicketHtml( __LINE__ )?>
		</FORM>
<?
echo_footer();
exit();

function echo_message($message,$mode = 1){
	echo_header();
?>
		<br>
		<ul class="errorMsg"<?if($mode == 2){?> style="color:red;"<?}?>>
			<li><?=$message?></li>
		</ul>
		<br>
		<br>
		<FORM>
			<INPUT id="close" class="btn_blue01" value="<?=_MD_ALBM_BTN_CLOSE?>" type="button" name="close" onclick="window.close();" style="width:70px;"class = "multi">
		</FORM>
<?
	echo_footer();
}

function echo_header(){
	global $mydirname,$xoops_module_header,$xoopsConfig;

	$xoops_imageurl = XOOPS_THEME_URL . '/' . $xoopsConfig['theme_set'] . '/';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?=_LANGCODE?>">
<head>
	<meta http-equiv="Content-Type"			content="text/html; charset=<?=_CHARSET?>" />
	<meta http-equiv="content-language"		content="<?=_LANGCODE?>" />
	<meta http-equiv="content-script-type"	content="text/javascript" />
	<meta http-equiv="content-style-type"	content="text/css" />
	<meta name="generator"					content="XOOPS Cube Legacy" />
	<title><?=_MD_ALBM_FILEUPLOAD?></title>
	<link href="<?=XOOPS_URL?>/favicon.ico" rel="SHORTCUT ICON" />
	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?=$xoops_imageurl?>common/css/import.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?=$xoops_imageurl?>css/default.css1" />
	<link rel="stylesheet" type="text/css" media="all" href="<?=$xoops_imageurl?>css/style.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?=$xoops_imageurl?>css/module.css" />
	<link rel="stylesheet" type="text/css" media="all" href="<?=$xoops_imageurl?>css/user_style.css" />
	<!-- JS -->
	
	<script language="JavaScript" type="text/javascript">/*@cc_on _d=document;eval('var document=_d')@*/</script>
	<script language="JavaScript" type="text/javascript" src="<?=XOOPS_URL?>/common/lib/prototype.js"></script>
	<script language="JavaScript" type="text/javascript" src="<?=$xoops_imageurl?>common/js/rollover.js"></script>

	<?=$xoops_module_header?>
	
	<script type="text/javascript">
	var Const = {
		Label : {
			optionPublic:'<?=_MD_ALBM_FOR_ALL_USERS?>',
			optionUser:'<?=_MD_ALBM_FOR_THE_CURRENT_USER_ONLY?>'
		}
	};
	
	</script>
</head>
<body id="<?=$mydirname?>" style="background:none;">
<div id="contents_body" style='width:auto;height:auto;min-width:auto;min-height:auto;text-align:center;margin:auto;'>
	<div class="list_table02" style="width:90%;margin-left:5%;margin-right:5%;">
<?
}

function echo_footer(){
?>
	</div>
</div>
</body>
</html>
<?
	exit();
}
?>