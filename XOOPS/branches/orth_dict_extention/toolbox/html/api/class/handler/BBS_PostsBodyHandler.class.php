<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
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
require_once(dirname(__FILE__).'/Toolbox_CompositeKeyGenericHandler.class.php');

class BBS_PostBodyObject extends XoopsSimpleObject {

	function BBS_PostBodyObject() {
		$this->initVar('post_id', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('language_code', XOBJ_DTYPE_STRING, '', true, 16);
		$this->initVar('title', XOBJ_DTYPE_STRING, '', true, 255);
		$this->initVar('description', XOBJ_DTYPE_STRING, '', true);
		$this->initVar('update_time', XOBJ_DTYPE_INT, 0, true);
	}
}

class BBS_PostsBodyHandler extends Toolbox_CompositeKeyGenericHandler {

	var $mTable = "";
	var $mModName = "";
	var $mPrimary = "post_id";
	var $mClass = "BBS_PostBodyObject";
	var $mPrimaryAry = array('post_id', 'language_code');
	
	public function __construct(&$db,$moduleName) {
		parent::__construct($db);
		$this->mTable = $db->prefix($moduleName."_posts_body");
		$this->mModName = $moduleName;
	}
	
	function &getObjects($criteria = null, $limit = null, $start = null, $id_as_key = false){
		$ret = array();
		$postsTable = $this->db->prefix($this->mModName.'_posts');
		
		$sql  = "SELECT * FROM `" . $this->mTable . '`';
		$sql .= " WHERE post_id in (select post_id from ".$postsTable." where delete_flag = 0) ";
		if($criteria !== null && is_a($criteria, 'CriteriaElement')) {
			$where = $this->_makeCriteria4sql($criteria);
			
			if (trim($where)) {
				$sql .= " AND " . $where;
			}
			
			$sorts = array();
			foreach ($criteria->getSorts() as $sort) {
				$sorts[] = '`' . $sort['sort'] . '` ' . $sort['order']; 
			}
			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . implode(',', $sorts);
			}
			
			if ($limit === null) {
				$limit = $criteria->getLimit();
			}
			
			if ($start === null) {
				$start = $criteria->getStart();
			}
		}else {
			if ($limit === null) {
				$limit = 0;
			}
			
			if ($start === null) {
				$start = 0;
			}
		}

		$result = $this->db->query($sql, $limit, $start);

		if (!$result) {
			return $ret;
		}

		while($row = $this->db->fetchArray($result)) {
			$obj = new $this->mClass();
			$obj->assignVars($row);
			$obj->unsetNew();
			
			if ($id_as_key)	{
				$ret[$obj->get($this->mPrimary)] =& $obj;
			}else {
				$ret[]=&$obj;
			}
			unset($obj);
		}
	
		return $ret;
	}
}
?>