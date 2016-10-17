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

class ContentsUpdate {

	const TYPE_IMAGE = "IMAGE";
	const TYPE_GMAP  = "GMAP";
	
	public function __construct() {
		
	}
	
	function insertContent($content_title, $file_name, $mime_type, $data) {
		
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s') . $content_title ."\r\n"); // writing
		fclose ($fp);
		
		$xoopsDB =& Database::getInstance();
		
		$content_title = mysql_real_escape_string($content_title);
		$file_name     = mysql_real_escape_string($file_name);
		$mime_type     = mysql_real_escape_string($mime_type);
		$data          = mysql_real_escape_string($data);
		
		// deprecated
//		$baseSql  = " INSERT INTO ";
//		$baseSql .=        $xoopsDB->prefix . "_" . $GLOBALS['mydirname'] . "_" . "imagebody ";
//		$baseSql .= "      (image_title, image_file_name, image_mimetype, image_body, image_created) ";
//		$baseSql .= " VALUES ";
//		$baseSql .= "       ('%s', '%s', '%s',  '%s',  NOW())";
	
		// INSERT CONTENT META DATA
		$contentBaseSql  = " INSERT INTO ";
		$contentBaseSql .=        $xoopsDB->prefix . "_" . $GLOBALS['mydirname'] . "_" . "content ";
		$contentBaseSql .= "      (topic_id, uid, content_title, content_url, content_type, content_created) ";
		$contentBaseSql .= " VALUES ";
		$contentBaseSql .= "       ('%d', '%d', '%s',  '%s', '%s',  NOW())";
		
		$contentInsertSql = sprintf($contentBaseSql,
								0,	// TODO set topic_id
								0,		// TODO set user_id
								$content_title,
								null,
								TYPE_IMAGE);
								
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). "contentInsertSql:" . $contentInsertSql . "\r\n"); // writing
		fclose ($fp);
						
		$contentInsertBool = $xoopsDB->queryF($contentInsertSql);
		
		if ($contentInsertBool != 1) {
	    	echo $xoopsDB->errno(). '<br />';// error code
	    	echo $xoopsDB->error(). '<br />';// error message
		}
		
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). "contentInsertBool:" . $contentInsertBool . "\r\n"); // writing
		fclose ($fp);
		
		// INSERT CONTENT IMAGE DATA
		$contentImageSql  = " INSERT INTO ";
		$contentImageSql .=        $xoopsDB->prefix . "_" . $GLOBALS['mydirname'] . "_" . "content_image ";
		$contentImageSql .= "      (content_id, image_file_name, image_mimetype, image_data, image_created) ";
		$contentImageSql .= " VALUES ";
		$contentImageSql .= "       ('%d', '%s', '%s',  '%s', NOW())";
		
		$imageInsertSql = sprintf($contentImageSql,
								$this->selectNewContentId($uid = null),	// TODO set user_id
								$file_name,
								$mime_type,
								$data);
								
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). "imageInsertSql:" . $imageInsertSql . "\r\n"); // writing
		fclose ($fp);
						
		$imageInsertBool = $xoopsDB->queryF($imageInsertSql);
		
		if ($imageInsertBool != 1) {
	    	echo $xoopsDB->errno(). '<br />';// error code
	    	echo $xoopsDB->error(). '<br />';// error message
		}
		
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). "imageInsertBool:" . $imageInsertBool . "\r\n"); // writing
		fclose ($fp);
	}

	
	function selectNewContentId($uid = null) {
		
		$newImageId = null;
		$xoopsDB =& Database::getInstance();
			
		$selectSql  = " SELECT ";
		$selectSql .= "   image_id, ";
		$selectSql .= "   image_title, ";
		$selectSql .= "   image_file_name, ";
		$selectSql .= "   image_mimetype, ";
		$selectSql .= "   image_body, ";
		$selectSql .= "   image_created ";
		$selectSql .= " FROM ";
		$selectSql .=	  $xoopsDB->prefix . "_" . $GLOBALS["mydirname"] . "_" . "imagebody";
		$selectSql .= " WHERE ";
		$selectSql .= "   image_id = (SELECT ";
		$selectSql .= "                 MAX(image_id) ";
		$selectSql .= "               FROM ";
		$selectSql .=	                $xoopsDB->prefix . "_" . $GLOBALS["mydirname"] . "_" . "imagebody)";
		//$selectSql .= "	          WHERE uid = $uid)";// TODO set user_id
		
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). $selectSql ."\r\n"); // writing
		fclose ($fp);
										
		$result=$xoopsDB->query($selectSql);
		
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). "select result:" .  $result ."\r\n"); // writing
		fclose ($fp);
		
		if ($result != null) {
			while ($row = $xoopsDB->fetchArray($result)) {
				$img['image_id'] = $row['image_id'];
				$newImageId = $row['image_id'];
				
				// debug
				$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
				fwrite ($fp, date('y/m/d H:i:s'). "hit id:" .  $newImageId ."\r\n"); // writing
				fclose ($fp);
			}
		}		
		
		// debug
		$fp = fopen("D:/temp/phplogA.log", "a"); // file open by add mode "a":addd, "w":write
		fwrite ($fp, date('y/m/d H:i:s'). $newImageId ."\r\n"); // writing
		fclose ($fp);
		
		return $newImageId;
	}
}
?>
