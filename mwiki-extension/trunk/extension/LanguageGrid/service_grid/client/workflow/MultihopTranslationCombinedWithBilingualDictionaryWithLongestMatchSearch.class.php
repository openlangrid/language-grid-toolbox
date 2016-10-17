<?php
require_once(dirname(__FILE__).'/../LanguageGrid.interface.php');
require_once(dirname(__FILE__).'/../translation_with_temporal_dictionary/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');

/**
 * <#if locale="en">
 * <#elseif locale="ja">
 * マルチホップ辞書連携翻訳サービスクライアントクラス
 * </#if>
 * @author Jun Koyama
 *
 */
class MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch 
	extends TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  {
        
	function __construct() {
		parent::__construct();
	}

    function _translate() {
		$set = $this->context->getBindings();	
		$paths = $set->getTranslationPaths();

		$execs = $paths[0]->getTranslationExecs();

        $intermediate = $this->context->getSource();

        $licenseInformationArray = array();

        foreach ($execs as $exec) {
            $this->_makeBinding($exec);
            
            $sourceLang = $exec->getSourceLang();
            $targetLang = $exec->getTargetLang();
			$result = $this->_client->invokeService('translate',
                                                    array($sourceLang,
                                                          $targetLang,
                                                          $intermediate,
                                                          $this->_getTemporalDict($intermediate, $sourceLang, $targetLang),
                                                          $targetLang
                                                    ));
            
            if ($result['status'] != 'OK') {
                return $result;
            }
            
            $intermediate = $result['contents'];
            
            $licenseInformationArray = array_merge($licenseInformationArray, $result['LicenseInformation']);
        }
        
        $result['LicenseInformation'] = $licenseInformationArray;
        
        return $result;
    }
                
    protected function _makeBinding($exec) {

		$bindNodes = array();
		$bindTemp = '{"children":[%s],"invocationName":"%s","serviceId":"%s"}';
		
		$dictCount = 0;
		$dictionaries = array();
		$serviceId = $exec->getServiceId();
		$binds = $exec->getTranslationBinds();
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

    protected function _getTemporalDict($source, $sourceLang, $targetLang) {
        $setting = new ServiceGridTranslationServiceSetting();

        return $setting->getTemporalDictionaryContents($this->context->getTemporalDictIds(), $sourceLang, $targetLang, $source);
    }
        
}
?>