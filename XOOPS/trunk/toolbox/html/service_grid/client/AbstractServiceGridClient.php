<?php

require_once(dirname(__FILE__).'/LanguageGrid.interface.php');

class AbstractServiceGridClient implements LanguageGrid {
	const TAG_RELAXED = '__________';
	const TAG_QUOTE = '"';

	protected $context = null;
	protected $noTranslations = null;

	public function __construct() {

	}

	protected function createClient($wsdl) {
		$options = array();

		if ($this->context) {
			$bindingName = $this->context->getTranslationBindingName();

			debugLog('$bindingName = '.$bindingName);

			if ($bindingName == 'SERVER_CONTROL_SHARED') {
				$header = 'X-Langrid-TypeOfAppProvision: SERVER_CONTROL';

				$options['stream_context'] = stream_context_create(array(
					'http' => array('header' => $header)
				));

				if (version_compare(PHP_VERSION, '5.3.0') == -1){
					ini_set('user_agent', 'PHP-SOAP/' . PHP_VERSION . "\n" . $header);
				}
			}
		}

		$this->_client = new LangridSoapClient($wsdl, $options);
	}

	public function invoke() {
		debugLog("AbstractServiceGridClient: invoke start");
		$source = $this->getSource();
		$encode = $this->preProcess($source);
		$result = $this->translate($encode);
		if ($result == null) {
			return null;
		}
		return $this->postProcess($encode, $result);
	}

	public function preProcess($source) {
		$this->noTranslations= array();

		$this->tags_begin[] = '[relax]';
		$this->tags_begin[] = ServiceGridConfig::SKIP_TAG_BEGIN;

		$this->tags_end[] = '[/relax]';
		$this->tags_end[] = ServiceGridConfig::SKIP_TAG_END;

		/* Encoding */
		return $this->encodeEscapes_relaxed($source);
	}

	public function translate($source) {
		$this->setSource($source);
		return $this->_translate();
	}

	public function postProcess($encode, $result) {
		/* attach Descriptions */
		$this->attachDescriptions();
		if (!isset($result['contents'])) {
			return $result;
		}
		if ($this->isBackTranslation()
			&& !isset($result['contents']->intermediate)
			&& !isset($result['contents']->target)) {
			return $result;
		}
		/* Decode */
		if ($this->isBackTranslation()) {
			$result['contents']->intermediate = $this->decodeEscapes($result['contents']->intermediate);
			$result['contents']->target = $this->decodeEscapes($result['contents']->target, false);

			$result['contents']->intermediate = $this->removeTags($result['contents']->intermediate);
			$result['contents']->target = $this->removeTags($result['contents']->target);
		} else {
			$result['contents'] = $this->decodeEscapes($result['contents']);
			$result['contents'] = $this->removeTags($result['contents']);
		}

		return $result;
	}

	// -
	// Encode Process

	protected function encodeEscapes_relaxed($source) {

		if (is_array($source)) {
			$return = array();
			foreach ($source as $s) {
				$return[] = $this->encodeEscapes_relaxed($s);
			}
			return $return;
		}

		$array = explode(self::TAG_RELAXED, $source);

		$relaxed = '';
		for ($i = 0; $i < count($array) - 1; $i++) {
			$relaxed .= $array[$i];

			if ($i % 2 == 0) {
				$relaxed .= $this->tags_begin[0];
			} else {
				$relaxed .= $this->tags_end[0];
			}
		}

		$relaxed .= $array[count($array)-1];

		return ($this->useQuoteOption()) ? $this->encodeEscapes_quote($relaxed) : $this->encodeEscapes_strict($relaxed);
	}

	protected function encodeEscapes_quote($source) {
		$array = explode(self::TAG_QUOTE, $source);

		$relaxed = '';
		for ($i = 0; $i < count($array) - 1; $i++) {
			$relaxed .= $array[$i];

			if ($i % 2 == 0) {
				$relaxed .= $this->tags_begin[0];
			} else {
				$relaxed .= $this->tags_end[0];
			}
		}
		$relaxed .= $array[count($array)-1];

		return $this->encodeEscapes_strict($relaxed);
	}

	protected function encodeEscapes_strict($source) {
		$encode = '';
		$escape = '';
		$indent = 0;
		$tagStart = 0;
		$tagEnd = 0;
		$buf = '';
		$tagNumber = 0;

		for ($i = 0; $i < mb_strlen($source); $i++) {
			$buf .= mb_substr($source, $i, 1);

			/* escape begins */
			$len = $this->startsWith($this->tags_begin, $buf);
			if ($len != -1) {
				/* before tag, do not escape */
				if ($indent == 0) {
					$encode .= mb_substr($buf, 0, mb_strlen($buf)-$len);
					$tagStart = $i;
				} else {
					$escape .= $buf;
				}
				$buf = '';
				$indent++;
				continue;
			}

			/* escape ends */
			$len = $this->startsWith($this->tags_end, $buf, $tagNumber);
			if ($len != -1) {
				$indent--;
				$escape .= $buf;
				/* after tag, do not escape */
				if ($indent == 0) {
					$escape = mb_substr($escape, 0, mb_strlen($escape)-$len);
					$escape = $this->removeDescription($escape);
					$escape = trim($escape);
					$tagEnd = $i;
					$interCode = $this->getInterCode(count($this->noTranslations), $tagStart, $tagEnd);

					$this->noTranslations[] = array(
						'escape' => $escape,
						'code' => $interCode,
						'tag' => $tagNumber
					);

					$encode .= ' '.$interCode.' ';
					$escape = '';
				}
				$buf = '';
				continue;
			}
		}

		return $encode.$buf;
	}

	protected function startsWith($array, $str, &$num = null) {
		for ($i = 0; $i < count($array); $i++) {
			if (strpos($str, $array[$i]) !== false) {
				$num = $i;
				return mb_strlen($array[$i]);
			}
		}

		return -1;
	}

	// -
	// Decode Process

	/**
	 * Replacing Intermediate Code into Escapes
	 * @param $source  Encoded sentence
	 * @return Decoded sentence
	 */
	protected function decodeEscapes($source, $withDescription = true) {
		$key = ($withDescription) ? 'escapeWithDescription' : 'escape';
		for($i = 0; $i < count($this->noTranslations); $i++) {
			$source = str_ireplace($this->noTranslations[$i]['code'],
							$this->noTranslations[$i][$key], $source);
		}

		return $source;
	}

	/**
	 * Attaching Descriptions
	 */
	protected function attachDescriptions() {
		/* for all noTranslations */
		for ($i = 0; $i < count($this->noTranslations); $i++) {
			$escape = $this->noTranslations[$i]['escape'];

			if ($this->isMultiSentenceTranslation()) {
				$this->setSource(array($escape));
			} else {
				$this->setSource($escape);
			}

			$res = $this->_translate();

			if ($this->isBackTranslation()) {
				$description = $res['contents']->intermediate;
			} else {
				$description = $res['contents'];
			}

			if (is_array($description)) {
				$description = (isset($description[0])) ? $description[0] : '';
			}

			/* attaching */
			if ($this->canAttach($escape, $description)) {
				$escape .= ' ('.$description.') ';
			}

			$tagNumber = $this->noTranslations[$i]['tag'];

	  		if ($tagNumber === 0) {
				$this->noTranslations[$i]['escapeWithDescription'] = self::TAG_RELAXED.$escape.self::TAG_RELAXED;
			}  else if ($tagNumber === 2) {
				$this->noTranslations[$i]['escapeWithDescription'] = self::TAG_QUOTE.$escape.self::TAG_QUOTE;
			} else {
				$this->noTranslations[$i]['escapeWithDescription'] = $this->tags_begin[$tagNumber].$escape.$this->tags_end[$tagNumber];
			}
		}
	}

	/**
	 * Whether to Attach Descriptions
	 */
	protected function canAttach($escape, $description) {
		/* punctuations */
		$pattern = "/(\.|\s|．|,|，|　|:|：|;|。|、|!|！|\?|？)/u";
		$comp1 = @preg_replace($pattern, '', $escape);
		$comp2 = @preg_replace($pattern, '', $description);
		return ($comp1 !== $comp2);
	}

	protected function removeTags($source) {
		if (is_array($source)) {
			$return = array();
			foreach ($source as $s) {
				$return[] = $this->removeTagReplace($s);
			}
			return $return;
		}
		if (is_object($source) && isset($source->result)) {
			$return = array();
			$tmp = $source->result;
			foreach ($tmp as $s) {
				$return[] = $this->removeTagReplace($s);
			}
			$source->result = $return;
			return $source;
		}
		return $this->removeTagReplace($source);
	}
	protected function removeTagReplace($source) {
		$source = preg_replace('/<[^>]+>/u', '', $source);
		$source = str_replace(self::TAG_RELAXED, '', $source);

		return $source;
	}
	protected function removeDescription($source) {
		$ret='';
		$indent = 0;
		for ($i = 0; $i < mb_strlen($source); $i++) {
			$buf = mb_substr($source, $i, 1);
			if ($buf == '(') {
				$indent++;
			}
			if ($indent == 0) {
				$ret.=$buf;
			}
			if ($buf == ')') {
				$indent--;
			}
		}
		return $ret;
	}

	protected function getInterCode($id, $tagStart, $tagEnd) {
		$alt = dechex($tagStart).'x'.dechex($id).'x'.dechex($tagEnd);
		$search = array('0','1','2','3','4','5','6','7','8','9');
		$replace = array('g','h','i','j','k','l','m','n','o','p');
		$result = str_replace($search, $replace, $alt);
		return 'xxx'.$result.'xxx';
	}

	// -
	// Abstract Methods

	// TODO Override
	protected function _translate() {
		;
	}

	// TODO Override
	protected function _makeBinding() {
		;
	}

	// -
	// Getter / Setter

	public function getContext() {
		return $context;
	}

	public function setContext($context) {
		$this->context = $context;
	}

	public function getSource() {
		debugLog('isMultiSentenceTranslation = '.(int)$this->isMultiSentenceTranslation());
		if ($this->isMultiSentenceTranslation()) return $this->context->getSourceArray();
		else return $this->context->getSource();
	}

	public function setSource($source) {
		if ($this->isMultiSentenceTranslation()) {
			$this->context->setSourceArray($source);
		} else {
			$this->context->setSource($source);
		}
	}

    public function getGridId() {
        return ServiceGridConfig::getGridId();
    }

	protected function useQuoteOption() {
		$context = $this->context;
		if (!$context) return false;

		$options = $context->getOptions();
		if (!$options) return false;

		return (isset($options['type']) && ($options['type'] == ServiceGridConfig::TRANSLATION_TYPE_LITE
				|| $options['type'] == ServiceGridConfig::TRANSLATION_TYPE_DUAL));
	}

	protected function isMultiSentenceTranslation() {
		$class = array(
			'MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'MultiSentenceCycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'MultiSentenceMultihopTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'MultiSentenceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'BestChoiceTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'BestChoiceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch'
		);

		foreach ($class as $c) {
			if ($this instanceof $c) return true;
		}

		return false;
	}

	protected function isBackTranslation() {
		$class = array(
			'MultiSentenceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'MultiSentenceCycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'CycleBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'BackTranslation',
			'BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch',
			'BestChoiceBackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch'
		);

		foreach ($class as $c) {
			if (is_a($this, $c)) return true;
		}

		return false;
	}

	protected function getEndpoint($serviceId) {
		// serviceIDがハッシュ値とかならconvertする
		$daoAdapter = DaoAdapter::getAdapter();
		$dao = $daoAdapter->getLangridServicesDao();
		$service = $dao->queryGetByServiceId($serviceId, 'IMPORTED');
		if (!empty($service)) {
			$url = $service[0]->getEndpointUrl();

			$userId = $service[0]->getMiscBasicUserid();
			$passwd = $service[0]->getMiscBasicPasswd();

			if ($userId && $passwd) {
				$parsedUrl = parse_url($url);
				$url = $parsedUrl['scheme'].'://'.$userId.':'.$passwd.'@'.$parsedUrl['host'].$parsedUrl['path'];
				if (isset($parsedUrl['query'])) {
					$url .= '?'.$parsedUrl['query'];
				}
				if (isset($parsedUrl['fragment'])) {
					$url .= '#'.$parsedUrl['fragment'];
				}
			}

			return $url;
		}

		return $serviceId;
	}
}
?>
