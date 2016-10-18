<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

class WebCreationDisplayCacheObject extends XoopsSimpleObject {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->initVar('display_key', XOBJ_DTYPE_STRING, '', true, 32);
		$this->initVar('contents', XOBJ_DTYPE_STRING, '');
		$this->initVar('creation_time', XOBJ_DTYPE_INT, 0, true);
	}
}

class WebCreationDisplayCacheHandler extends XoopsObjectGenericHandler {
	
	public $mTable;
	public $mPrimary = "display_key";
	public $mClass = "WebCreationDisplayCacheObject";
	
	/**
	 * Constructor
	 * @param unknown_type $db
	 */
	public function __construct($db) {
		parent::XoopsObjectGenericHandler($db);
		$this->mTable = $this->db->prefix(APP_DIR_NAME.'_display_cache');
	}
}
?>
