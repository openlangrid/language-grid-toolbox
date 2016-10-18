<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: LanguageUtil.class.php 4654 2010-10-28 06:37:35Z yoshimura $ */

class LanguageUtil {

	public static function sort(&$languages, $sort = 'asc') {
		usort($languages, array(self, 'sort'.ucfirst($sort)));
	}

	public static function sortAsc($a, $b) {

		if ($a == $b) return 0;

		require XOOPS_ROOT_PATH.'/modules/langrid_config/include/Languages.php';

		foreach ($LANGRID_LANGUAGE_ARRAY as $key => $value) {
			if ($key == $a) {
				return -1;
			}
			if ($key == $b) {
				return 1;
			}
		}

		return 0;
	}

	public static function sortDesc($a, $b) {
		return self::sortAsc($b, $a);
	}
}
?>