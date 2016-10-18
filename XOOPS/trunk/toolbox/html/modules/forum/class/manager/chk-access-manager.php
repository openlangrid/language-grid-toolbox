<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2013  Department of Social Informatics, Kyoto University
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


/* 20130131 add */

require_once dirname(__FILE__).'/abstract-manager.php';

class chkAccessManager extends AbstractManager {

	public function __construct() {
		parent::__construct();
	}

	public function searchUserAccess() {

		$postsTable          = $this->db->prefix($this->moduleName.'_posts');
		$usersTable          = $this->db->prefix("users");
		$orgTable          = $this->db->prefix("profile_data");

		$sql  = '';
		$sql .= 'SELECT ' ;
		$sql .= 'P.uid, ';
		$sql .= 'MAX(P.post_time) as posttime, ';
		$sql .= 'P.post_order, ';
		$sql .= 'P.delete_flag, ';
		$sql .= 'U.user_avatar as avatar, ';
		$sql .= 'U.uname as name,';
		$sql .= 'G.sub_profile_1 as org, ';
		$sql .= '0 as timelag ';		
		$sql .= 'FROM '.$usersTable.' AS U ';
		$sql .= 'LEFT JOIN '.$postsTable.' AS P ';
		$sql .= 'ON U.uid = P.uid ';
		$sql .= 'LEFT JOIN '.$orgTable.' AS G ';
		$sql .= 'ON G.uid = U.uid ';
		$sql .= 'GROUP BY U.uid ';
		$sql .= 'ORDER BY  U.uid ASC';

		$result = $this->db->query($sql);

		$uaccess = array();
		$i = 0;

		while ($row = $this->db->fetchArray($result)) {
			$uacc = array();
			$uacc['name']=$row['name'];
			$uacc['avatar']=$row['avatar'];
			$uacc['org']=$row['org'];
			
			$past_time=0;
			$ntime=time();
			$past_time= $ntime - $row['posttime'];
			if ($past_time == $ntime){
				$uacc['_bgc']='#990000';
			}else if ($past_time < 86400*3){
				$uacc['_bgc']='#FAFAFA';
			}else if($past_time < 86400*7){
				$uacc['_bgc']='#C0C0C0';
			}else if($past_time >= 86400*7){
				$uacc['_bgc']='#990000';
			}
			
			$uaccess[$i] = $uacc;
			$i++;
		}
		return $uaccess;
	}

	public function searchOrgAccess() {

		$postsTable          = $this->db->prefix($this->moduleName.'_posts');
		$usersTable          = $this->db->prefix('users');
		$orgTable          = $this->db->prefix('profile_data');

		$sql  = 'SELECT '; 
		$sql  .= 'XX.org,'; 
		$sql  .= 'U.user_avatar as avatar,'; 
		$sql  .= 'XX.posttime '; 
		$sql  .= 'FROM ' . $usersTable . ' AS U '; 
		$sql  .= 'LEFT JOIN (SELECT '; 
		$sql  .= 'MIN(G.uid) as uid, '; 
		$sql  .= 'G.sub_profile_1 as org, '; 
		$sql  .= 'MAX(P.post_time) as posttime '; 
		$sql  .= 'FROM ' . $orgTable . ' AS G '; 
		$sql  .= 'LEFT JOIN ' .  $postsTable . ' AS P '; 
		$sql  .= 'ON G.uid = P.uid '; 
		$sql  .= 'GROUP BY G.sub_profile_1 ) as XX '; 
		$sql  .= 'ON U.uid = XX.uid '; 
		$sql  .= 'GROUP BY XX.org '; 
		$sql  .= 'ORDER BY  XX.org ASC'; 




		$result = $this->db->query($sql);

		$uaccess = array();
		$i = 0;
		while ($row = $this->db->fetchArray($result)) {
			$uacc = array();
			$uacc['avatar']=$row['avatar'];
			$uacc['org']=$row['org'];
			
			$past_time=0;
			$ntime=time();
			$past_time= $ntime - $row['posttime'];
			if ($past_time == $ntime){
				$uacc['_bgc']='#990000';
			}else if ($past_time < 86400*3){
				$uacc['_bgc']='#FAFAFA';
			}else if($past_time < 86400*7){
				$uacc['_bgc']='#C0C0C0';
			}else if($past_time >= 86400*7){
				$uacc['_bgc']='#990000';
			}
			
			$uaccess[$i] = $uacc;
			$i++;
		}
		return $uaccess;
	}

}
?>
