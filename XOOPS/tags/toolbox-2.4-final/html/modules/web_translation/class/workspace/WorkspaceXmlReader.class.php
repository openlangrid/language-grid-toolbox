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

require_once APP_ROOT_PATH.'/class/template/TemplateXmlReader.class.php';
require_once APP_ROOT_PATH.'/class/toolbox/FileSharingAdapter.class.php';
require_once APP_ROOT_PATH.'/class/workspace/WorkspaceXmlValidator.class.php';

class WorkspaceXmlReader {

	private $xml;

	public function __construct($contents) {
		libxml_use_internal_errors(true);

		$this->xml = simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA);

		$errors = '';
		foreach (libxml_get_errors() as $xmlerror) {
			$errors .= $xmlerror->message;
		}
		if ($errors) {
			throw new Exception($errors);
		}

		if (!$this->valid()) {
			throw new Exception('not valid');
		}
	}

	/**
	 * @return bool
	 */
	private function valid() {
		$validator = new WorkspaceXmlValidator($this->xml);
		return $validator->valid();
	}

	public function read() {
		$return = array();

		$return['sourceLanguage'] = (string) $this->xml->sourceLang;
		$return['targetLanguage'] = (string) $this->xml->targetLang;

		$return['result'] = array();
		foreach ($this->xml->htmlSource->line as $line) {
			$return['result'][] = array(
				'status' => (string) $line->attributes()->status,
				'source' => (string) $line->source,
				'target' => (string) $line->target
			);
		}

		$return['templates'] = array();
		foreach ($this->xml->currentTemplate->pair as $pair) {
			$return['templates'][] = array(
				'source' => (string) $pair->source,
				'target' => (string) $pair->target
			);
		}

		$return['appliedTemplates'] = array();
		$client = FileSharingAdapter::factory(FileSharingAdapterType::WORKSPACE);
		foreach ($this->xml->appliedTemplates->template as $id) {
			try {
				$file = $client->getFile($id);
				$name = $file->name;
				$contents = $client->read($id);

				$reader = new TemplateXmlReader($contents);
				$templates = $reader->read();

				$return['appliedTemplates'][] = array(
					'id' => (string) $id,
					'name' => $name,
					'template' => $templates
				);
			} catch (Exception $e) {
				;
			}
		}

		return $return;
	}
}
?>