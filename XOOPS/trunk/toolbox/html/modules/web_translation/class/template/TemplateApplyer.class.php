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

require_once APP_ROOT_PATH.'/class/html/HtmlTokenizer.class.php';
require_once APP_ROOT_PATH.'/class/template/TemplateValidator.class.php';

class TemplateApplyer {

	public function __construct() {

	}

	private function valid($template) {
		$validator = new LineBasedTemplateValidator($template);
		return $validator->valid();
	}

	public function apply(&$result, $template) {

		if (!$this->valid($template)) {
			return;
		}

		$tokenizer = new StandardsHtmlTokenizer($template['source']);
		$sourceTemplate = $tokenizer->getTokens();

		$tokenizer = new StandardsHtmlTokenizer($template['target']);
		$targetTemplate = $tokenizer->getTokens();

		$templateLength = $tokenizer->countTokens();

		$applyKeys = array();

		foreach ($result as $key => $line) {

			if ($line['status'] == 'fixed' || $line['template']) {
				$applyKeys = array();
				continue;
			}

			$pointer = count($applyKeys);

//			if ($line['source'] != $sourceTemplate[$pointer]->token) {
			if (!$this->compear($line['source'], $sourceTemplate[$pointer]->token)) {
				$applyKeys = array();
				continue;
			}

			$applyKeys[] = $key;

			if ($templateLength == count($applyKeys)) {
				foreach ($applyKeys as $i => $applyKey) {
					$result[$applyKey]['target'] = $targetTemplate[$i]->token;
					$result[$applyKey]['template'] = true;
				}
				$applyKeys = array();
				continue;
			}
		}
	}

	private function compear($a, $b) {
		if ($a == $b) {
			return true;
		} else if (trim($a) == trim($b)) {
			return true;
		} else if (strtoupper($a) == strtoupper($b)) {
			return true;
		}
		return false;
	}
}
?>