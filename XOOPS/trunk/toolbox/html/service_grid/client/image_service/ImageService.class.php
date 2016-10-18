<?php
require_once(dirname(__FILE__).'/ImageService.interface.php');
require_once(dirname(__FILE__).'/../common/LangridSoapClient.class.php');
class ImageServiceImpl implements ImageService {

	protected $_client = null;

	function __construct($serviceId, $options = array(), $force = false) {
		$this->_client = new LangridSoapClient($serviceId, $options, $force);
	}

	public function searchImages($text, $textLanguage, $matchingMethod, $categoryIds, $orders) {
		$soapResponse = $this->_client->invokeService('searchImages', array($text, $textLanguage, $matchingMethod, $categoryIds, $orders));
		return $soapResponse;
	}

	public function listAllTags() {
		$soapResponse = $this->_client->invokeService('listAllTags');
		return $soapResponse;
	}

}
?>
