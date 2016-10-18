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

require_once(dirname(__FILE__).'/Toolbox_FileSharing_AbstractManager.class.php');

class Toolbox_FileSharing_FolderGetAllManager extends Toolbox_FileSharing_AbstractManager {
	public function __construct() {
		parent::__construct();
	}

	function getFolderList($parentId = null,$offset = null, $limit = null) {

		$mCriteria = new CriteriaCompo();
		if(is_numeric($parentId)){
			$mCriteria->add(new Criteria('pid', $parentId));
		}
		$objects =& $this->m_folderHandler->getObjects($mCriteria, $limit, $offset);
		
		$FolderArray = array();

		foreach ($objects as &$object) {
			$FolderArray[] = $this->folderObject2responseVo($object);
		}

		return $this->getResponsePayload($FolderArray);
	}


	function getFolder($id) {
		$object =& $this->m_folderHandler->get($id);
		if($object){
			return $this->getResponsePayload($this->folderObject2responseVo($object));
		}else{
			return $this->getErrorResponsePayload("no match.");
		}
	}
}
?>