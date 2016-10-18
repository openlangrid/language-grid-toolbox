<?php

require_once(dirname(__FILE__).'/../AbstractServiceGridClient.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * 辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author jun
 *
 */
class TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch extends AbstractServiceGridClient {

	protected $_client = null;
	protected $translator = null;
	protected $morphologicalAnalyzer = "DefaultMorphologicalAnalysis";
	protected $bilingualDictionaries = array();
	protected $context;

	public static function getInstance($access) {
		switch ($access) {
			case LanguageGridAccess::SOAP:
			default:
				require_once(dirname(__FILE__).'/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearchBySoap.class.php');
				return new TranslationCombinedWithBilingualDictionaryWithLongestMatchSearchBySoap();
			case LanguageGridAccess::ProtcolBuffers:
				require_once(dirname(__FILE__).'/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearchByProtocolBuffers.class.php');
				return new TranslationCombinedWithBilingualDictionaryWithLongestMatchSearchByProtocolBuffers();
		}
	}
	
	function __construct() {
		$wsdl = 'TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch';
		$this->_client = new LangridSoapClient($wsdl);
		$this->translator = 'NICTJServer';
	}
	protected function setWsdl4Rich() {
		$this->_client = new LangridSoapClient(ServiceGridConfig::getRichWsdlUrl());
	}
	function _translate() {
		$options = $this->context->getOptions();		
		if (isset($options['type']) && 
			($options['type'] == ServiceGridConfig::TRANSLATION_TYPE_RICH || $options['type'] == ServiceGridConfig::TRANSLATION_TYPE_DUAL)) {
			$this->setWsdl4Rich();
		}
		$this->_makeBinding();
		$soapResponse = $this->_client->invokeService('translate', 
			array(
				$this->context->getSourceLang(),
				$this->context->getTargetLang(), 
				$this->context->getSource(), 
				$this->context->getTemporalDict(), 
				$this->context->getDictTargetLang()));

		return $soapResponse;
	}

	function setTranslator($translator) {
		$this->translator = $translator;
	}
	
	function setMorphologicalAnalyzer($morphologicalAnalyzer) {
		$this->morphologicalAnalyzer = $morphologicalAnalyzer;
	}
	
	function setBilingualDictionaries($bilingualDictionaries) {
		$this->bilingualDictionaries = $bilingualDictionaries;
	}

	public function setContext($context) {
		$this->context = $context;
	}

	protected function _makeBinding() {
		// Binding情報生成
//		print_r($this->context->getBindings());

		$bindNodes = array();
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';
		
		$set = $this->context->getBindings();
		
		$paths = $set->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		
		$dictCount = 0;
		$dictionaries = array();
		$serviceId = $execs[0]->getServiceId();
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
		
		$bindNodes[] = sprintf($bindTemp, '', 'TranslationPL', $serviceId);
		$bindNodes[] = sprintf($bindTemp, '', 'MorphologicalAnalysisPL', $morphologicalanalyzer);
		
		$this->_client->setBindingTree('['.implode(',', $bindNodes).']');
	}
}
?>
