<?php
require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

class BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch extends AbstractServiceGridClient {

	protected $_client = null;

	protected $forwardServiceId = null;
	protected $backwardServiceId = null;
	protected $morphologicalAnalyzer = null;
	protected $bilingualDictionaries = array();
	
	public function setContext($context) {
		parent::setContext($context);
		$this->createClient($this->getGridId()
		. ':BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch');
	}
	protected function setWsdl4Rich() {
		$this->_client = new LangridSoapClient(ServiceGridConfig::getRichBackWsdlUrl());
	}
	function _translate() {
	    debugLog("Translate Start");
		$options = $this->context->getOptions();
		if (isset($options['type']) && 
			($options['type'] == ServiceGridConfig::TRANSLATION_TYPE_RICH || $options['type'] == ServiceGridConfig::TRANSLATION_TYPE_DUAL)) {
			$this->setWsdl4Rich();
		}
		$a = $this->context->getServiceIds();
		$b = $this->context->getReverseServiceIds();

		$this->forwardServiceId = $a[0];
		$this->backwardServiceId = $b[0];

        $set = $this->context->getBindings();
		$paths = $set->getTranslationPaths();
    	$execs = $paths[0]->getTranslationExecs();
    	$binds = $execs[0]->getTranslationBinds();
		foreach ($binds as $bind) {
			if ($bind->getBindType() == '0') {
				$this->forwardServiceId = $bind->getBindValue();
			}
		}

        $rSet = $this->context->getReverseBindings();
		$rPaths = $rSet->getTranslationPaths();
    	$rExecs = $rPaths[0]->getTranslationExecs();
    	$rBinds = $rExecs[0]->getTranslationBinds();
		foreach ($rBinds as $rBind) {
			if ($rBind->getBindType() == '0') {
				$this->backwardServiceId = $rBind->getBindValue();
			}
		}

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

//		$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $this->morphologicalAnalyzer);

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
				, $this->getGridId() . ':BilingualDictionaryWithLongestMatchCrossSearch');
				break;
		}
		if (isset($morphologicalanalyzer)) {
			$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $this->getEndpoint($morphologicalanalyzer));
		}
		$bindNodes[] = sprintf($bindTemp, '', 'ForwardTranslationPL', $this->forwardServiceId);
		$bindNodes[] = sprintf($bindTemp, '', 'BackwardTranslationPL', $this->backwardServiceId);

		$this->_client->setBindingTree('['.implode(',', $bindNodes).']');
	}
}
?>