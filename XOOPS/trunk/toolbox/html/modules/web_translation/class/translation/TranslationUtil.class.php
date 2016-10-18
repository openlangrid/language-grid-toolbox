<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

require_once APP_ROOT_PATH.'/class/html/HtmlPathTranslator.class.php';
require_once APP_ROOT_PATH.'/class/html/HtmlTokenizer.class.php';
require_once APP_ROOT_PATH.'/class/http/HttpClient.class.php';

class TranslationUtil {

	/**
	 *
	 * @param String $str
	 * @return TranslationModel[]
	 */
	public static function html2translationModels($html, $url = '') {

		if ($html == '') {
			return $html;
		}

		$return = array();

		$pathTranslator = new HtmlPathTranslator();
		$translatedHtml = $pathTranslator->translate($html, $url);

		$tokenizer = new StandardsHtmlTokenizer($translatedHtml);

		while ($tokenizer->hasMoreTokens()) {
			$token = $tokenizer->nextToken();

			$status = $token->type;
			if ($token->type == 'text') {
				$status = 'unfixed';
			}

			$return[] = new TranslationModel($status, $token->token);
		}

		return $return;
	}
}
?>