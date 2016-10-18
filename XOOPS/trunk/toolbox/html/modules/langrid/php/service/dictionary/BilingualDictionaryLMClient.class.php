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

die('This code seems dead.');

class BilingualDictionaryLMClient extends ServiceClient {

	private $serviceId = null;

	public function __construct($serviceId) {
		$this->serviceId = $serviceId;
		parent::__construct('wsdl/'.$serviceId);
	}

	public function search($headLang, $targetLang, $headWord, $matchingMethod = 'PREFIX') {
		$parameters = array(
				'headLang' => $headLang,
				'targetLang' => $targetLang,
				'headWord' => $headWord,
				'matchingMethod' => $matchingMethod);
		$res = parent::call('search', $parameters);

		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			$result['contents'] = array();

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'Dictionary search successed.';
			$result['contents'] = $res;
		}
		return $result;
	}

	protected function makeBindingHeader($parameters){
		return '';
	}

	public function getServiceId() {
		return $this->serviceId;
	}

	public function getSoapBindings() {
		return 'This service is Atomic.';
	}
}
?>