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
class SubProfileManager {
	var $db;
	var $config;
	var $lcount;

	function __construct() {
		$this->db = &Database :: getInstance();
		$this->lcount = 5;
	}

	function setConfigration() {
		$sql="";
		$sql.="UPDATE ".$this->tableName." SET ";
		for($i=1;$i<$this->lcount;$i++) {
			if($i!=1) $sql.=",";
			$sql.="sub".$i."_display='".$_POST["radSub".$i]."'";
			$sql.=",";
			$sql.="sub".$i."_title='".$_POST["txtSub".$i."Title"]."'";
			$sql.=",";
			$sql.="sub".$i."_length='".$_POST["txtSub".$i."Length"]."'";
			$sql.=",";
			$sql.="sub".$i."_default='".$_POST["txtSub".$i."Default"]."'";
		}
		$sql.=" where id=1";
		$res=$this->db->queryF($sql);

		return $res;
	}

	function getConfiguration() {
		$tn=$this->db->prefix("user_sub_profiles");
		///later rewrite conditional
		$sql="SELECT * FROM ".$tn." WHERE id=1";
		$qResult=$this->db->queryF($sql);
		$data=$this->db->fetchArray($qResult);
		$this->config = $data;
		return $this->config;
	}

	function getTitle() {
		$tn=$this->db->prefix("user_sub_profiles");
		$sql="SELECT * FROM ".$tn." WHERE id=1";
		$qResult=$this->db->queryF($sql);
		$data=$this->db->fetchArray($qResult);
		$arrSP=array();
		if (isset($_COOKIE["ml_lang"])) {
			$lang = $_COOKIE["ml_lang"];
		}
		else {
			$lang = 'en';
		}
		$separateLen = mb_strlen("[$lang]");
		$limitLen = 15;
		for($i=1; $i<$this->lcount; $i++) {
			$t = $data["sub".$i."_title"];
			$tmp = null;
			preg_match("/\[$lang\].+?\[\/$lang\]/", $t ,$tmp);
			if (!empty($tmp)){
				$t = $tmp[0];
				$t = mb_substr($t, $separateLen);
				$len = mb_strlen($t);
				$t = mb_substr($t, 0, $len-($separateLen+1));
			}
			else {
				preg_match("/\[.+\].+?\[\/.+\]/", $t ,$tmp);
				if( !empty($tmp) ) $t = "";
			}
			if ($data["sub".$i."_display"]) {
				if (mb_strlen($t) > $limitLen){
					$t = mb_substr($t, 0, $limitLen)."...";
				}
				$arrSP[]=$t;
			}
		}
		return $arrSP;
	}

	function getDisplayNumber() {
		$tmp = $this->getConfiguration();
		$ret = array();
		for ($i = 1; $i <= 3; $i++) {
			if ($tmp["sub".$i."_display"]) {
				$ret[] = $i;
			}
		}
		return $ret;
	}

	function getData($id=-1) {
		$ret = array();

		$tn=$this->db->prefix("user_sub_profile_data");
		$this->getConfiguration();

		$sql="SELECT * FROM ".$tn." WHERE uid=".intval($id);
		$qResult=$this->db->queryF($sql);
		$fResult = $this->db->fetchArray($qResult);
		for ($i=1; $i<$this->lcount; $i++){
			if ($this->config["sub".$i."_display"] == 1){
				if($fResult){
					$ret["sub".$i."_value"] = $fResult["sub".$i."_value"];
				}else{
					$ret["sub".$i."_value"] = null;
				}
			}
		}
		$this->setDefault(&$ret);
		return $ret;
	}

	function setDefault(&$record) {
		$tn=$this->db->prefix("user_sub_profiles");
		$sql="SELECT * FROM ".$tn." WHERE id=1";
		$qResult=$this->db->queryF($sql);
		$data=$this->db->fetchArray($qResult);
		foreach ($record as $key=>$r) {
			if($r == null) {
				$defaultKey = substr($key, 0, 4)."_default";
				$record[$key] = $data[$defaultKey];
			}
		}
		return $record;
	}

	function getDefault() {
		$tn=$this->db->prefix("user_sub_profiles");
		$sql="SELECT * FROM ".$tn." WHERE id=1";
		$qResult=$this->db->queryF($sql);
		$data=$this->db->fetchArray($qResult);
		$ret=array();
		for($i=1;$i<$this->lcount;$i++) {
			$ret[]=$data["sub".$i."_default"];
		}
		return $ret;
	}

	function setData() {
		$tn=$this->db->prefix("user_sub_profile_data");
		$where="";
		$where=" WHERE uid=".$_POST["uid"];
		$sql="SELECT * FROM ".$tn.$where;
		$qResult=$this->db->queryF($sql);
		$fResult=$this->db->fetchArray($qResult);
		if(!empty($fResult)) {
			$sql="UPDATE ".$tn." SET ";
			$flg=false;
			for($i=1;$i<$this->lcount;$i++) {
				if(isset ($_POST["inputSub".$i])) {
					if($flg) $sql.=",";
					$sql.="sub".$i."_value='".$_POST["inputSub".$i]."'";
					$flg=true;
				}
			}
			$sql.=" WHERE uid=".$_POST["uid"];
		}
		else {
			$sql="INSERT INTO ".$tn." VALUES(".$_POST["uid"].",";
			for($i=1;$i<$this->lcount;$i++) {
				if($_POST["inputSub".$i]) {
					$sql.="'".$_POST["inputSub".$i]."'";
				}
				else {
					$sql.="''";
				}
				if($i!=4) $sql.=",";
				$flg=true;
			}
			$sql.=")";
		}
		$res=$this->db->queryF($sql);

		return $res;
	}
}
?>
