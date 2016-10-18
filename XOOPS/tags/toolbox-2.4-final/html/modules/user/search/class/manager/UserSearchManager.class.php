<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides user management
// functions.
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
error_reporting(0);
require_once XOOPS_ROOT_PATH."/api/class/client/ProfileClient.class.php";
require_once dirname(__FILE__)."/../../../subProfile/class/SubProfileManager.class.php";


class UserSearchManager {
	var $db;
	var $profileClient;
	var $subProfileManager;
	var $searchData;

	function __construct() {
		$this->db = &Database :: getInstance();
		$this->profileClient = new ProfileClient();
		$this->subProfileManager = new SubProfileManager();
	}


	function getUserProfile($userIDs = array()) {
		$userData = array();
		$subProfileData = array();
		$keys = array("id", "name");
		if (empty($userIDs)) {
			$userIDs = $this->profileClient->getAllUserIDs();
		}
		$title = $this->subProfileManager->getTitle();
		$c = count($userIDs["contents"]);
		for ($i = 0; $i < $c; $i++) {
			$uname = $userIDs["contents"][$i];
			$tmp = $this->profileClient->getProfile($uname);
			$tmp = (array)$tmp["contents"];
			$keysLen = count($keys);
			for ($j = 0; $j < $keysLen; $j++) {
				$userData[$i][$keys[$j]] = $tmp[$keys[$j]];
			}
			$userData[$i]["uname"] = $uname;
			$id = $userData[$i]["id"];
			$userData[$i]["subProfile"] = $this->subProfileManager->getData($id);
		}
		return $userData;
	}

	function getSubProfileTitle() {
		return $this->subProfileManager->getTitle();
	}

	function search($searchData = array()) {
		//ユーザプロファイルを引き出してから検索
		$this->searchData = $searchData;
		$front = "";
		$back = "";
		switch ($this->searchData["type"]) {
			case "partial":
				break;
			case "prefix":
				$front = "^";
				break;
			case "suffix":
				$back = "$";
				break;
			case "complete":
				if ($this->searchData["keyWord"] != "") {
					$front = "^";
					$back = "$";
				}
				break;
			default:
				break;
		}

		$this->searchData["keyWord"] = $this->decodeUrlFix($this->searchData["keyWord"]);
		$escapeKeyWord = preg_quote($this->searchData["keyWord"], "/");
		$this->searchData["pattern"] = "/$front".$escapeKeyWord."$back/";

		$profs = $this->getUserProfile();
		$userProf = array();
		$keys = array("uname", "name", "subProfile");
		$keyCount = count($keys);
		$profCount = count($profs);
		for ($i = 0; $i < $profCount; $i++) {
			$matchFlag = false;
			$prof = $profs[$i];
			$hilight = array();
			for ($j = 0; $j < $keyCount; $j++) {
				$key = $keys[$j];
				$matches = null;
				if ($key == "subProfile"){
					foreach ($prof["subProfile"] as $subKey => $subProf) {
						$result = $this->execSearch($subProf, $subKey, &$hilight);
						if ($result){
							$matchFlag = $result;
						}
					}
				}
				else {
					$result = $this->execSearch($prof[$key], $key, &$hilight);
					if ($result){
						$matchFlag = $result;
					}
				}
			}
			if ($matchFlag){
				$prof["hilight"] = $hilight;
				$userProf[] = $prof;
			}
		}

		$ret = array("status"=>"ok","contents"=>null,"title"=>null);
		$ret["contents"] = $userProf;
		$ret["title"] = $this->getSubProfileTitle();
		return $ret;
	}

	function decodeUrlFix($str) {
		$ret = $str;
		$ret = preg_replace("/\\\\'/", "'", $ret);
		$ret = preg_replace('/\\\\"/', '"', $ret);
		return $ret;
	}

	function execSearch($subject, $key, &$hilight ) {
		$matchFlag = false;
		$matches = null;
		$matchCount = preg_match($this->searchData["pattern"], $subject, $matches);

		if ($matchCount){
			$matchFlag = true;
			$hilight[$key] = $this->setHilight($subject);
		}
	return $matchFlag;
	}

	function setHilight($haystack) {
		$ret = array();
		$needle = $this->searchData["keyWord"];
		$needleLen = mb_strlen($needle);
		$haystackLen = mb_strlen($haystack);
		$lastPosition = $haystackLen - $needleLen;
		switch ($this->searchData["type"]) {
			case "partial":
				$i = 0;
				$offset = 0;
				while ($offset<=$lastPosition) {
					$index = mb_strpos($haystack, $needle, $offset);
					if ($index === false) break;

					if ($i == 0) {
							$ret[$i] = $index;
							$ret[$i+1] = $needleLen;
							$i += 2;
					}
					else {
						$oldIndex = $ret[$i-2];
						$oldLen = $ret[$i-1];
						if ($oldIndex == $index) {
						}
						elseif ($oldIndex+$oldLen-1>=$index){
								$ret[$i-1] += ($index+$needleLen) - ($oldIndex+$oldLen);
						}
						else{
							$ret[$i] = $index;
							$ret[$i+1] = $needleLen;
							$i += 2;
						}
					}
					$offset += 1;
				}
				break;
			case "prefix":
				$ret[] = 0;
				$ret[] = $needleLen;
				break;
			case "suffix":
				$ret[] = $lastPosition;
				$ret[] = $needleLen;
				break;
			case "complete":
				if ($this->searchData["keyWord"] != "") {
					$ret[] = 0;
					$ret[] = $haystackLen;
				}
				break;
			default:
				break;
		}
		return $ret;
	}

	function TestSearch() {
		//    IDも渡す様にする

		$ret=array("status"=>"ok",
        "contents"=>array(
		array(
        "name"=>"abcdefghijklmn",
        "uname"=>"bbbbbbbbbb",
				"sub1_value"=>"sub1",
				"sub2_value"=>"sub2",
				"sub3_value"=>"sub3",
        "hilight"=>array(
	        								"name"=>array(0,3,5,2),
	        								"uname"=>array(0,1,4,3),
													"sub1_value"=>array(0,1)
												)
		),
		array(
        "name"=>"abcdefghijklmn",
        "uname"=>"bbbcccccccccccc",
        "email"=>"bbbbbbbb",
        "timezone_offset"=>"9.0",
        "hilight"=>array("name"=>array(0,3,5,),
								        "uname"=>array(0,1,4,3),
								        "email"=>array(2,3),
								        "timezone_offset"=>array(0,3)
												)
					)
		),

		"title"=>array("title1","title2","title3")
		);

		return $ret;
	}

	//legacy
	function setSQL() {
		//searchIDがuname,searchNameがnameに対応する。
		//UIとテーブル構造にズレがある
		$existConditional=false;
		$this->sql = "SELECT uid,name,uname,email,user_avatar,timezone_offset,user_viewemail FROM ".$this->tableName." ";
		$this->sql .= "WHERE (level>0) ";//０だっけ？
		if(($this->searchID != null) || ($this->searchName != null) || ($this->searchTimeZone_Offset != null)) {
			$this->sql .= "AND ( ";
			$existConditional = true;
		}

		if($this->searchID!=null) {
			switch ($_GET["searchTypeID"]) {
				case "prefix":
					$this->sql .= "(uname LIKE '".$this->searchID."%')";
					break;
				case "suffix":
					$this->sql .= "(uname LIKE '%".$this->searchID."')";
					break;
				case "partial":
					$this->sql .= "(uname LIKE '%".$this->searchID."%')";
					break;
				default:
					$this->sql .= "(uname = '".$this->searchID."')";
					break;
			}
		}

		if($this->searchName!==null) {
			if($this->searchID) $this->sql.=" ".$this->searchOperator;
			switch ($_GET["searchTypeName"]) {
				case "prefix":
					$this->sql .= "(name LIKE '".$this->searchName."%')";
					break;
				case "suffix":
					$this->sql .= "(name LIKE '%".$this->searchName."')";
					break;
				case "partial":
					$this->sql .= "(name LIKE '%".$this->searchName."%')";
					break;
				default:
					$this->sql .= "(name = '".$this->searchName."')";
					break;
			}
		}
		if($this->searchTimeZone_Offset) {
			if($this->searchID || $this->searchName) $this->sql.=" ".$this->searchOperator;
			$this->sql .= "(timezone_offset=".$this->searchTimeZone_Offset.")";
		}

		if($existConditional) $this->sql.=")";
		$this->sql.=" ORDER BY uname";
	}

	function formatData($record) {
		//name=searchName,uname=searchIDを検索

		//  $targetColumn=array("uname","name");
		$uname=$record["uname"];
		$name=$record["name"];
		$ret=array(
        "uid"=>$record["uid"],
        "uname"=>$uname,
        "name"=>$name,
        "timezone_offset"=>$record["timezone_offset"],
        "user_avatar"=>$record["user_avatar"],
        "hilight"=>array()
		);

		if($record["user_viewemail"]) $ret["email"]=$record["email"];

		$tmp=null;
		if($this->searchID) {
			$tmp=$this->hilightPosition($uname, $this->searchID);
			if(!empty($tmp)) $ret["hilight"]["uname"] = $tmp;
		}
		$tmp=null;
		if($this->searchName) {
			$tmp=$this->hilightPosition($name, $this->searchName);
			if(!empty($tmp)) $ret["hilight"]["name"] = $tmp;
		}
		return $ret;
	}

}
?>