<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

require_once XOOPS_MODULE_PATH.'/langrid/php/service/other/HtmlTextExtractorClient.class.php';

class HtmlPathTranslator {

	public function __construct() {
	}

	/**
	 * <#if local="ja">
	 *
	 * HTMLとURLを受け取り、相対パスを絶対パスに変換する
	 *
	 * </#if>
	 * @param unknown_type $contents
	 * @param unknown_type $url
	 */
	public function translate($contents, $url = '') {
		$client = new HtmlTextExtractorClient();
		$result = $client->separate($contents, $url);

		if ($result['status'] != 'OK') {
			return $contents;
		}

		$return = $result['contents']['contents']->skeletonHtml;

		// $1 -> $1, $10, $11, $12,...
		$cts = array_reverse($result['contents']['contents']->codesAndTexts);

		foreach ($cts as $ct) {
			$return = str_replace($ct->code, $ct->text, $return);
		}

		return $return;
	}
}
?>