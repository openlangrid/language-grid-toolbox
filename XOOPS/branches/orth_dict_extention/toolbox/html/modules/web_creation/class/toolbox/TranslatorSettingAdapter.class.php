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

require_once(APP_ROOT_PATH.'/class/toolbox/LanguageUtil.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');

class TranslatorSettingAdapter {

    /*
     * <#if locale="ja">
     * web_translationモジュールで利用するバインディングセット名
     * </#if>
     */
    const BINDING_NAME = 'USER';

	public function __construct() {

	}

	public function getLanguages() {
        $languages = array();

        $langridAccess = new LangridAccessClient();
        $apires = $langridAccess->getSupportedTranslationLanguagePairs(self::BINDING_NAME);
        if ($apires['status'] !== 'OK') {
            throw new Exception($apires['message']);
        }
        $pairs = $apires['contents'];

        foreach ($pairs as $pair) {
            $src = $pair[0];
            $tgt = $pair[1];

            $languages[$src][] = $tgt;
        }

		$this->sort($languages);

		return $languages;
	}

	private function sort(&$languages) {
		uksort($languages, array('LanguageUtil', 'sortAsc'));

		foreach ($languages as $key => $value) {
			usort($languages[$key], array('LanguageUtil', 'sortAsc'));
		}
	}
}