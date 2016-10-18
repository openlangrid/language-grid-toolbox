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

class ActiveUserManager {

	/**
	 *  
	 */
	public function __construct() {
		
	}
	
	/**
	 * 
	 * @param $xoopsUser
	 * @param $xoopsTpl
	 * @param $action
	 */
	public function getActiveUser($xoopsUser,$xoopsTpl,$action,$topicId,$browser = NULL) {
	
		// database connection
		$db =& XoopsDatabaseFactory::getDatabaseConnection();
		// table name
		$userAccessTable = $db->prefix($service->getModuleName().'_user_access');
		
		$ret = 0;

		if($action != "check"){
		
			$uid = '';
			if(!empty($xoopsUser)) {
				$uid = $xoopsUser->getVar('uid');
			}
			
			// data check
			$sql  = '';
			$sql .= ' SELECT COUNT(uid) as uid ';
			$sql .= ' FROM '.$userAccessTable.' ';
			$sql .= ' WHERE uid = '.$uid;
			// TODO
			$sql .= ' AND function_distinction = 1 ';
			$sql .= ' AND topic_id = '.$topicId;
			$result = $db->query($sql);
			
			if (!$result) {
				//die('[ERROR] SQL:'.$sql);
				return;
			}else{
				if ($row = $db->fetchArray($result)) {
					$ret = $row['uid'];
				}
			}
			
			// data insert or update
			if($ret == 0){
				// access data insert

				$sql  = '';
				$sql .= ' INSERT INTO '.$userAccessTable.' ';
				$sql .= ' (uid, access_time, function_distinction, topic_id) ';
				$sql .= ' VALUES ';
				$sql .= " ('%s', SYSDATE(), '1', '%s') ";
				$sql = sprintf($sql, $uid, mysql_real_escape_string($topicId));
				$result = $db->queryF($sql);
				if (!$result) {
					die('[ERROR] SQL:'.$sql);
				}
			}else{
				// access data update
				$sql  = '';
				$sql .= ' UPDATE '.$userAccessTable.' SET ';
				$sql .= ' access_time = SYSDATE() ';
				$sql .= ' WHERE uid = '.$uid.' ';
				// TODO
				$sql .= ' AND function_distinction = 1 ';
				$sql .= ' AND topic_id = '.$topicId;
				$result = $db->queryF($sql);
				if (!$result) {
					die('[ERROR] SQL:'.$sql);
				}
			}
			
			// javascript setting

		}else if($action == "check"){
			
			$uid = '';
			if(!empty($xoopsUser)) {
				$uid = $xoopsUser->getVar('uid');
			}
			
			// data check
			$sql  = '';
			$sql .= ' SELECT COUNT(uid) as uid ';
			$sql .= ' FROM '.$userAccessTable.' ';
			$sql .= ' WHERE uid = '.$uid;
			// TODO
			$sql .= ' AND function_distinction = 1 ';
			$sql .= ' AND topic_id = '.$topicId;
			$result = $db->query($sql);
			if (!$result) {
				//die('[ERROR] SQL:'.$sql);
				return;
			}else{
				if ($row = $db->fetchArray($result)) {
					$ret = $row['uid'];
				}
			}
			
			// data insert or update
			if($ret == 0){
				// access data insert

				$sql  = '';
				$sql .= ' INSERT INTO '.$userAccessTable.' ';
				$sql .= ' (uid, access_time, function_distinction, topic_id) ';
				$sql .= ' VALUES ';
				$sql .= " ('%s', SYSDATE(), '1', '%s') ";
				$sql = sprintf($sql, $uid, mysql_real_escape_string($topicId));
				$result = $db->queryF($sql);
				if (!$result) {
					die('[ERROR] SQL:'.$sql);
				}
				/*
				$sql  = '';
				$sql .= ' INSERT INTO '.$userAccessTable.' SET ';
				$sql .= ' uid = '.$uid.', ';
				$sql .= ' access_time = SYSDATE(), ';
				$sql .= ' function_distinction = 1, ';
				$sql .= ' topic_id = '.$topicId;
				$result = $db->queryF($sql);
				if (!$result) {
					die('[ERROR] SQL:'.$sql);
				}*/
			}else{
				// access data update
				$sql  = '';
				$sql .= ' UPDATE '.$userAccessTable.' SET ';
				$sql .= ' access_time = SYSDATE() ';
				$sql .= ' WHERE uid = '.$uid.' ';
				// TODO
				$sql .= ' AND function_distinction = 1 ';
				$sql .= ' AND topic_id = '.$topicId;
				$result = $db->queryF($sql);
				if (!$result) {
					die('[ERROR] SQL:'.$sql);
				}
			}
			
			$usersTable = $db->prefix('users');
			
			// select active user count
			$activeUserNum = 0; 
			$sql  = '';
			$sql .= ' SELECT COUNT(uid) as num ';
			$sql .= ' FROM '.$userAccessTable.' ';
			$sql .= ' WHERE date_add(access_time, interval 1 minute ) > SYSDATE() ';
			// TODO
			$sql .= ' AND function_distinction = 1 AND topic_id = '.$topicId;
			$result = $db->query($sql);
			if (!$result) {
				//die('[ERROR] SQL:'.$sql);
				return;
			}else{
				if ($row = $db->fetchArray($result)) {
					$ret = $row['num'];
				}
			}
			
			// select active user
			$assign_list = array();
			$sql  = '';
			$sql .= ' SELECT T2.uid AS uid,T2.uname AS uname,T2.user_avatar AS user_avatar,T1.access_time AS access_time ';
			$sql .= ' FROM '.$userAccessTable.' T1, ';
			$sql .= ' '.$usersTable.' T2 ';
			$sql .= ' WHERE T1.uid = T2.uid ';
			$sql .= ' AND date_add(access_time, interval 1 minute ) > SYSDATE() ';
			// TODO
			$sql .= ' AND function_distinction = 1 AND topic_id = '.$topicId;
			$sql .= ' ORDER BY access_time DESC ';
			$result = $db->query($sql);
			if (!$result) {
				die('[ERROR] SQL:'.$sql);
			}else{
				while($row = $db->fetchArray($result)){
				    $buf = array();
				    $buf['uid'] = $row['uid'];
				    $buf['uname'] = $row['uname'];
				    $buf['user_avatar'] = $row['user_avatar'];
				    $buf['access_time'] = $row['access_time'];
				    array_push($assign_list, $buf);
				}
			}
			
			$this -> outputUsers($ret, $assign_list, $browser);
		}
	}
	
	public function outputUsers($ret, $assign_list, $browser) {
		echo "<div style='float:left'>";
		
		echo "<strong>".$ret."</strong> user";
		
		$listCount = 0;
		foreach ($assign_list as $info){
			if($listCount == 5){
				break;
			}
			
			echo " <a href='".$mydirpath."/toolbox/html/userinfo.php?uid=".$info['uid']."' title='".$info['uname']."'>";
			if($info['user_avatar']== 'blank.gif') {
				echo "<img src='".XOOPS_URL.'/modules/user/images/no-image.jpg'."' alt='' width=24 height=24 />";	
			} else {
				echo "<img src='".$mydirpath."/toolbox/html/uploads/".$info['user_avatar']."' alt='' width=24 height=24 />";				
			}
			echo "</a>";
			$listCount++;
		}
		
		echo "</div>";
		echo "<div style='float:left'>";
		
		echo "<script>document.getElementById('activeUserListClose').style.display='none';</script>";
		
		if ($browser == 'msie'){
			echo "<a href='javascript:;' onclick=\"document.getElementById('activeUserList').style.left=(document.getElementById('activeUserListOpen').offsetLeft - 250);document.getElementById('activeUserList').style.top=(document.getElementById('activeUserListOpen').offsetTop + 30);document.getElementById('activeUserList').style.display='block';document.getElementById('activeUserListOpen').style.display='none';document.getElementById('activeUserListClose').style.display='block';\"><img src='".$mydirpath."/toolbox/html/modules/'.$service->getModuleName().'/images/icon/icon_option_big.png' alt='' id='activeUserListOpen' style='position:absolute;' /></a>";
			echo "<a href='javascript:;' onclick=\"document.getElementById('activeUserList').style.display='none';document.getElementById('activeUserListOpen').style.display='block';document.getElementById('activeUserListClose').style.display='none';\"><img src='".$mydirpath."/toolbox/html/modules/'.$service->getModuleName().'/images/icon/icon_option_big.png' alt='' id='activeUserListClose' style='position:absolute;' /></a>";
		}else{
			echo "<a href='javascript:;' onclick=\"document.getElementById('activeUserList').style.left= (document.getElementById('activeUserListOpen').offsetLeft - 250) + 'px';document.getElementById('activeUserList').style.top= (document.getElementById('activeUserListOpen').offsetTop + 30) + 'px';document.getElementById('activeUserList').style.display='block';document.getElementById('activeUserListOpen').style.display='none';document.getElementById('activeUserListClose').style.display='block';\"><img src='".$mydirpath."/toolbox/html/modules/'.$service->getModuleName().'/images/icon/icon_option_big.png' alt='' id='activeUserListOpen' style='position:absolute;' /></a>";
			echo "<a href='javascript:;' onclick=\"document.getElementById('activeUserList').style.display='none';document.getElementById('activeUserListOpen').style.display='block';document.getElementById('activeUserListClose').style.display='none';\"><img src='".$mydirpath."/toolbox/html/modules/'.$service->getModuleName().'/images/icon/icon_option_big.png' alt='' id='activeUserListClose' style='position:absolute;' /></a>";
		}
		echo "</div>";

		echo "<div id='activeUserList' style='text-align:left;padding:10px;overflow:auto;display: none;position:absolute;left: 0px;top: 0px;border:1px;border-style:solid;background:white;z-index:999;width: 250px;height: 350px;'>";
		//echo "<a href='javascript:;' onclick=\"document.getElementById('activeUserList').style.display='none';\"><img src='images/icon/icon_option_big.png' alt='' style='float:left;' /></a><br>";
		
		foreach ($assign_list as $info){
			echo " <a href='".$mydirpath."/toolbox/html/userinfo.php?uid=".$info['uid']."' title='".$info['uname']."'>";
			if($info['user_avatar']== 'blank.gif') {
				echo "<img src='".XOOPS_URL.'/modules/user/images/no-image.jpg'."' alt='' width=24 height=24 />";	
			} else {
				echo "<img src='".$mydirpath."/toolbox/html/uploads/".$info['user_avatar']."' alt='' width=24 height=24 />";			
			}
			echo $info['uname'];
			echo "</a><br>";
		}
		echo "</div>";
	}
}


?>