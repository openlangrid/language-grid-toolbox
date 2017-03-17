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


ob_start("callback");
session_cache_limiter('private_no_expire');
clearstatcache();

if (ini_get('zlib.output_compression')) {
	ini_set('zlib.output_compression', 'Off');
}
include "header.php" ;
$myts =& MyTextSanitizer::getInstance() ;
include_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;
$cattree = new XoopsTree( $table_folder , "folder_id" , "parent_id" ) ;
// GET variables
$lid = empty( $_GET['lid'] ) ? 0 : intval( $_GET['lid'] ) ;
$cid = empty( $_GET['cid'] ) ? 0 : intval( $_GET['cid'] ) ;


require_once( 'class/fileManager.php' );
require_once( 'class/file.php' );

$fileM = new FileManager();
$file = $fileM->getFile($lid);

if( empty($file) ) {
	redirect_header( $mod_url.'/' , 3 , _MD_ALBM_NOMATCH ) ;
	exit ;
}else{
	$f_lid=$file->getId();
	$f_title=$file->getTitle();
	$f_ext=$file->getExtension();
	$r_type=$file->getReadType();
	$r_user=$file->getUserId();
		
	if($isadmin || $r_type == 'public' || (($r_type == 'user'||$r_type == 'protected') && $r_user == $my_uid)||($r_type == 'protected' && check_read_permission_group($f_lid,2))){
		//read ok
	}else{
		echo "forbidden : access is denied";
		exit ;
	}
}
$up_dir = $files_dir;
if(substr($up_dir,-1,1) != "/"){$up_dir .= "/";}
$file_path = $up_dir.$f_lid;
if(trim($f_ext) != ""){
	$file_path .= ".".trim($f_ext);
}

ob_clean();

if(file_exists($file_path)){
	$filename = trim($f_title);
	$binfile = file_get_contents($file_path);
	$fsize = filesize($file_path);

	mb_http_output('pass');

	if(ereg(' MSIE ', $_SERVER['HTTP_USER_AGENT'])) {
		$encoding = 'sjis-win';
		$outname = mb_convert_encoding($filename,$encoding,"UTF-8");
	}else{
		$outname = $filename;
	}
	
	header("HTTP/1.1 200 OK");
	header("Status: 200 OK");
	header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
	header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Accept-Ranges: ".strlen($fsize)."bytes");
	header("Content-Disposition: attachment; filename=\"".($outname)."\"");
	header("Content-Length: ".$fsize);
	header("Content-Type: application/octet-stream");

	print $binfile;	
}else{
	header("HTTP/1.0 404 Not Found");
}
flush();
ob_flush();
exit();

function callback($buffer){
	return $buffer;
}
?>