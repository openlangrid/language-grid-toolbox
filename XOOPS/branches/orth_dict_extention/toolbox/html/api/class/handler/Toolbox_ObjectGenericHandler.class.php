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

/**
 *
 * $Id: Toolbox_ObjectGenericHandler.class.php 6209 2011-11-30 02:34:21Z mtanaka $
 */
class Toolbox_ObjectGenericHandler extends XoopsObjectGenericHandler {

	function delete(&$obj, $force = false)
	{
		$criteria = new Criteria($this->mPrimary, $obj->get($this->mPrimary));
        $sql = "UPDATE `" . $this->mTable . "` SET `delete_flag` = '1', `update_date` = ".time()." WHERE " . $this->_makeCriteriaElement4sql($criteria, $obj);

		return $force ? $this->db->queryF($sql) : $this->db->query($sql);
	}

//	function _insert(&$obj, $force = false) {
//		$sql = parent::_insert($obj, $force);
//
//		echo '<pre>'.$sql.'</pre><br>';
//
//		return $sql;
//	}

}
?>