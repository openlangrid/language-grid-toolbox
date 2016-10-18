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
class NoBindingTranslatorClient extends ServiceClient implements ITranslatorClient {

	private $exec = null;

	public function __construct($exec) {
		$this->exec = $exec;
		$this->exec->set('service_id', $this->_callUserHookFunction_replaceTranslatorServiceId($this->exec->get('service_id')));
		parent::__construct('wsdl/'.$this->exec->get('service_id'));
	}

	public function translate($source) {
		$sourceLang = $this->exec->get('source_lang');
		$targetLang = $this->exec->get('target_lang');

		$parameters = array(
				'sourceLang' => $sourceLang,
				'targetLang' => $targetLang,
				'source' => $source);
		$res = parent::call('translate', $parameters);

		if ( $res['status'] == 'ERROR' ) {
			$result['status'] = 'ERROR';
			$result['message'] = $res['message'];
			$result['contents'] = array(
					'targetLanguage' => $targetLang,
					'targetText' =>$res
				);

		} else {
			$result['status'] = 'OK';
			$result['message'] = 'Translation successed.';
			$result['contents'] = array(
					'targetLanguage' => $targetLang,
					'targetText' => $res,
				);
		}

//		$licenseArray = $this->getLicense();
//		$licenseArray = array_merge($licenseArray, $this->getLocalTranslationLicense($this->exec));
		$licenseArray = $this->getLocalTranslationLicense($this->exec);
		$result['licenseInformation'] = $licenseArray;

		return $result;
	}

	protected function getLicense() {
		$infoArray = array();
		return $infoArray;
		$serviceId = $this->exec->get('service_id');
		require_once(dirname(__FILE__).'/../manager/ServiceManagerClient.class.php');
		$manager = new ServiceManagerClient();
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
		return 'AtomicTranslatorClient::'.$this->exec->get('service_id');
	}

	public function getSoapBindings() {
		return 'This service is Atomic.';
	}

	private function _callUserHookFunction_replaceTranslatorServiceId($serviceId) {
		// call user hook function
		$pinfo = pathinfo(__FILE__);
		$hookfile = $pinfo['dirname'].'/hooks/TranslatorClient.hook.'.$pinfo['extension'];
		if (file_exists($hookfile)) {
			require_once($hookfile);
			$hookclass = 'TranslatorClient_Hook';
			if (class_exists($hookclass)) {
				$hook = new $hookclass;
				$hookfunc = 'replaceTranslatorServiceId';
				if (method_exists($hook, $hookfunc)) {
					call_user_method($hookfunc, $hook, &$serviceId);
				}
			}
		}
		return $serviceId;
	}
}

class NoBindingTranslatorClient_MultiHop implements ITranslatorClient {

	private $exec = null;
	private $binds = array();

	public function __construct($execs) {
		$this->execs = $execs;
	}
	public function translate($source) {
		$lastResult = null;
		$stack = '';
		foreach ($this->execs as $exec) {
			$runner = new NoBindingTranslatorClient($exec);
			if ($lastResult == null) {
				$lastResult = $runner->translate($source);
			} else {
//				print_r( $lastResult['contents']['targetText']);die();
				$lastResult = $runner->translate($lastResult['contents']['targetText']['contents']);
			}
			$this->binds[] = $runner->getSoapBindings();
			//$stack[$execObj->get('source_lang').'2'.$execObj->get('target_lang')] = $lastResult['contents']['targetText']['contents'];
			$stack .= '\n'.$exec->get('source_lang').'2'.$exec->get('target_lang').$lastResult['contents']['targetText']['contents'];
		}

		$lastResult['message'] .= $stack;

		return $lastResult;
	}
	public function getServiceId() {
		return 'MultiHop@AtomicTranslatorClient';
	}
	public function getSoapBindings() {
		return implode('@@@', $this->binds);
	}

}
?>