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
require_once XOOPS_ROOT_PATH.'/api/IResourceVO.interface.php';

/**
 * @author kitajima
 */
class QaRecordUtil {
	
	/**
	 * 
	 * @param ToolboxVO_QA_QARecord $record
	 * @return array
	 */
	public static function buildRecord($record) {
		$question = self::toolboxVos2expressions($record->question);
		
		$answers = array();
		foreach ($record->answers as $answer) {
			$answers[] = self::toolboxVos2answers($answer);
		}
		
		return array(
			'questionId' => $record->id,
			'categoryIds' => $record->categoryIds,
			'expressions' => $question,
			'answers' => $answers
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
	 * @param array<String, String> $answer
	 * @return ToolboxVO_Resource_Expression[]
	 */
	public static function answer2toolboxVo($answer) {
		$answerVO = new ToolboxVO_QA_Answer();
		$answerVO->id = $answer['answerId'];
		$answerVO->creationDate = $answer['creationDate'];
		
		unset($answer['answerId']);
		
		$answerVO->expression = array();
		foreach ($answer as $language => $expression) {
			$vo = new ToolboxVO_Resource_Expression();
			$vo->language = $language;
			$vo->expression = $expression;
			$answerVO->expression[] = $vo;
		}
		
		return $answerVO;
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

	/**
	 * 
	 * @param ToolboxVO_QA_Answer[]
	 * @return array<String, String> $expressions
	 */
	public static function toolboxVos2answers($vos) {
		$expressions = array();
		foreach ($vos->expression as $vo) {
			$expressions[$vo->language] = $vo->expression;
		}
		
		$expressions['answerId'] = $vos->id;
		$expressions['creationDate'] = $vos->creationDate;
		
		return $expressions;
	}
}
?>