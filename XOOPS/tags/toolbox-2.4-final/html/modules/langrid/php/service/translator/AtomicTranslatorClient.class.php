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
require_once(dirname(__FILE__).'/ITranslatorClient.interface.php');
class AtomicTranslatorClient extends ServiceClient implements ITranslatorClient {

	private $setting = null;

	public function __construct($setting) {
		$this->setting = $setting;
		parent::__construct('wsdl/'.$this->setting['translatorService1']);
	}

	public function translate($source) {
		$parameters = array(
				'sourceLang' => $this->setting['sourceLang'],
				'targetLang' => $this->setting['targetLang'],
				'source' => $source);
		$res = parent::call('translate', $parameters);

		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			$result['contents'] = array(
					'targetLanguage' => $this->setting['targetLang'],
					'targetText' =>$res
				);

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'Translation successed.';
			$result['contents'] = array(
					'targetLanguage' => $this->setting['targetLang'],
					'targetText' => $res,
				);
		}

		$licenseArray = $this->getLicense();
		$result['licenseInformation'] = $licenseArray;

		return $result;
	}

	protected function getLicense() {
		$infoArray = array();
		$serviceId = $this->setting['translatorService1'];
		require_once(dirname(__FILE__).'/../manager/ServiceManagerClient.class.php');
		$manager =& new ServiceManagerClient();
		$self = (array)$manager->getServiceProfile($serviceId);

		//print_r($selfLicense);die();
		$infoArray[$serviceId] = array(
			'serviceName' => $self['serviceName'],
			'serviceCopyright' => $self['copyrightInfo'],
			'serviceLicense' => $self['licenseInfo'],
			'lastAccessDate' => date('D, j M Y G:i:s +0900')
		);

		return $infoArray;
	}

	protected function makeBindingHeader($parameters){
		return '';
	}

	public function getServiceId() {
		return 'AtomicTranslatorClient::'.$this->setting['translatorService1'];
	}

	public function getSoapBindings() {
		return 'This service is Atomic.';
	}
}
?>