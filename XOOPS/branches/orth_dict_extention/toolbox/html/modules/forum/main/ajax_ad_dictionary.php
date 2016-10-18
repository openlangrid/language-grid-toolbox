<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2013  Department of Social Informatics, Kyoto University
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

require '../../../mainfile.php';
require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';
require_once dirname(__FILE__).'/../class/permission/permission.php';
require_once dirname(__FILE__).'/../../../api/class/client/DictionaryClient.class.php';
require_once dirname(__FILE__).'/../../../api/class/client/ResourceClient.class.php';

/**/

try{
	switch($_POST['ad_mode']){
		case 0:
		/* get data */
			$sourceLanguage = $_POST['sourceLanguage'];

			$rclient = new ResourceClient();
			$ad_Dictionary=$rclient ->getAllLanguageResources('dictionary');
			$dictlist=array();
			foreach($ad_Dictionary['contents'] as $adDict){
				$langflg = 0;
				foreach($adDict->languages as $adlnglist){
					if($adlnglist==$sourceLanguage){$langflg=1; }
				}
				if($langflg==1){
					$dictlist[] =array( "dictVal"=> $adDict->name);
				}
			}
			
			$ad_LanguageResource=$rclient ->getLanguageResource($ad_Dictionary['contents'][0]->name);
			$langlist=array();
			foreach($ad_LanguageResource['contents']->languages as $adLR){
				$langlist[]=array( "langTag" => $adLR ,  "langVal" =>$LANGRID_LANGUAGE_ARRAY[$adLR]);
			}
			
			$returnText = array();
			$returnText=array(
				"labelSourceLang"=>$LANGRID_LANGUAGE_ARRAY[$sourceLanguage], 
				"selectTargetLang"=>$langlist, 
				"selectDictionary"=>$dictlist);

			echo json_encode($returnText);
			break;

		case 1:
			$dctclient = new DictionaryClient();
			$exps = array();
			$exp0 = new ToolboxVO_Resource_Expression();
			$exp1 = new ToolboxVO_Resource_Expression();

			$exp0 -> language = $_POST['sourceLanguage'];
			$exp0 -> expression = $_POST['sourceExpression'];
			$exps[0] = $exp0;
			$exp1 -> language = $_POST['targetLanguage'];
			$exp1 -> expression = $_POST['targetExpression'];
			$exps[1] = $exp1;
			$dctclient -> addRecord($_POST['dictionaryName'], $exps);
			break;

		case 2:
			$rclient = new ResourceClient();

			$sourceLanguage = $_POST['sourceLanguage'];
			$ad_LanguageResource=$rclient ->getLanguageResource($_POST['dictionaryName']);
			$targetLanguages=array();

			$ad_LanguageResource=$rclient ->getLanguageResource($_POST['dictionaryName']);

			$returnText = array();
			foreach($ad_LanguageResource['contents']->languages as $adLR){
				$returnText[]=array( "langTag" => $adLR ,  "langVal" =>$LANGRID_LANGUAGE_ARRAY[$adLR]);
			}
			echo json_encode($returnText);
			break;
	}
}catch(Exception $e){
	echo  $e->getMessage();
}

?>




