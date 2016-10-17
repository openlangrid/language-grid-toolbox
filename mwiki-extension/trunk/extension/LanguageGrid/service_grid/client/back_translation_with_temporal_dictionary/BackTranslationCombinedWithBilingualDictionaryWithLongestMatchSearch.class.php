<?php
require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');
class BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch extends AbstractServiceGridClient {

	protected $_client = null;

	protected $forwardServiceId = null;
	protected $backwardServiceId = null;
	protected $morphologicalAnalyzer = "DefaultMorphologicalAnalysis";
	protected $bilingualDictionaries = array();
	
	function __construct() {
//		$wsdl = 'BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
//		$this->_client = new LangridSoapClient($wsdl);
	}
	protected function setWsdl4Rich() {
//		$this->_client = new LangridSoapClient(ServiceGridConfig::getRichBackWsdlUrl());
	}
	function _translate() {
		$options = $this->context->getOptions();
		if (isset($options['type']) && 
			($options['type'] == ServiceGridConfig::TRANSLATION_TYPE_RICH || $options['type'] == ServiceGridConfig::TRANSLATION_TYPE_DUAL)) {
			$this->setWsdl4Rich();
		}
		$a = $this->context->getServiceIds();
		$b = $this->context->getReverseServiceIds();
		error_log('### Service ID 1 ###'.print_r($a, true));
		error_log('### Service ID 2 ###'.print_r($b, true));
		$this->forwardServiceId = $a[0];
		$this->backwardServiceId = $b[0];
		
    	$this->_makeBinding();
    	
		$soapResponse = $this->_client->invokeService('backTranslate', array(
			$this->context->getSourceLang(),
			$this->context->getIntermediateLang(),
			$this->context->getSource(),
			$this->context->getTemporalDict(),
			$this->context->getIntermediateLang()
		));
		
		return $soapResponse;
	}

	function setTranslator($forwardServiceId, $backwardServiceId) {
		$this->forwardServiceId = $forwardServiceId;
		$this->backwardServiceId = $backwardServiceId;
	}
	
	function setMorphologicalAnalyzer($morphologicalAnalyzer) {
		$this->morphologicalAnalyzer = $morphologicalAnalyzer;
	}
	
	function setBilingualDictionaries($bilingualDictionaries) {
		$this->bilingualDictionaries = $bilingualDictionaries;
	}

	protected function _makeBinding() {
		$bindNodes = array();
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';

		$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $this->morphologicalAnalyzer);

		$set = $this->context->getBindings();

		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();

		$dictCount = 0;
		$dictionaries = array();
		//$serviceId = $execs[0]->getServiceId();
		$binds = $execs[0]->getTranslationBinds();
		$morphologicalanalyzer = null;
		//
		foreach ($binds as $bind) {
			switch ($bind->getBindType()) {
				case 1: //Global
					$dictionaries[] = $bind->getBindValue();
					$dictCount++;
					break;
				case 2: //Local
					$dictionaries[] = $bind->getBindValue();
					$dictCount++;
					break;
				case 3: //Temporal
					;
					break;
				case 9: //Morphological Analyzer
					$morphologicalanalyzer = $bind->getBindValue();
					break;
			}
		}
		switch ($dictCount) {
			case 0:		// Bind for No Dictionary
				$bindNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, 'AbstractBilingualDictionaryWithLongestMatchSearch');
				break;
			case 1:		// Bind for One Dictionary
				$bindNodes[] = sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchSearchPL'
				, $dictionaries[0]);
				break;
			default:	// Bind for Any Dictionaries
				$dictionaryBinding = array();
				foreach ($dictionaries as $dictionary) {
					if (!empty($dictionary)) {
						$idx = count($dictionaryBinding) + 1;
						$dictionaryBinding[] =
							sprintf($bindTemp, '', 'BilingualDictionaryWithLongestMatchCrossSearchPL'.$idx, $dictionary);
					}
				}
				$bindNodes[] = sprintf($bindTemp, implode(',', $dictionaryBinding), 'BilingualDictionaryWithLongestMatchSearchPL'
				, 'BilingualDictionaryWithLongestMatchCrossSearch');
				break;
		}
		if (isset($morphologicalanalyzer)) {
			$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $morphologicalanalyzer);
		}
		$bindNodes[] = sprintf($bindTemp, '', 'ForwardTranslationPL', $this->forwardServiceId);
		$bindNodes[] = sprintf($bindTemp, '', 'BackwardTranslationPL', $this->backwardServiceId);

		$this->_client->setBindingTree('['.implode(',', $bindNodes).']');
	}
}
?>