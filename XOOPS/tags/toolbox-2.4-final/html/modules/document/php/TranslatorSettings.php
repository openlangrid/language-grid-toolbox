<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
require_once(XOOPS_ROOT_PATH.'/modules/langrid/class/get-supported-language-pair-class.php');

class TranslatorSettings{
	public function getSupportedLanguageTags(){
		$sPair = new GetSupportedLanguagePair();
		$pairs = $sPair->getLanguageNamePair();

		$languages = array();
		$languages['source'] = array();
		$languages['target'] = array();
		$languages['source']['default'] = null;
		$languages['target']['default'] = null;
		$sourceOptions = "";
		$targetOptions = "";
		$sourceBuffer = array();
		$targetBuffer = array();


		$sourceLangNames = array();
		foreach($pairs as $pair){
			$code = $pair[0]['code'];
			$name = $pair[0]['name'];
			$sourceLangNames[$name] = $code;
		}
		ksort($sourceLangNames);
		foreach($sourceLangNames as $name => $code){
			if(!in_array($code,  $sourceBuffer)){
				if($code == 'en'){
					$sourceOptions .= "<option value='" . $code . "' selected >" . $name . "</option>";
					$languages['source']['default']['code'] = $code;
					$languages['source']['default']['name'] = $name;
				}else{
					$sourceOptions .= "<option value='" . $code . "' >" . $name . "</option>";
					$languages['source']['default']['code'] = null;
					$languages['source']['default']['name'] = null;
				}
				$sourceBuffer[] = $code;
			}
		}
		if($languages['source']['default']['code'] == null){
			$languages['source']['default']['code'] = @$sourceBuffer[0];
		}

		$default = $languages['source']['default']['code'];
		$targetLangNames = array();
		foreach($pairs as $pair){
			$code = $pair[1]['code'];
			$name = $pair[1]['name'];
			if($pair[0]['code'] == $default){
				$targetLangNames[$name] = $code;
			}
		}
		ksort($targetLangNames);
		foreach($targetLangNames as $name => $code){
			if($code == 'ja'){
				$targetOptions .= "<option value='" . $code . "' selected >".$name . "</option>";
				$languages['target']['default']['code'] = $code;
				$languages['target']['default']['name'] = $name;
			}else{
				$targetOptions .= "<option value='" . $code . "' >" . $name . "</option>";
				$languages['target']['default']['code'] = null;
				$languages['target']['default']['name'] = null;
			}
			if(! in_array($code, $targetBuffer)){
				$targetBuffer[] = $code;
			}
		}
		if($languages['target']['default']['code'] == null){
			$languages['target']['default']['code'] = @$targetBuffer[0];
		}

		$languages['source']['tags'] = $sourceOptions;
		$languages['target']['tags'] = $targetOptions;
		return $languages;
	}

	public function getTargetLanguageTags($sourceLang = 'en', $pairs = null){
		if($pairs == null){
			$pair = new GetSupportedLanguagePair();
			$pairs = $pair->getLanguageNamePair();
		}
		$targetLangNames = array();
		foreach($pairs as $pair){
			$code = $pair[1]['code'];
			$name = $pair[1]['name'];
			if($pair[0]['code'] == $sourceLang){
				$targetLangNames[$name] = $code;
			}
		}
		ksort($targetLangNames);
		$targetOptions = '';
		foreach($targetLangNames as $name => $code){
			if($code == 'ja'){
				$targetOptions .= "<option value='" . $code . "' selected >" . $name . "</option>";
			}else{
				$targetOptions .= "<option value='" . $code . "' >" . $name . "</option>";
			}
		}
		return $targetOptions;
	}
}

?>