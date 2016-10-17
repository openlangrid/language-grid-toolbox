<?php
require_once(dirname(__FILE__).'/../../common/php_lib/PhpBuf.php');

require_once(dirname(__FILE__).'/../config/ServiceGridConfig.class.php');
require_once(dirname(__FILE__).'/common/LangridSoapClient.class.php');
require_once(dirname(__FILE__).'/common/LangridPbClient.class.php');
require_once(dirname(__FILE__).'/common/util/SoapValueCreation.class.php');

interface LanguageGrid {
	public function invoke();
}

class LanguageGridAccess {
	const SOAP = 0;
	const ProtcolBuffers = 1;
}

?>
