<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
class UnsupportedLanguagePairException extends Exception{
	private $soapMessage;

	public function __construct($message, $code = 0) {
        parent::__construct($message, $code);
    }

	public function getSoapMessage(){
		return $this->soapMessage;
	}

	public function setSoapMessage($parameterName, $parameter, $hostName){
		$template = file_get_contents(dirname(__FILE__).'/templates/UnsupportedLanguagePairException.soap.template');
		$template = preg_replace('/\$\{parameter\}/', $parameter, $template);
		$template = preg_replace('/\$\{parameterName\}/', $parameterName, $template);
		$template = preg_replace('/\$\{hostname\}/', $hostName, $template);
		$template = preg_replace('/\\t/', '', $template);
		$template = preg_replace('/\\n/', '', $template);
		$template = preg_replace('/\\r/', '', $template);
		$this->soapMessage = $template;
	}
}
?>
