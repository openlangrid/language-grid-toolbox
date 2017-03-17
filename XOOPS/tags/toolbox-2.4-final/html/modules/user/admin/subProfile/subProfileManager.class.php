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
class subProfileManager {
		var $db;
	var $tableName;
	var $root;

	function __construct() {
		$this->db = &Database :: getInstance();
		$this->tableName = $this->db->prefix("user_sub_profiles");
		$this->root = XCube_Root :: getSingleton();
	}

	function getData() {
		$tn=$this->db->prefix("user_sub_profiles");
		$sql="SELECT * FROM ".$tn." WHERE id=1";
		$qResult=$this->db->queryF($sql);
		$data=$this->db->fetchArray($qResult);
		return $data;

	}

	function Register() {
		$sql="";
		$sql.="UPDATE ".$this->tableName." SET ";
		for($i=1;$i<5;$i++){
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
//		var_dump($sql);

		$res=$this->db->queryF($sql);
//var_dump($res);
		return $res;
	}
}

?>
