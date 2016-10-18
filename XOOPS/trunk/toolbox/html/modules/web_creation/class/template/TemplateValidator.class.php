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

require_once APP_ROOT_PATH.'/class/validator/Validator.class.php';

class TemplateValidator extends Validator {

	protected $template;
	
	/**
	 * Constructor
	 * @param unknown_type $template
	 */
	public function __construct($template) {
		parent::__construct();
		$this->template = $template;
	}
}

class LineBasedTemplateValidator extends TemplateValidator {
	
	protected function validate() {
		parent::validate();
		
		$source = new StandardsHtmlTokenizer($this->template['source']);
		$target = new StandardsHtmlTokenizer($this->template['target']);
		
		$sourceTokens = $source->countTokens();
		$targetTokens = $target->countTokens();
		
		if ($sourceTokens == 0) {
			$this->errorMessages[] = '';
		}
		
		if ($targetTokens == 0) {
			$this->errorMessages[] = '';
		}
		
		if ($sourceTokens != $targetTokens) {
			$this->errorMessages[] = '';
		}
	}
}
?>