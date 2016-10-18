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
// error_reporting(0);
require_once XOOPS_ROOT_PATH."/api/class/client/ProfileClient.class.php";
require_once XOOPS_ROOT_PATH."/api/class/client/ExtendedProfileClient.class.php";


class UserSearchManager {
	var $db;
	var $profileClient;
	var $extProfileClient;
	var $searchData;

	function __construct() {
		$this->db = &Database :: getInstance();
		$this->profileClient = new ProfileClient();
		$this->extProfileClient = new ExtendedProfileClient();
	}


	function getUserProfile($userIDs = array()) {
		$userData = array();
		$keys = array("id", "name");
		if (empty($userIDs)) {
			$userIDs = $this->profileClient->getAllUserIDs();
		}
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
            $envelope = $this->extProfileClient->getProfile($id);
            $userData[$i]["definitions"] = $envelope['contents']->definitions;
            $userData[$i]["values"] = $envelope['contents']->values;
		}
		return $userData;
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
		$profCount = count($profs);
		$userProf = array();
        if ($profCount) {
            $keys = array("uname", "name");
            $keyCount = count($keys);
            for ($i = 0; $i < $profCount; $i++) {
                $matchFlag = false;
                $prof = $profs[$i];
                $hilight = array();
                for ($j = 0; $j < $keyCount; $j++) {
                    $key = $keys[$j];
                    $result = $this->execSearch($prof[$key], $key, $hilight);
                    if ($result){
                        $matchFlag = $result;
                    }
                }
                foreach ($prof['definitions'] as $def) {
                    $key = $def['field_name'];
                    $v = $prof['values'][$key];
                    $result = $this->execSearch($v, $key, $hilight);
                    if ($result){
                        $matchFlag = $result;
                    }
                }
                if ($matchFlag){
                    $prof["hilight"] = $hilight;
                    $userProf[] = $prof;
                }
            }
        }
		$ret = array("status"=>"ok","contents"=>null,"title"=>null);
		$ret["contents"] = $userProf;
        if ($titles = $this->getTitles()) {
            $ret['title'] = $titles;
        }
		return $ret;
	}

    function getTitles() {
        // get Admin user instead of getting column definition
        $envelope = $this->extProfileClient->getProfile(1);
        $prof = $envelope['contents'];
        unset($envelope);

        if (isset($_COOKIE["ml_lang"])) {
			$lang = $_COOKIE["ml_lang"];
		}
		else {
			$lang = 'en';
		}
        $arrSP = array();
		$separateLen = mb_strlen("[$lang]");
		$limitLen = 15;
		foreach($prof->definitions as $def) {
            $t = $def['label'];
			$tmp = null;
			preg_match("/\[$lang\](.+?)\[\/$lang\]/", $t ,$tmp);
			if (!empty($tmp)){
				$t = $tmp[1];
			}
			else {
				preg_match("/\[.+\].+?\[\/.+\]/", $t ,$tmp);
				if( !empty($tmp) ) $t = "";
			}
            $arrSP[]=$t;
		}
		return $arrSP;
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