<?php
error_reporting(E_ALL);
require(dirname(__FILE__).'/../../mainfile.php');

require_once(XOOPS_ROOT_PATH.'/service_grid/ServiceGridClient.class.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/client/example_based_translator/ExampleBasedTranslator.class.php');

class TestEBMT extends ServiceGridClient {

	public function test() {
		$client = new ServiceGridClientExp();
		return $client->ebmtTranslate('en', 'ja', 'Hello world.');
	}

	public function test1() {
		$c = new ExampleBasedTranslatorImpl('kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
		return $c->getStatus('1df746e368e793e9302e41e3c646d2d1');
	}

	public function test2() {
		$c = new ExampleBasedTranslatorImpl('kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
		return $c->createToken('en', 'ja');
	}

	public function test3() {
		$c = new ExampleBasedTranslatorImpl('kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
		return $c->addParallelText('1df746e368e793e9302e41e3c646d2d1', 'en', 'ja', array(array('Hello world.', '世界の皆さん、こんにちわ。')));
	}


}

class ServiceGridClientExp extends ServiceGridClient {

	public function ebmtTranslate($sourceLang, $targetLang, $source, $translationBindingSetId = null, $translationBindingSetName = null, $temporalDictIds = array()) {
		$context = $this->createServiceGridContext($sourceLang, $targetLang, null, $source, null, $translationBindingSetId, $translationBindingSetName, $temporalDictIds);
		$translator = new ExampleBasedTranslatorImpl('kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
		$translator->setContext($context);
		$result = $translator->invoke();
		return $result;
	}
}

$ebmt = new TestEBMT();
$out = $ebmt->test1();

echo'<pre>';
print_r($out);
echo'</pre>';

?>