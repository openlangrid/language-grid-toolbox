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

function getLanguagePairScript(){
	$sPair = new GetSupportedLanguagePair();
	$pairs = $sPair->getLanguageNamePair();
	$buff = "";

	$sourceLangNames = array();
	foreach($pairs as $pair){
		$code = $pair[0]['code'];
		$name = $pair[0]['name'];
		$sourceLangNames[$name] = $code;
	}
	ksort($sourceLangNames);

	foreach($sourceLangNames as $sName => $sCode){
		$targetLangNames = array();
		foreach($pairs as $pair){
			$code = $pair[1]['code'];
			$name = $pair[1]['name'];
			if($pair[0]['code'] == $sCode){
				$targetLangNames[$name] = $code;
			}
		}
		ksort($targetLangNames);

		$buff .= "LangPairs['".$sCode."'] = ".json_encode($targetLangNames).";\n";

	}

	$Ret = <<<EOM
	<script type="text/javascript" language="javascript">
		var LangPairs = {};
		{$buff}
	</script>

EOM;
	return $Ret;
}
?>