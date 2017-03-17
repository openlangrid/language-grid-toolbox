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

class Toolbox_CompositeKeyGenericHandler extends XoopsObjectGenericHandler {
	var $mPrimaryAry = array();

	/**
	 * @Overwride
	 */
	function &get($compsite)
	{
		$ret = null;

		$criteria =& new CriteriaCompo();
		foreach ($this->mPrimaryAry as $pkName) {
			$criteria->add(new Criteria($pkName, $compsite[$pkName]));
		}
//		$criteria =& new Criteria($this->mPrimary, $id);
		$objArr =& $this->getObjects($criteria);

		if (count($objArr) == 1) {
			$ret =& $objArr[0];
		}

		if ($ret == null) {
			$ret =& $this->create(true);
			foreach ($this->mPrimaryAry as $pkName) {
				$ret->set($pkName, $compsite[$pkName]);
			}
		}

		return $ret;
	}


	/**
	 * @Overwride
	 */
	function _update(&$obj) {
		$set_lists=array();
		//$where = "";
		$where_lists=array();

		$arr = $this->_makeVars4sql($obj);

		foreach ($arr as $_name => $_value) {
			//if ($_name == $this->mPrimary) {
			if (in_array($_name, $this->mPrimaryAry)) {
				//$where = "`${_name}`=${_value}";
				$where_lists[] = "`${_name}`=${_value}";
			}
			else {
				$set_lists[] = "`${_name}`=${_value}";
			}
		}

		$sql = @sprintf("UPDATE `" . $this->mTable . "` SET %s WHERE %s", implode(",",$set_lists), implode(" and ",$where_lists));

		return $sql;
	}

	/**
	 * Delete $obj.
	 *
	 * @return bool
	 */
	function delete(&$obj, $force = false)
	{
		//
		// Because Criteria can generate the most appropriate sentence, use
		// criteria even if this approach is few slow.
		//
		//$criteria =& new Criteria($this->mPrimary, $obj->get($this->mPrimary));
		$criteria =& new CriteriaCompo();
		foreach ($this->mPrimaryAry as $key) {
			$criteria->add(new Criteria($key, $obj->get($key)));
		}
        $sql = "DELETE FROM `" . $this->mTable . "` WHERE " . $this->_makeCriteriaElement4sql($criteria, $obj);

		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

	protected function escape($string) {
		if (get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}
		return mysql_real_escape_string($string);
	}

}
?>