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
require_once(dirname(__FILE__).'/Toolbox_ObjectGenericHandler.class.php');

class FileSharing_TextObject extends XoopsSimpleObject {
	function FileSharing_TextObject() {
		$this->initVar('lid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('description', XOBJ_DTYPE_STRING, '', true);
	}
}

class FileSharing_TextHandler extends XoopsObjectGenericHandler {
	var $mTable = "filesharing_text";
	var $mPrimary = "lid";
	var $mClass = "FileSharing_TextObject";
}
?>