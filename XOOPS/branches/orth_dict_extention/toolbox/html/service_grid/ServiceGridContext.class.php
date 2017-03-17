<?php
require_once(dirname(__FILE__).'/ServiceGridTranslationServiceSetting.class.php');
require_once(dirname(__FILE__).'/db/handler/ServiceGridLogDbHandler.class.php');
require_once(dirname(__FILE__).'/config/ServiceGridConfig.class.php');
//TODO xoops only
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');
class ServiceGridContext {
	private $sourceLang; // Source Language Code
	private $targetLang; // Target Language Code
	private $translationBindingName; // Binding Name
	private $translationBindingSetId; // Set ID
	private $intermediateLang; // Intermediate Lanuguage Code
	private $source; // Source
	private $sourceArray; // Source[]
	private $dictTargetLang; // Dictionary Target Language Code
	private $temporalDictIds; // Temporal Dictionary
	private $temporalDict; // Temporal Dictionary Contents
	private $sourceTextJoinStrategy;
	private $bindings; // Binding Value
	private $options; // Translation Options
	private $reverseBindings; // Reverse binding value
	private $isBackTrans = false; // is Back Translation?
	private $isMultihop = false; // is Multihop Translation?
	private $isCombined = false; // is Dictionary Combined?
	private $isBestChoice = false; // is Best Choice?
	private $isEbmt = false; // is EBMT?
 	function __construct() {
 	}
 	public function getTranslator() {
 		$this->_loadBindings();
		if ($this->isBestChoice || $this->isEbmt) {
			if ($this->isBackTrans) {
				$translator = $this->getBestChoiceBackTranslation();
			} else {
				$translator = $this->getBestChoiceTranslation();
			}
		} else if ($this->isBackTrans && $this->isMultihop) {
  			if ($this->sourceArray == null) {
  				$translator = $this->getCycleBackTranslation();
  			} else {
  				$translator = $this->getMultiSentenceCycleBackTranslation();
  			}
  		} else if ($this->isBackTrans && $this->isCombined) {
  			if ($this->sourceArray == null) {
	  			$translator = $this->getBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
  			} else {
	  			$translator = $this->getMultiSentenceBackTranslation();
   			}
  		} else if ($this->isBackTrans) {
  			if ($this->sourceArray == null) {
  				$translator = $this->getBackTranslation();
  			} else {
  				$translator = $this->getMultiSentenceBackTranslation();
  			}
  		} else if ($this->isMultihop) {
  			if ($this->sourceArray == null) {
  				$translator = $this->getMultihopTranslation();
  			} else {
	  			$translator = $this->getMultiSentenceMultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
  			}
  		} else if ($this->isCombined) {
 			if ($this->sourceArray == null) {
 				$translator = $this->getTranslationCombinedWithBilingualDictionaryWithLogestMatchSearch();
 			} else {
 				$translator = $this->getMultiSentenceTranslation();
 			}
 		} else {
  			if ($this->sourceArray == null) {
				$translator = $this->getAtomicTranslation();
  			} else {
  				$translator = $this->getMultiSentenceTranslation();
   			}
  		}
		debugLog("### Selected translator ###");
		debugLog(get_class($translator));
		debugLog("source = ".$this->source);
		debugLog("sourceArray = ".$this->sourceArray);
		debugLog("BindingName = ".$this->translationBindingName);
		debugLog("isBackTrans = ". (int)$this->isBackTrans);
		debugLog("isMultihop = ". (int)$this->isMultihop);
		debugLog("isCombined = ". (int)$this->isCombined);
		debugLog("isBestChoice = ". (int)$this->isBestChoice);
		debugLog("isEbmt = ". (int)$this->isEbmt);
  		$this->_loadTemporalDictionary();
   		$translator->setContext($this);
 		return $translator;
  	}
  	protected function _loadBindings() {
  		$setting = new ServiceGridTranslationServiceSetting();
  		if ($this->targetLang) {
  			$this->bindings = $setting->getTranslationSet($this->translationBindingSetId, $this->translationBindingName, $this->sourceLang, $this->targetLang);
  			$this->reverseBindings = null;
  		} else if ($this->intermediateLang) {
  			$this->isBackTrans = true;
  			$this->bindings = $setting->getTranslationSet($this->translationBindingSetId, $this->translationBindingName, $this->sourceLang, $this->intermediateLang);
  			$this->reverseBindings = $setting->getTranslationSet($this->translationBindingSetId, $this->translationBindingName, $this->intermediateLang, $this->sourceLang);
  		} else {
  			throw new Exception("Argument Null Exception.");
  		}
  		if ($this->temporalDictIds != null) {
			$this->isCombined = true;
		}
   		$setting = new ServiceGridTranslationServiceSetting();
   		$paths = $this->bindings->getTranslationPaths();
		$execs = $paths[0]->getTranslationExecs();
		if (count($execs) > 1) {
			$this->isMultihop = true;
		}
		
		foreach ($execs as $exec) {
			$numberOfTranslators = 0;
			$numberOfParallelTexts = 0;
			$numberOfEbmts = 0;

			$binds = $exec->getTranslationBinds();
			debugLog("### binds ###");
			debugLog(print_r($binds, 1));
			foreach ($binds as $bind) {
				switch ($bind->getBindType()) {
					case 0: //Translation
						if ($bind->getBindValue() == 'kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT') {
							$this->isEbmt = true;
							$numberOfEbmts++;
						}
						$numberOfTranslators++;
						break;
					case 1: //Global
						$dictionaries[] = $bind->getBindValue();
						$this->isCombined = true;
						break;
					case 2: //Local
						$dictionaries[] = $bind->getBindValue();
						$this->isCombined = true;
						break;
					case 3: //Temporal
						$this->temporalDictIds[] = $setting->getTemporalDictionaryIdByName($bind->getBindValue());
						$this->isCombined = true;
						break;
					case 4: // Parallel
						$numberOfParallelTexts++;
						break;
					case 5:
						$numberOfParallelTexts++;
						break;
				}
			}

			debugLog('$numberOfTranslators = '.$numberOfTranslators);
			debugLog('$numberOfEbmts = '.$numberOfEbmts);
			debugLog('$numberOfParallelTexts = '.$numberOfParallelTexts);
			
			if ($numberOfTranslators >= 2) {
				$this->isBestChoice = true;
			}
			debugLog('$this->isBestChoice = '.(int)$this->isBestChoice);
		}
   	}
	
   	protected function _loadTemporalDictionary() {
   		$target = $this->targetLang;
   		if ($this->intermediateLang != null) {
   			$target = $this->intermediateLang;
   		}
   		$setting = new ServiceGridTranslationServiceSetting();
		$source = "";
   		if ($this->sourceArray != null && count($this->sourceArray) > 0) {
   			foreach($this->sourceArray as $tmp) {
   				$source = $source.$tmp;
   			}
   		} else {
   			$source = $this->source;
   		}
  		if ($this->temporalDictIds != null && count($this->temporalDictIds) > 0) {
  			$this->temporalDict = $setting->getTemporalDictionaryContents($this->temporalDictIds, $this->sourceLang, $target, $source);
  		}
   	}
 	public function getTranslationCombinedWithBilingualDictionaryWithLogestMatchSearch() {
		require_once(dirname(__FILE__).'/client/translation_with_temporal_dictionary/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch::getInstance(LanguageGridAccess::SOAP);
//		$translator = TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch::getInstance(LanguageGridAccess::ProtcolBuffers);
		return $translator;
 	}
 	public function getAtomicTranslation() {
		require_once(dirname(__FILE__).'/client/translation/AtomicTranslation.class.php');
		$serviceIds = $this->getServiceIds();
		$translator = new AtomicTranslation($serviceIds[0]);
		return $translator;
  	}
  	public function getBackTranslation() {
		require_once(dirname(__FILE__).'/client/backtranslation/BackTranslation.class.php');
		$serviceIds = $this->getServiceIds();
		$translator = new BackTranslation();
		return $translator;
  	}
  	public function getBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch() {
  		require_once(
  			dirname(__FILE__).'/client/back_translation_with_temporal_dictionary/BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php'
  			);
		$serviceIds = $this->getServiceIds();
		$translator = new BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
  	}
  	public function getMultiSentenceTranslation() {
  		require_once(dirname(__FILE__).'/client/workflow/MultiSentenceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = new MultiSentenceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
  	}
  	public function getMultiSentenceBackTranslation() {
  		require_once(dirname(__FILE__).'/client/workflow/MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = new MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
  	}
  	public function getCycleBackTranslation() {
  		require_once(dirname(__FILE__).'/client/workflow/CycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = new CycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
   	}
   	public function getMultiSentenceCycleBackTranslation() {
   		require_once(dirname(__FILE__).'/client/workflow/MultiSentenceCycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
   		$translator = new MultiSentenceCycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
   		return $translator;
   	}
   	public function getMultiSentenceMultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch() {
   		require_once(dirname(__FILE__).'/client/workflow/MultiSentenceMultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
   		$translator = new MultiSentenceMultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
   		return $translator;
   	}
   	public function getMultihopTranslation() {
  		require_once(dirname(__FILE__).'/client/workflow/MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = new MultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
   	}
	public function getBestChoiceTranslation() {
  		require_once(dirname(__FILE__).'/client/best_choice/BestChoiceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = new BestChoiceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
	}
	public function getBestChoiceBackTranslation() {
  		require_once(dirname(__FILE__).'/client/best_choice/BestChoiceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.class.php');
		$translator = new BestChoiceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch();
		return $translator;
	}
  	public function getServiceIds() {
		$serviceIds = array();
  		$paths = $this->bindings->getTranslationPaths();
		foreach ($paths as $path) {
			$execs = $path->getTranslationExecs();
			foreach ($execs as $exec) {
				$serviceIds[] = $this->convertServiceId($exec->getServiceId());
			}
		}
		return $serviceIds;
  	}
  	public function getReverseServiceIds() {
		$serviceIds = array();
  		$paths = $this->reverseBindings->getTranslationPaths();
		foreach ($paths as $path) {
			$execs = $path->getTranslationExecs();
			foreach ($execs as $exec) {
				$serviceIds[] = $this->convertServiceId($exec->getServiceId());
			}
		}
		return $serviceIds;
  	}
  	protected function convertServiceId($serviceId) {
  		$adapter = DaoAdapter::getAdapter();
		$dao = $adapter->getLangridServicesDao();

		$result = $dao->queryGetByServiceId($serviceId, 'IMPORTED', 'TRANSLATION');
		if (empty($result)) {
			return $serviceId;
		}
		return $result[0]->getEndpointUrl();
  	}
  	public function getReverseBindings() {
  		return $this->reverseBindings;
  	}
	// Setter, Getter start
	public function setSourceLang($sourceLang) {
		$this->sourceLang = $sourceLang;
	}
	public function setTargetLang($targetLang) {
		$this->targetLang = $targetLang;
	}
	public function setTranslationBindingName($translationBindingName) {
		$this->translationBindingName = $translationBindingName;
	}
	public function setTranslationBindingId($translationBindingSetId) {
		$this->translationBindingSetId = $translationBindingSetId;
	}
	public function setIntermediateLang($intermediateLang) {
		$this->intermediateLang = $intermediateLang;
	}
	public function setSource($source) {
		$this->source = $source;
	}
	public function setSourceArray($sourceArray) {
		$this->sourceArray = $sourceArray;
	}
	public function setBindings($bidings) {
		$this->bindings = $bidings;
	}
	public function setDictTargetLang($dictTargetLang) {
		$this->dictTargetLang = $dictTargetLang;
	}
	public function setTemporalDictIds($temporalDictIds) {
		$this->temporalDictIds = $temporalDictIds;
	}
	public function setTemporalDict($temporalDict) {
		$this->temporalDict = $temporalDict;
	}
	public function setSourceTextJoinStrategyType($sourceTextJoinStrategy) {
		$this->sourceTextJoinStrategy = $sourceTextJoinStrategy;
	}
	public function getSourceLang() {
		return $this->sourceLang;
	}
	public function getTargetLang() {
		return $this->targetLang;
	}
	public function getTranslationBindingName() {
		return $this->translationBindingName;
	}
	public function getTranslationBindingId() {
		return $this->translationBindingId;
	}
	public function getIntermediateLang() {
		return $this->intermediateLang;
	}
	public function getSource() {
		return $this->source;
	}
	public function getSourceArray() {
		return $this->sourceArray;
	}
	public function getBindings() {
		return $this->bindings;
	}
	public function getDictTargetLang() {
		return $this->dictTargetLang;
	}
	public function getTemporalDictIds() {
		return $this->temporalDictIds;
	}
	public function getTemporalDict() {
		return $this->temporalDict;
	}
	public function getSourceTextJoinStrategyType() {
		return $this->sourceTextJoinStrategy;
	}
	public function setOptions($options) {
		$this->options = $options;
	}
	public function getOptions() {
		return $this->options;
	}
	public function isEbmt() {
		return $this->isEbmt;
	}
	public function isBestChoice() {
		return $this->isBestChoice;
	}
	// Setter, Getter end
	public function debugLog($sourceLang = null, $targetLang = null, $source = null, $result = null) {
		if (ServiceGridConfig::isDebugLog() == false) {
			return;
		}
		$logger = new ServiceGridLogDbHandler();
		$serviceName = '';
		$serviceIds = $this->getServiceIds();
		foreach ($serviceIds as $serviceId) {
			$serviceName = $serviceName.' '.$serviceId.',';
		}
		$dictionaryContents = '';
		if ($this->temporalDict != null) {
			$dictionaryContents = serialize($this->temporalDict);
		}
		$serviceName = $serviceName.' '.$dictionaryContents;
		$logger->create($sourceLang, $targetLang, $source,
			strval($result), $serviceName,
			$this->translationBindingName);
	}
 }
?>
