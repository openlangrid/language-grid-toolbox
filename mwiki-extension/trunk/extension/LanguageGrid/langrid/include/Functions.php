<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/Languages.php');

/**
 * <#if locale="en">
 * Transform a language path in the form of [en2ja] into [Japanese<->English]
 * <#elseif locale="ja">
 * [en2ja]形式の言語パスを[Japanese<->English]形式に変換
 * </#if>
 */
function languagePair($langTexts) {
	$paths = str_replace('"', '', $langTexts);
	$pathArray = explode(',', $paths);

	$dist = array();
	$codes = array();
	while (count($pathArray) > 0) {
		$pairs = $pathArray[0];
		$pair = explode('2', $pairs);

		$bak = $pair[1].'2'.$pair[0];
		if (in_array($bak, $pathArray)) {
			$codes[] = $pair[0].'4'.$pair[1];
			$dist[] = getLangridLanguageName($pair[0]).'4'.getLangridLanguageName($pair[1]);
		} else if (in_array($pair[1].'4'.$pair[0], $codes)) {
		} else {
			$codes[] = $pair[0].'2'.$pair[1];
			$dist[] = getLangridLanguageName($pair[0]).'2'.getLangridLanguageName($pair[1]);
		}
		array_shift($pathArray);
	}

	sort($dist);

	$ret = '';
	for ($i = 0; $i < count($dist); $i++) {
		$ret .= str_replace('2', '-&gt;', str_replace('4', '&lt;-&gt;', $dist[$i])).'<br />';
	}
	return $ret;
}

/**
 * <#if locale="en">
 * Convert a language code to the language name
 * <#elseif locale="ja">
 * 言語コードを言語名称に変換
 * </#if>
 */
function getLangridLanguageName($code) {
	$LANGRID_LANGUAGE_ARRAY = GetLangridLanguageDefine();
	if (isset($LANGRID_LANGUAGE_ARRAY[$code])) {
		return $LANGRID_LANGUAGE_ARRAY[$code];
	} else {
		return 'No define ['.$code.']';
	}
}

// unused
//function mergeSetting($settings) {
//	$response = array();
//
//	$keyArray = array();
//
//	foreach ($settings as $setting) {
//		$data = array();
//		$data['id'] = $setting['id'];
//		$data['source_lang'] = $setting['source_lang'];
//		$data['target_lang'] = $setting['target_lang'];
//		$data['inter_lang_1'] = $setting['inter_lang_1'];
//		$data['inter_lang_2'] = $setting['inter_lang_2'];
//		$data['translator_service_1'] = $setting['translator_service_1'];
//		$data['translator_service_2'] = $setting['translator_service_2'];
//		$data['translator_service_3'] = $setting['translator_service_3'];
//		$data['flow'] = 'left';
//
////*********test*************************
//		$data['global_dict_1'] = $setting['bind_global_dict_ids'];
//		$data['local_dict_1'] = $setting['bind_local_dict_ids'];
//		$data['temp_dict_1'] = $setting['bind_user_dict_ids'];
//		$data['dict_flag_1'] = $setting['dictionary_flag'];
//
//		$data['global_dict_2'] = $setting['bind_global_dict_ids'];
//		$data['local_dict_2'] = $setting['bind_local_dict_ids'];
//		$data['temp_dict_2'] = $setting['bind_user_dict_ids'];
//		$data['dict_flag_2'] = $setting['dictionary_flag'];
//
//		$data['global_dict_3'] = $setting['bind_global_dict_ids'];
//		$data['local_dict_3'] = $setting['bind_local_dict_ids'];
//		$data['temp_dict_3'] = $setting['bind_user_dict_ids'];
//		$data['dict_flag_3'] = $setting['dictionary_flag'];
////**************************************
//
//		$keys = array();
//		$keys[] = $data['source_lang'];
//		$keys[] = $data['translator_service_1'];
//		$keys[] = $data['inter_lang_1'];
//		$keys[] = $data['translator_service_2'];
//		$keys[] = $data['inter_lang_2'];
//		$keys[] = $data['translator_service_3'];
//		$keys[] = $data['target_lang'];
//
//		$data['keys'] = implode('', $keys);
//		$data['revs'] = implode('', array_reverse($keys));
//
//		if (array_key_exists($data['revs'], $keyArray)) {
//			$response[$keyArray[$data['revs']]]['id'] = $response[$keyArray[$data['revs']]]['id'].','.$data['id'];
//			$response[$keyArray[$data['revs']]]['flow'] = 'both';
//		} else {
//			$response[$data['id']] = $data;
//			$keyArray[$data['keys']] = $data['id'];
//		}
//
//	}
//	return array_merge($response);
//}

/**
 * <#if locale="en">
 * Invert the direction of uni-directional language path to generate setting for back-translation.
 * <#elseif locale="ja">
 * 一方向の翻訳パス設定データを逆向きに変換して、"折返し翻訳"用の設定データを生成
 * </#if>
 */
function postDateRevs($inData) {
	$revsData = $inData;
	if ($revsData['service3']) {
		$revsData['lang1'] = $inData['lang4'];
		$revsData['lang2'] = $inData['lang3'];
		$revsData['lang3'] = $inData['lang2'];
		$revsData['lang4'] = $inData['lang1'];
		$revsData['service1'] = $inData['service3'];
		$revsData['service2'] = $inData['service2'];
		$revsData['service3'] = $inData['service1'];
		$revsData['global_dict_1'] = $inData['global_dict_3'];
		$revsData['global_dict_2'] = $inData['global_dict_2'];
		$revsData['global_dict_3'] = $inData['global_dict_1'];
		$revsData['local_dict_1'] = $inData['local_dict_3'];
		$revsData['local_dict_2'] = $inData['local_dict_2'];
		$revsData['local_dict_3'] = $inData['local_dict_1'];
		$revsData['temp_dict_1'] = $inData['temp_dict_3'];
		$revsData['temp_dict_2'] = $inData['temp_dict_2'];
		$revsData['temp_dict_3'] = $inData['temp_dict_1'];
		$revsData['dict_flag_1'] = $inData['dict_flag_3'];
		$revsData['dict_flag_2'] = $inData['dict_flag_2'];
		$revsData['dict_flag_3'] = $inData['dict_flag_1'];
		$revsData['morph_analyzer1'] = $inData['morph_analyzer4'];
		$revsData['morph_analyzer2'] = $inData['morph_analyzer3'];
		$revsData['morph_analyzer3'] = $inData['morph_analyzer2'];
		$revsData['morph_analyzer4'] = $inData['morph_analyzer1'];
	} else if ($inData['service2']) {
		$revsData['lang1'] = $inData['lang3'];
		$revsData['lang2'] = $inData['lang2'];
		$revsData['lang3'] = $inData['lang1'];
		$revsData['lang4'] = '';
		$revsData['service1'] = $inData['service2'];
		$revsData['service2'] = $inData['service1'];
		$revsData['service3'] = '';
		$revsData['global_dict_1'] = $inData['global_dict_2'];
		$revsData['global_dict_2'] = $inData['global_dict_1'];
		$revsData['global_dict_3'] = '';
		$revsData['local_dict_1'] = $inData['local_dict_2'];
		$revsData['local_dict_2'] = $inData['local_dict_1'];
		$revsData['local_dict_3'] = '';
		$revsData['temp_dict_1'] = $inData['temp_dict_2'];
		$revsData['temp_dict_2'] = $inData['temp_dict_1'];
		$revsData['temp_dict_3'] = '';
		$revsData['dict_flag_1'] = $inData['dict_flag_2'];
		$revsData['dict_flag_2'] = $inData['dict_flag_1'];
		$revsData['dict_flag_3'] = '';
		$revsData['morph_analyzer1'] = $inData['morph_analyzer3'];
		$revsData['morph_analyzer2'] = $inData['morph_analyzer2'];
		$revsData['morph_analyzer3'] = $inData['morph_analyzer1'];
		$revsData['morph_analyzer4'] = '';
	} else {
		$revsData['lang1'] = $inData['lang2'];
		$revsData['lang2'] = $inData['lang1'];
		$revsData['lang3'] = '';
		$revsData['lang4'] = '';
		$revsData['service1'] = $inData['service1'];
		$revsData['service2'] = '';
		$revsData['service3'] = '';
		$revsData['global_dict_1'] = $inData['global_dict_1'];
		$revsData['global_dict_2'] = '';
		$revsData['global_dict_3'] = '';
		$revsData['local_dict_1'] = $inData['local_dict_1'];
		$revsData['local_dict_2'] = '';
		$revsData['local_dict_3'] = '';
		$revsData['temp_dict_1'] = $inData['temp_dict_1'];
		$revsData['temp_dict_2'] = '';
		$revsData['temp_dict_3'] = '';
		$revsData['dict_flag_1'] = $inData['dict_flag_1'];
		$revsData['dict_flag_2'] = '';
		$revsData['dict_flag_3'] = '';
		$revsData['morph_analyzer1'] = $inData['morph_analyzer2'];
		$revsData['morph_analyzer2'] = $inData['morph_analyzer1'];
		$revsData['morph_analyzer3'] = '';
		$revsData['morph_analyzer4'] = '';
	}
	return $revsData;
}


?>