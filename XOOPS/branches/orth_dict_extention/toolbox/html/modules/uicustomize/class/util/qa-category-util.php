<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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

require_once XOOPS_ROOT_PATH.'/api/IResourceVO.interface.php';

/**
 * $Id: qa-category-util.php 3662 2010-06-16 02:22:17Z yoshimura $
 */
class QaCategoryUtil {

	/**
	 *
	 * @param ToolboxVO_QA_QACategory $category
	 * @return array
	 */
	public static function buildCategory($category) {
		$return = array(
			'language' => $category->language,
			'count' => $category->qCount
		);
		foreach ($category->name as $exp) {
			$return[$exp->language] = $exp->expression;
		}
		return $return;
	}
}
?>