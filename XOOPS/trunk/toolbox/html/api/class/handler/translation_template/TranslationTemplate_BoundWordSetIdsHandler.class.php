<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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

require_once(dirname(__FILE__).'/../Toolbox_ObjectGenericHandler.class.php');

class TranslationTemplate_BoundWordSetIdsObject extends XoopsSimpleObject {
	public function TranslationTemplate_BoundWordSetIdsObject() {
		$this->initVar('id', XOBJ_DTYPE_INT, 0, false);
	}
}

class TranslationTemplate_BoundWordSetIdsHandler extends XoopsObjectGenericHandler {

	public $mTable = "template_bound_word_set_ids";
	public $mPrimary = "id";
	public $mClass = "TranslationTemplate_BoundWordSetIdsObject";
}
?>