<?php
require_once(dirname(__FILE__).'/TemplateParallelText.interface.php');
require_once(dirname(__FILE__).'/../common/LangridSoapClient.class.php');

class TemplateParallelTextImpl implements TemplateParallelText {

	protected $_client = null;

	function __construct($serviceId, $options = array(), $force = false) {
		$this->_client = new LangridSoapClient($serviceId, $options, $force);
	}

	public function getCategoryNames($categoryId, $languages) {
		$soapResponse = $this->_client->invokeService('getCategoryNames', array($headLang, $targetLang, $headWord, $matchingMethod));
		return $soapResponse;
	}

	public function listTemplateCategories($language) {
		$soapResponse = $this->_client->invokeService('listTemplateCategories', array($language));
		return $soapResponse;
	}

	public function searchTemplates($language, $text, $matchingMethod, $categoryIds) {
		$soapResponse = $this->_client->invokeService('searchTemplates', array($language, $text, $matchingMethod, $categoryIds));
		return $soapResponse;
	}

	public function getTemplatesByTemplateId($language, $templateIds) {
		$soapResponse = $this->_client->invokeService('getTemplatesByTemplateId', array($language, $templateIds));
		return $soapResponse;
	}

	public function generateSentence($language, $templateId, $boundChoiceParameters, $boundRangeParameters) {
		$soapResponse = $this->_client->invokeService('generateSentence', array($language, $templateId, $boundChoiceParameters, $boundRangeParameters));
		return $soapResponse;
	}

}
?>
