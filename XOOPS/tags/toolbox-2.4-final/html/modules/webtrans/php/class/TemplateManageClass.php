<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

class TemplateManageClass {

	private $db = null;
	private $TBL = null;

	function __construct() {
		global $xoopsDB;
		$this->db = $xoopsDB;
		$mydirname = basename(realpath(dirname(__FILE__)."/../../"));
		$this->TBL = $xoopsDB->prefix($mydirname.'_template');
	}

	function getTemplateCount($filename){
		$result = $this->search(
			array(
				'name'=>$filename
			)
		);
		if(is_array($result)){
			return count($result);
		}else{
			return false;
		}
	}

	function deleteTemplate($filename){
		$this->update(array('delete_flag'=>'1'),array('name'=>$filename));
	}

	function insertTemplatePairs($uid,$filename,$pairs){
		$pair_id = 0;
		foreach($pairs as $item){
			$data = array();
			$data['user_id'] = $uid;
			$data['name'] = $filename;
			$data['pair_id'] = $pair_id;
			$data['source_text'] = $item["source"];
			$data['target_text'] = $item["target"];
			$data['create_time'] = time();

			$result = $this->insert($data);
			/*
			if(!$result){
				return false;
			}
			*/
			$pair_id++;
		}
		return true;
	}

	function loadTemplatePairs($filename){
		$where = array(
			'name'=>$filename
		);

		return $this->search($where);
	}
	function loadTemplateNames(){
		$sql = '';
		$sql .= 'SELECT name FROM '.$this->TBL.' WHERE delete_flag = \'0\' ';
		$sql .= '  GROUP BY name ORDER BY name';

		$result = array();
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchArray($rs)) {
				$result[] = $row;
			}
			return $result;
		}else{
			return false;
		}
	}

	private function search($wheres) {
		$sql = '';
		$sql .= 'select * from '.$this->TBL.' where delete_flag = \'0\' and';
		foreach ($wheres as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\' and';
		}
		$sql = substr($sql, 0, -4);
		//$sql .= 'order by user_id, pair_id';
		$sql .= 'order by name';
		$result = array();
		if ($rs = $this->db->query($sql)) {
			while ($row = $this->db->fetchArray($rs)) {
				$result[] = $row;
			}
			return $result;
		}else{
			return false;
		}
	}

	private function insert($data) {
		$sql = '';
		$sql .= 'insert into '.$this->TBL.' set ';
		foreach ($data as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\', ';
		}
		$sql = substr($sql, 0, -2);
		$this->db->queryf($sql);
	}

	private function update($data, $where) {
		$sql = '';
		$sql .= 'update '.$this->TBL.' set update_time = '.time().', ';
		foreach ($data as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\', ';
		}
		$sql = substr($sql, 0, -2);
		$sql .= ' where ';
		foreach ($where as $key => $value) {
			$sql .= '`'.$key.'` = \'' . mysql_real_escape_string($value) . '\' and';
		}
		$sql = substr($sql, 0, -4);
		$this->db->queryf($sql);
	}

	private function realDelete($id) {
		if ($id != null) {
			$sql = '';
			$sql .= 'delete from '.$this->TBL.' where id = \''.$id.'\'';
			$this->db->queryf($sql);
		}
	}





	function getTranslators() {
		return $this->search(array('service_type'=>'TRANSLATION'));
	}
	function searchTranslation($serviceId) {
		$ret = $this->search(array('service_type'=>'TRANSLATION', 'service_id'=>$serviceId));
		if (count($ret) == 1) {
			return $ret[0];
		} else {
			return array();
		}
	}

	function getDictionarys() {
		return $this->search(array('service_type'=>'DICTIONARY'));
	}
	function searchDictionary($serviceId) {
		return $this->search(array('service_type'=>'DICTIONARY', 'service_id'=>$serviceId));
	}



	function getTranslatorAllSupportLanguagePairs() {
		$allpaths = array();
		$services = $this->search(array('service_type'=>'TRANSLATION'));
		foreach ($services as $service) {
			$pathArry = explode(',', $service['supported_languages_paths']);
			$allpaths = array_merge($allpaths, $pathArry);
		}
		sort($allpaths);

		$pairs = array();
		$srcLangs = array();
		$tgtLangs = array();
		foreach($allpaths as $path) {
			$pair = explode('2', $path);
			$pairs[] = $pair;
			$srcLangs[] = $pair[0];
			$tgtLangs[] = $pair[1];
		}

		$srcLangs = array_merge(array(), array_unique($srcLangs));
		$tgtLangs = array_merge(array(), array_unique($tgtLangs));

		$source = array();
		foreach ($srcLangs as $lang) {
			$source = array_merge($source, array($lang=>getLanguageName($lang)));
		}
		$target = array();
		foreach ($tgtLangs as $lang) {
			$target = array_merge($target, array($lang=>getLanguageName($lang)));
		}

		asort($source);
		asort($target);

		return array('sourceLanguages'=>$source, 'targetLanguages'=>$target);
	}

	function getAllSupportLanguagePairs() {
		global $LANGRID_LANGUAGE_ARRAY;
		return array('sourceLanguages'=>$LANGRID_LANGUAGE_ARRAY, 'targetLanguages'=>$LANGRID_LANGUAGE_ARRAY);
	}
}
?>
