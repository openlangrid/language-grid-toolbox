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
class BackTranslatorClient extends ServiceClient implements ITranslatorClient {

	protected $translationExecObject = null;
	protected $backExecObject = null;
	protected $bindingStr = null;

	public function BackTranslatorClient($translationExecObject, $backExecObject) {
		$this->translationExecObject = $translationExecObject;
		$this->backExecObject = $backExecObject;

//		$this->translationExecObject->set('service_id',
//			$this->_callUserHookFunction_replaceTranslatorServiceId($this->translationExecObject->get('service_id')));
		parent::ServiceClient('wsdl/BackTranslation');
	}

	public function translate($source) {

		$parameters = array(
				'sourceLang' => $this->translationExecObject->get('source_lang'),
				'intermediateLang' => $this->translationExecObject->get('target_lang'),
				'source' => $source
		);
		
		$fServiceId = $this->convertServiceId($this->translationExecObject->get('service_id'));
		$bServiceId = $this->convertServiceId($this->backExecObject->get('service_id'));

		$bindParams = array(
			'ForwardTranslationPL' => $fServiceId,
			'BackwardTranslationPL' => $bServiceId
		);

		$res = parent::call('backTranslate', $parameters, $bindParams);

		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			$result['contents'] = array(
					'targetLanguage' => $this->translationExecObject->get('target_lang'),
					'targetText' =>$res
				);

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'Translation successed.';
			$result['contents'] = array(
					'targetLanguage' => $this->translationExecObject->get('target_lang'),
					'targetText' => $res,
				);
		}

		$result['licenseInformation'] = $this->getLicenses();
		return $result;
	}

	protected function getLicenses() {
		$licenseArray = array();
		$licenseArray = $this->getLicense();
		$licenseArray = array_merge($licenseArray, $this->getCombineLicense());
		return $licenseArray;
	}

	protected function getLicense() {
		$infoArray = array();
		return $infoArray;
		$serviceId = $this->getServiceId();
		require_once(dirname(__FILE__).'/../manager/ServiceManagerClient.class.php');
		$manager =& new ServiceManagerClient();
		$self = (array)$manager->getServiceProfile($serviceId);

		$infoArray[$serviceId] = array(
			'serviceName' => $self['serviceName'],
			'serviceCopyright' => $self['copyrightInfo'],
			'serviceLicense' => $self['licenseInfo'],
			'lastAccessDate' => date('D, j M Y G:i:s +0900')
		);

		return $infoArray;
	}

	protected function getCombineLicense() {
		$infoArray = array();
		if (!is_array($this->callTree)) {
			return array();
		}
		foreach ($this->callTree as $obj) {
			$this->_parseTree($obj, &$infoArray);
		}

		return $infoArray;
	}

	protected function _parseTree($obj, &$result) {
		$obj = (array)$obj;
		if ($obj['faultCode'] == '' && count($obj['children']) == 0) {
			$serviceId = $obj['serviceId'];
			$result[$serviceId] = array(
				'serviceName' => $obj['serviceName'],
				'serviceCopyright' => $obj['serviceCopyright'],
				'serviceLicense' => $obj['serviceLicense'],
				'lastAccessDate' => date('D, j M Y G:i:s +0900')
			);
		}
		foreach ($obj['children'] as $child) {
			$this->_parseTree($child, &$result);
		}
	}

	protected function makeBindingHeader($bindParams){
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$bindNodes = array();


		$bindNodes[] = sprintf($bindTemp, '', 'ForwardTranslationPL', $bindParams['ForwardTranslationPL']);
		$bindNodes[] = sprintf($bindTemp, '', 'BackwardTranslationPL', $bindParams['BackwardTranslationPL']);

		$bindingStr = '['.implode(',', $bindNodes).']';

		$this->bindingStr = $bindingStr;
		return $bindingStr;
	}

	protected function _callUserHookFunction_replaceTranslatorServiceId($serviceId) {
		// call user hook function
		$pinfo = pathinfo(__FILE__);
		$hookfile = $pinfo['dirname'].'/hooks/TranslatorClient.hook.'.$pinfo['extension'];
		if (file_exists($hookfile)) {
			require_once($hookfile);
			$hookclass = 'TranslatorClient_Hook';
			if (class_exists($hookclass)) {
				$hook =& new $hookclass;
				$hookfunc = 'replaceTranslatorServiceId';
				if (method_exists($hook, $hookfunc)) {
					call_user_method($hookfunc, $hook, &$serviceId);
				}
			}
		}
		return $serviceId;
	}

	public function getServiceId() {
		return $this->getGridId() . ':BackTranslation';
	}

	public function getSoapBindings() {
		return $this->bindingStr;
	}

}
?>