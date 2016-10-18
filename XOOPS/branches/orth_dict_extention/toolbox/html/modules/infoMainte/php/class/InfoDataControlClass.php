<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This preserves contents
// entered in forms.
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
require_once(dirname(__FILE__).'/../../config.php');

class InfoDataControlClass {
	private $root = null;
	private $db = null;
	private $TBL = null;
	private $uid = null;
	private $result = Array();

	function __construct() {
		$this->root = XCube_Root::getSingleton();
		$this->db   = Database::getInstance();
		$this->TBL  = $this->db->prefix('screen_info_mainte');
		$this->uid  = intval($this->root->mContext->mXoopsUser->get('uid'));
	}

	function saveInfoData($mid,$sid,$items){
		if(is_array($items)){
			$this->db->query("BEGIN");

			$sql = '';
			$sql .= ' DELETE FROM '.$this->TBL.' ';
			$sql .= ' WHERE user_id = ? ';
			$sql .= ' AND module_id = ? ';
			$sql .= ' AND screen_id = ? ';
			$this->db->prepare($sql);
			$this->db->bind_param('isss', $this->uid,$mid,$sid,$key);
			$result = $this->db->execute();
			if(!$result){
				$this->db->query("ROLLBACK");
				$this->result = array('status'=>'ERROE','message'=>$this->db->error());
				return false;
			}

			foreach($items as $key => $val){
				$sql = '';
				$sql .= 'INSERT INTO '.$this->TBL.' ';
				$sql .= '(user_id,module_id,screen_id,item_id,parameter,create_date,delete_flag)';
				$sql .= 'VALUES( ? , ? , ? , ? , ? , ? ,0)';
				$this->db->prepare($sql);
				$this->db->bind_param('issssi',$this->uid,$mid,$sid,$key,$val,time());
				if (!$this->db->execute()) {
					$this->db->query("ROLLBACK");
					$this->result = array('status'=>'ERROE','message'=>$this->db->error());
					return false;
				}
			}
			$this->db->query("COMMIT");
		}
		$this->result = array('status'=>'OK');
		return true;
	}


	function saveInfoData_update($mid,$sid,$items){
		if(is_array($items)){
			foreach($items as $key => $val){
				$sql = '';
				$sql .= ' SELECT COUNT(user_id) AS info_count FROM '.$this->TBL.' ';
				$sql .= ' WHERE user_id = ? ';
				$sql .= ' AND module_id = ? ';
				$sql .= ' AND screen_id = ? ';
				$sql .= ' AND item_id = ? ';

				$this->db->prepare($sql);
				$this->db->bind_param('isss', $this->uid,$mid,$sid,$key);
				$result = $this->db->execute();
				$cnt = 0;
				if (!$result) {

					return false;
				}else{
					if ($row = $this->db->fetchArray($result)) {
						$cnt = $row['info_count'];
					}
				}
				$this->db->freeRecordSet($result);

				$sql = '';
				if($cnt > 0){
					$sql .= ' UPDATE '.$this->TBL.' SET ';
					$sql .= ' parameter = ? , ';
					$sql .= ' create_date = ? , ';
					$sql .= ' delete_flag = 0 ';
					$sql .= ' WHERE user_id = ? ';
					$sql .= ' AND module_id = ? ';
					$sql .= ' AND screen_id = ? ';
					$sql .= ' AND item_id = ? ';

					$this->db->prepare($sql);
					$this->db->bind_param('siisss',$val,time(),$this->uid,$mid,$sid,$key);
					if (!$this->db->execute()) {
						$this->result = array('status'=>'ERROE','message'=>$this->db->error());
						return false;
					}
				}else{
					$sql .= 'INSERT INTO '.$this->TBL.' ';
					$sql .= '(user_id,module_id,screen_id,item_id,parameter,create_date,delete_flag)';
					$sql .= 'VALUES( ? , ? , ? , ? , ? , ? ,0)';
					$this->db->prepare($sql);
					$this->db->bind_param('issssi',$this->uid,$mid,$sid,$key,$val,time());
					if (!$this->db->execute()) {
						$this->result = array('status'=>'ERROE','message'=>$this->db->error());
						return false;
					}
				}
			}
		}
		$this->result = array('status'=>'OK');
		return true;
	}

	function loadInfoData($mid,$sid){
		$this->result = array();
		$limit_time = time() - (INFO_MAINTE_LIFETIME * 60);

		$sql = '';
		$sql .= ' UPDATE '.$this->TBL.' SET ';
		$sql .= ' delete_flag = 1 ';
		$sql .= ' WHERE user_id = ? ';
		$sql .= ' AND module_id = ? ';
		$sql .= ' AND screen_id = ? ';
		$sql .= ' AND create_date < ? ';

		$this->db->prepare($sql);
		$this->db->bind_param('issi',$this->uid,$mid,$sid,$limit_time);
		if (!$this->db->execute()) {
			//$this->result = array('status'=>'ERROE','message'=>$this->db->error());
			//return false;
		}

		$sql = '';
		$sql .= ' SELECT ';
		$sql .= ' item_id, ';
		$sql .= ' parameter ';
		$sql .= ' FROM '.$this->TBL.' ';
		$sql .= ' WHERE user_id = ? ';
		$sql .= ' AND module_id = ? ';
		$sql .= ' AND screen_id = ? ';
		$sql .= ' AND delete_flag = 0 ';

		$this->db->prepare($sql);
		$this->db->bind_param('iss', $this->uid,$mid,$sid);
		$result = $this->db->execute();
		if (!$result) {
			//$this->result = array('status'=>'ERROE','message'=>$this->db->error());
			return false;
		}else{
			$this->result['status'] = 'OK';
			$this->result['items'] = array();
			while ($row = $this->db->fetchArray($result)) {
				$this->result['items'][$row['item_id']] = $row['parameter'];
			}
		}
		$this->db->freeRecordSet($result);

		return true;
	}

	function clearInfoData($mid,$sid){
		$this->result = array();

		$sql = '';
		$sql .= ' UPDATE '.$this->TBL.' SET ';
		$sql .= ' delete_flag = 1 ';
		$sql .= ' WHERE user_id = ? ';
		$sql .= ' AND module_id = ? ';
		$sql .= ' AND screen_id = ? ';

		$this->db->prepare($sql);
		$this->db->bind_param('iss',$this->uid,$mid,$sid);
		if (!$this->db->execute()) {
			return false;
		}

		return true;
	}

	function clearAllData(){
		$this->result = array();

		$sql = '';
		$sql .= ' UPDATE '.$this->TBL.' SET ';
		$sql .= ' delete_flag = 1 ';
		$sql .= ' WHERE user_id = ? ';
		$this->db->prepare($sql);
		$this->db->bind_param('i',$this->uid);
		if (!$this->db->execute()) {
			print $this->db->error();
			return false;
		}
		return true;
	}

	function getResult(){
		return $this->result;
	}
}
?>
