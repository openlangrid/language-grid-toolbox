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

require_once APP_ROOT_PATH.'/class/action/IndexAction.class.php';

class UnitTestAction extends IndexAction {

	public function execute() {
		die();
//		$templates = array(
//			array(
//				'source' => 'SOURCE',
//				'target' => 'TARGET'
//			)
//		);
//
//		$doc = new DOMDocument('1.0');
//		$doc->formatOutput = true;
//
//		$root = $doc->createElement('root');
//		$root->setAttribute('app', 'template');
//
//		$doc->appendChild($root);
//
//		foreach ($templates as $t) {
//			$pair = $doc->createElement('pair');
//			$pair = $root->appendChild($pair);
//
//			$source = $doc->createElement('source');
//			$source->appendChild($doc->createCDATASection($t['source']));
//
//			$target = $doc->createElement('target');
//			$target->appendChild($doc->createCDATASection($t['target']));
//
//			$pair->appendChild($source);
//			$pair->appendChild($target);
//
//			$root->appendChild($pair);
//		}
//
//		echo $doc->saveXML();
//
//		die();
//		switch ($this->getParameter('operation')) {
//		case 'templateApplyer':
//			$this->runTemplateApplyerTest();
//			break;
//		case 'translation':
//			$this->runTranslationTest();
//			break;
//		}
	}

	private function runTranslationTest() {
		require_once APP_ROOT_PATH.'/class/translation/Translator.class.php';

		$client = new Develope_LangridAccessClient();

		$result = $client->multisentenceTranslate(
				'en', 'ja', array('Hello', 'Help')
				, 'USER', Toolbox_Develope_SourceTextJoinStrategyType::Normal
		);
	}

	private function runTemplateApplyerTest() {
		require_once APP_ROOT_PATH.'/class/template/TemplateApplyer.class.php';

		$template = array(
			'source' => '<html>',
			'target' => '<html2>'
		);

		$result = array(
			array(
				'status' => 'tag',
				'template' => false,
				'source' => '<html>',
				'target' => '<html>'
			),
			array(
				'status' => 'tag',
				'template' => false,
				'source' => '<head>',
				'target' => '</head>'
			)
		);

		$applyer = new TemplateApplyer();
		$applyer->apply($result, $template);

		var_dump($result);die();
	}

	public function executeView($render) {
		$render->setTemplateName('web_creation_unit_test.html');

		$this->loadCss($render);
	}
}
?>