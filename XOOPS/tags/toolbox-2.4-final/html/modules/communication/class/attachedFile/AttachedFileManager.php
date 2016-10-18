<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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
Class AttachedFileManager {
	// AttachedFileManager
	var $db;
	var $tableName;
	var $uname;

	function __construct()
	{
		$this->db = &Database :: getInstance();
		$this->tableName = $this->db->prefix(USE_TABLE_PREFIX."_post_file");
		$root = XCube_Root :: getSingleton();
		$this->uname = $root->mContext->mXoopsUser->get('uname');
	}

	function SetFileRecord( $post_id )
	{
		//アップロードのカウント数を変えた為、初期値を１から０にしてみてる
		$c=count( $_FILES );
		for ( $i = 0;$i < $c ; $i++ ) {
			if ( empty( $_FILES["uploadfile" . $i]["tmp_name"] ) ) {
				continue;
			}
			$fn = $_FILES["uploadfile" . $i]["tmp_name"];
			if ( is_uploaded_file( $fn ) ) {
				$file_size = $_FILES["uploadfile" . $i]["size"];
				$file_name = addslashes( $_FILES["uploadfile" . $i]["name"]);
				$file_data = addslashes( file_get_contents( $fn, FILE_BINARY ) );
				//$file_name = $_FILES["uploadfile" . $i]["name"];
				//$file_data = file_get_contents( $fn, FILE_BINARY );
				$sql = "insert into " . $this -> tableName . "(post_id,file_name,file_data,file_size) ".
											"values('" . $post_id . "','" . $file_name . "','" . $file_data . "','" . $file_size . "')";
				$result = $this -> db -> query( $sql );
			}
		}

		if(@file_exists(XOOPS_ROOT_PATH . '/api/class/client/FileSharingClient.class.php')){
			global $xoopsModuleConfig;
			require_once( XOOPS_ROOT_PATH . '/api/class/client/FileSharingClient.class.php' );
			$FSClient = new FileSharingClient();

			$c=count( $_FILES );
			for ( $i = 0;$i < $c ; $i++ ) {
				$ret = array();
				$fn = $_FILES["uploadfile" . $i]["tmp_name"];
				if ( is_uploaded_file( $fn ) ) {
					$file_name = $_FILES["uploadfile" . $i]["name"];
					$readPerm =& new ToolboxVO_FileSharing_Permission();
					$readPerm->type = 'public';
					$readPerm->userId = $this->uname;
					$editPerm =& new ToolboxVO_FileSharing_Permission();
					$editPerm->type = 'public';
					$editPerm->userId = $this->uname;
					$ret = $FSClient->addFile($fn,$file_name,'',$xoopsModuleConfig['fileSharingCategoryID'],$readPerm,$editPerm);
				}
			}
		}

	}

	function GetFileRecord( $post_id )
	{
		$sql = "select * from " . $this -> tableName . " where post_id='" . $post_id . "'";
		$qResult = $this -> db -> queryF( $sql );
		$ret = array();
		$fResult = null;
		$i = 0;
		while ( $fResult = $this -> db -> fetchArray( $qResult ) ) {
			$ret[$i]["id"] = $fResult["id"];
			$ret[$i]["post_id"] = $fResult["post_id"];
			//$ret[$i]["file_name"] = stripslashes( $fResult["file_name"] );
			$ret[$i]["file_name"] =  $fResult["file_name"] ;
			// $ret[$i]["file_data"]=stripslashes($fResult["file_data"]);
			$ret[$i]["file_size"] = $fResult['file_size'];
			$i++;
		}
		return $ret;
	}

	function DownloadFile( $id )
	{
		$sql = "select * from " . $this -> tableName . " where id='" . $id . "'";
		$qResult = $this -> db -> query( $sql );
		$fResult = $this -> db -> fetchArray( $qResult );
		$file_name = mb_convert_encoding( stripslashes( $fResult["file_name"] ), "SJIS", "UTF-8" );
		//$file_data = stripslashes( $fResult["file_data"] );
		//$file_name = mb_convert_encoding(  $fResult["file_name"] , "SJIS", "UTF-8" );
		$file_data =  $fResult["file_data"] ;
		$file_size=$fResult["file_size"];
		// ダウンロード用のHTTPヘッダ送信
		// ini_set('zlib.output_compression', 'Off');
		header( 'HTTP/1.1 200 OK' );
		header( 'Status: 200 OK' );
		header( "Cache-Control: private, must-revalidate" );
		 header('Accept-Ranges: '.strlen($file_size).'bytes');
				// header('Accept-Ranges: '.$file_size.'bytes');

		// header("Content-Disposition: inline; filename=\"".(utf82sjis($file_name))."\"");
		// header("Content-Disposition: inline; filename=\"".convert_encoding($file_name,"SJIS","UTF-8")."\"");
		header( "Content-Disposition: attachment; filename=\"" . $file_name . "\"" );
		 header("Content-Length: ".$file_size);
		header( "Content-Type: application/octet-stream" );
		//header( "Content-Type: text/plain" );


		print $file_data;
	}

	function DeleteFileRecord( $id )
	{
		 $sql='delete from '.$this->tableName.' where id=\''.$id.'\'';
		//$sql = 'delete from ' . $this -> tableName . ' where id=1';
		$r=$this -> db -> queryF( $sql );
		//echo $id;
	}

  function DeleteFileRecordByPostID( $post_id )
	{
		 $sql='delete from '.$this->tableName.' where post_id=\''.$post_id.'\'';
		//$sql = 'delete from ' . $this -> tableName . ' where id=1';
		$this -> db -> queryF( $sql );
	}

	function getFileCount($post_id){
		$sql = "select * from " . $this -> tableName . " where post_id='" . $post_id . "'";
		$qResult = $this -> db -> query( $sql );
		$fResult = null;
		$i = 0;
		while ( $fResult = $this -> db -> fetchArray( $qResult ) ) {
			$i++;
		}
		return $i;
	}

	//function getExpectedPostID($topicId){
		//$mTable=XOOPS_DB_PREFIX."_forum_posts";
		//$sql = "";
		//$sql .= "SELECT COALESCE(MAX(post_order),0) AS c ";
		//$sql .= " FROM ".$mTable;
		//$sql .= " WHERE topic_id = '".$topicId."'";
		//$result=$this->db->queryF($sql);
//
		//if (!$result) {
			//return false;
		//}
//
		//$ret = $this->db->fetchArray($result);
		//
		//$num=$ret['c'];
		//return $num + 1;
	//}

	function validateFile(){
		$arrNotUpload = null;
		for ( $i = 0; $i < count($_FILES); $i++ ) {
			if ( empty( $_FILES["uploadfile" . $i]["tmp_name"] ) ) {
				continue;
			}

			if ( is_uploaded_file( $_FILES["uploadfile" . $i]["tmp_name"] ) ) {
				$file_size = $_FILES["uploadfile" . $i]["size"];
				$root = &XCube_Root :: getSingleton();
        		$limit_size = $root -> mContext -> mModuleConfig['file_limit_size'];
        		if ($limit_size == '') {
        			continue;
        		}
				$limit_size = $limit_size * pow(10,3);
				if (($file_size > $limit_size) || ($file_size <= 0)) {
			          $tmp["fileName"]=$_FILES["uploadfile" . $i]["name"];
			          $tmp["fileSize"]=$file_size;
				      $arrNotUpload[]=$tmp;
					  continue;
				}
			}
		}
		if (is_array($arrNotUpload)) {
			return $arrNotUpload;
		} else {
			return true;
		}
	}

	function getFileLimitSize(){
		$root = &XCube_Root :: getSingleton();
		$limit_size = $root -> mContext -> mModuleConfig['file_limit_size'];
		return $limit_size;
	}
}
?>
