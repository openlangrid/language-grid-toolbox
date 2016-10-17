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
require_once(dirname(__FILE__).'/../class/PathSettingWrapperClass.php');
require_once(dirname(__FILE__).'/../include/Functions.php');

/**
 * <#if locale="en">
 * Class for saving translation settings
 * <#elseif locale="ja">
 * 翻訳設定保存クラス
 * </#if>
 */
class SavePageDict extends LanguageGridAjaxRunner {
	function dispatch($action, $params) {
		$data = array();
		try {
			$tokens = explode('&', urldecode($params));
			foreach ($tokens as $item) {
				$keyval = explode('=', $item);
				$data[$keyval[0]] = urldecode($keyval[1]);
			}
		} catch(Exception $e) { }

		if ($data == null) {
			die('Data is null.');
		}

		$uid = '0';
		$idUtil =& new LanguageGridArticleIdUtil();
		$setId = $idUtil->checkSetIdByPageTitle($data['title_db_key']);

		$pathSetting =& new PathSettingWapperClass();

		$contents = array();
		$afterIds = '';
		$ids = explode(',', $data['id']);
		if (count($ids) == 1) {
			$afterIds = $pathSetting->saveTranslationSetting($uid, $setId, $data);
			if ($data['flow1'] == 'both') {
				$data2 = postDateRevs($data);
				$data2['id'] = '';
				$tmp = $pathSetting->saveTranslationSetting($uid, $setId, $data2);
				if ($tmp) {
					$pathSetting->linkTranslation($afterIds,$tmp);
					$afterIds .= ','.$tmp;
				}
			}
		} else {
			$data1 = $data;
			$data1['id'] = $ids[0];
			$afterIds = $pathSetting->saveTranslationSetting($uid, $setId, $data1);
			$data2 = postDateRevs($data);
			$data2['id'] = $ids[1];
			if ($data2['flow1'] == 'left') {
				$data2['isDelete'] = 'yes';
			}
			$tmp = $pathSetting->saveTranslationSetting($uid, $setId, $data2);
			if ($tmp) {
				$pathSetting->linkTranslation($afterIds,$tmp);
				$afterIds .= ','.$tmp;
			}
		}
		$contents[$data['index']] = $afterIds;

		return $contents;
	}
}
?>