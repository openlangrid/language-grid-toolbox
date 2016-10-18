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
require_once(dirname(__FILE__).'/ITranslatorClient.interface.php');
require_once(dirname(__FILE__).'/BindingTranslatorClient.class.php');

class CycleBackTranslator implements ITranslatorClient {

	protected $forwardPathObj = null;
	protected $backPathObj = null;
	protected $bindingStr = null;

	public function CycleBackTranslator($forwardPathObj, $backPathObj) {
		$this->forwardPathObj = $forwardPathObj;
		$this->backPathObj = $backPathObj;
	}

	public function translate($source) {

		$intermediate = $source;
		$licenseInformationArray = array();

		foreach ($this->forwardPathObj->getExecs() as $exec) {
			$transltor = new BindingTranslatorClient($exec);
			$result = $transltor->translate($intermediate);
			if ($result['status'] != 'OK') {
				return $result;
			}
			$intermediate = $result['contents']['targetText']['contents'];
			$licenseInformationArray = array_merge($licenseInformationArray, $result['licenseInformation']);
		}

		$target = $intermediate;

		foreach ($this->backPathObj->getExecs() as $exec) {
			$transltor = new BindingTranslatorClient($exec);
			$result = $transltor->translate($target);
			if ($result['status'] != 'OK') {
				return $result;
			}
			$target = $result['contents']['targetText']['contents'];
			$licenseInformationArray = array_merge($licenseInformationArray, $result['licenseInformation']);
		}

		$result['contents']['targetText']['contents'] = array('intermediate' => $intermediate, 'target' => $target);
		$result['licenseInformation'] = $licenseInformationArray;

		return $result;
	}

	public function getServiceId() {
		return 'CycleBackTranslator';
	}

	public function getSoapBindings() {
		return $this->bindingStr;
	}

}
?>