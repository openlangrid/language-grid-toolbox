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

require_once APP_ROOT_PATH.'/class/action/AjaxAction.class.php';
require_once APP_ROOT_PATH.'/class/toolbox/FileSharingAdapter.class.php';

class SaveWorkspaceAction extends AjaxAction {

	public function execute() {
		parent::execute();

		try {
			$file = FileSharingAdapter::factory(FileSharingAdapterType::WORKSPACE);
//			$file->save($this->getParameter('fileName').'.xml', $this->createXml());

			$context = array(
				'folderId' => $this->getParameter('folderId'),
				'fileName' => $this->getFileName($this->getParameter('fileName')),
				'description' => $this->getParameter('description'),
				'readPermission' => $this->getParameter('readPermission'),
				'editPermission' => $this->getParameter('editPermission'),
			);
			$file->save($context, $this->createXml());

			$this->buildSuccessResult(null);
		} catch (Exception $e) {
			$this->buildErrorResult($e->getMessage());
		}
	}

	private function createXml() {
		$tagetLang = $this->getParameter('targetLanguage');
		$htmls = $this->getParameter('result');

		if (!is_array($htmls)) $htmls = array();

		$templates = $this->getParameter('templates');
		$appliedTemplates = $this->getParameter('appliedTemplates');

		if (!is_array($templates)) $templates = array();
		if (!is_array($appliedTemplates)) $appliedTemplates = array();

		$xml = array();

		$xml[] = '<?xml version="1.0"?>';
		$xml[] = '<root app="web_creation">';
		$xml[] = '  <sourceLang>'.$this->getParameter('sourceLanguage').'</sourceLang>';
		$xml[] = '  <targetLang>'.$this->getParameter('targetLanguage').'</targetLang>';

		$xml[] = '  <appliedTemplates>';
		foreach ($appliedTemplates as $t) {
			$xml[] = '    <template>'.$t['id'].'</template>';
		}
		$xml[] = '  </appliedTemplates>';

		$xml[] = '  <htmlSource>';
		foreach ($htmls as $index => $html) {
			$xml[] = '    <line id="'.($index+1).'" status="'.$html['status'].'">';
			$xml[] = '      <source><![CDATA['.$this->escapeCDATA($html['source']).']]></source>';
			$xml[] = '      <target><![CDATA['.$this->escapeCDATA($html['target']).']]></target>';
			$xml[] = '  </line>';
		}
		$xml[] = '  </htmlSource>';

		$xml[] = '  <currentTemplate>';
		foreach ($templates as $t) {
			$xml[] = '    <pair>';
			$xml[] = '      <source><![CDATA['.$this->escapeCDATA($t['source']).']]></source>';
			$xml[] = '      <target><![CDATA['.$this->escapeCDATA($t['target']).']]></target>';
			$xml[] = '    </pair>';
		}
		$xml[] = '  </currentTemplate>';
		$xml[] = '  <histories>';
		$xml[] = '  </histories>';
		$xml[] = '</root>';

		return implode("\n", $xml);
	}

	private function escapeCDATA($str) {
		if (empty($str)) {
			return $str;
		}

		$str = preg_replace('/<!\[CDATA\[/', '', $str);
		$str = preg_replace('/\]\]>/', '', $str);

		return $str;
	}

	private function getFileName($base) {
		return $base . '.xml';
	}

}
?>