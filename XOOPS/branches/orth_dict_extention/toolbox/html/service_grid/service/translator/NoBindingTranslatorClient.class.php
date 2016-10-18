<?php
require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
require_once(dirname(__FILE__).'/ITranslatorClient.interface.php');
/**
 * <#if locale="en">
 * Superclass ofclient class for atomic services
 * <#elseif locale="ja">
 * 原子サービス翻訳器
 * </#if>
 */
class NoBindingTranslatorClient extends ServiceClient implements ITranslatorClient {

	private $exec = null;

	public function __construct($exec) {
		$this->exec = $exec;
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

		$licenseArray = $this->getLicense();
		$licenseArray = array_merge($licenseArray, $this->getLocalTranslationLicense($this->exec));
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
				$lastResult = $runner->translate($lastResult['contents']['targetText']['contents']);
			}
			$this->binds[] = $runner->getSoapBindings();
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