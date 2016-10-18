<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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
require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
class NullTranslatorClient extends ServiceClient {

	private $src = '';
	private $tgt = '';

	public function __construct($src, $tgt) {
		parent::__construct('NoTranslator');
		$this->src = $src;
		$this->tgt = $tgt;
	}

	public function translate($source) {
		$root =& XCube_Root::getSingleton();
		$modName = $root->mContext->mModule->mXoopsModule->get('name');
		$errorCode = '';
		if ($modName == 'document') {
			$errorCode = 'SAeou8oe9ugnjqka';
		}
		return array(
			'status' => 'Error',
			'message' => 'No translator is assigned for this translation path.',
			'contents' => array(
					'targetLanguage' => $this->tgt,
					'targetText' => array(
							'status' => 'Error',
							'contents' => $errorCode.'No translator is assigned for this translation path.'
					)
			)
		);
	}

	protected function makeBindingHeader($parameters){
		return '';
	}

	public function getServiceId() {
		return 'NullTranslatorClient['.$this->src.'2'.$this->tgt.']';
	}

	public function getSoapBindings() {
		return 'No Translated.';
	}

}
?>