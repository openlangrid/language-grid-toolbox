<?php

require_once(dirname(__FILE__).'/../../service/ServiceClient.class.php');
/**
 * <#if locale="en">
 * Client class for translators used when no translation setting exists
 * <#elseif locale="ja">
 * 翻訳パス設定が存在しない場合の翻訳器
 * </#if>
 */
class NullTranslatorClient extends ServiceClient {

	private $src = '';
	private $tgt = '';

	public function __construct($src, $tgt) {
		parent::__construct('NoTranslator');
		$this->src = $src;
		$this->tgt = $tgt;
	}

	public function translate($source) {
		$root =& XCube_Root::getSingleton();
		$modName = $root->mContext->mModule->mXoopsModule->get('name');
		$errorCode = '';
		if ($modName == 'document') {
			$errorCode = 'SAeou8oe9ugnjqka';
		}
		return array(
			'status' => 'Error',
			'message' => 'No translator is assigned for this translation path.',
			'contents' => array(
					'targetLanguage' => $this->tgt,
					'targetText' => array(
							'status' => 'Error',
							'contents' => $errorCode.'No translator is assigned for this translation path.'
					)
			)
		);
	}

	protected function makeBindingHeader($parameters){
		return '';
	}

	public function getServiceId() {
		return 'NullTranslatorClient['.$this->src.'2'.$this->tgt.']';
	}

	public function getSoapBindings() {
		return 'No Translated.';
	}

}
?>