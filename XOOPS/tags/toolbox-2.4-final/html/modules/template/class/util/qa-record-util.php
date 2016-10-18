<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
 * @author kitajima
 */
class QaRecordUtil {
	
	/**
	 * 
	 * @param ToolboxVO_TranslationTemplate_TranslationTemplateRecord $record
	 * @return array
	 */
	public static function buildRecord($record) {
		return array(
			'questionId' => $record->id,
			'parameterIds' => $record->wordSetIds,
			'categoryIds' => $record->categoryIds,
			'expressions' => self::toolboxVos2expressions($record->expressions)
		);
	}

	/**
	 * 
	 * @param array<String, String> $expressions
	 * @return ToolboxVO_Resource_Expression[]
	 */
	public static function expressions2toolboxVos($expressions) {
		$vos = array();
		foreach ($expressions as $language => $expression) {
			$vo = new ToolboxVO_Resource_Expression();
			$vo->language = $language;
			$vo->expression = $expression;
			$vos[] = $vo;
		}
		
		return $vos;
	}

	/**
	 * 
	 * @param ToolboxVO_Resource_Expression[]
	 * @return array<String, String> $expressions
	 */
	public static function toolboxVos2expressions($vos) {
		$expressions = array();
		foreach ($vos as $vo) {
			$expressions[$vo->language] = $vo->expression;
		}
		
		return $expressions;
	}
}
?>