<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// Q&As.
// Copyright (C) 2010  CITY OF KYOTO
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
 * @author kitajima
 *
 */
class QaExcelUtil {
	public static function getColumnExpressions() {
		$columns = array();
		for ($char = ord('A'); $char <= ord('Z'); $char++) {
			$columns[] = $char;
		}
		for ($char1 = ord('A'); $char1 <= ord('C'); $char1++) {
			for ($char2 = ord('A'); $char2 <= ord('Z'); $char2++) {
				$columns[] = $char1.$char2;
			}
		}
		return $columns;
	}
}
?>