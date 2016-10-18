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

require_once APP_ROOT_PATH.'/class/template/TemplateXmlValidator.class.php';

class TemplateXmlReader {
	
	private $xml;
	
	public function __construct($contents) {
		$this->xml = simplexml_load_string($contents, 'SimpleXMLElement', LIBXML_NOCDATA);
	
		if (!$this->valid()) {
			throw new Exception(_MI_WEB_CREATION_SELECTED_FILE_IS_INVALID);
		}
	}
	
	private function valid() {
		$validator = new TemplateXmlValidator($this->xml);
		return $validator->valid();
	}
	
	public function read() {
		$return = array();

		foreach ($this->xml->pair as $pair) {
			$return[] = array(
				'source' => (string) $pair->source,
				'target' => (string) $pair->target
			);
		}

		return $return;
	}
}
?>