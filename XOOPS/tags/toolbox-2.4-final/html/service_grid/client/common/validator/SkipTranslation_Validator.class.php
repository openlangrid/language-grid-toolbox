<?php
/* $Id: SkipTranslation_Validator.class.php 4461 2010-10-04 06:25:13Z koyama $ */

//require_once(dirname(__FILE__).'/../../AbstractServiceGridClient.php');
require_once(dirname(__FILE__).'/../../AbstractServiceGridClient.php');
class SkipTranslation_Validator {

    public function __construct() {
    }

	public static function validSkipTag($source) {
		if (!$source) {
			return true;
		}

		$stx = '@'.preg_quote(ServiceGridConfig::SKIP_TAG_BEGIN, '@').'@iu';
		$etx = '@'.preg_quote(ServiceGridConfig::SKIP_TAG_END, '@').'@iu';

		$stxMatch = array();
		$etxMatch = array();

		$stxCnt = preg_match_all($stx, $source, $stxMatch, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
		$etxCnt = preg_match_all($etx, $source, $etxMatch, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

		if ($stxCnt === false && $etxCnt === false) {
			return true;
		}

		if ($stxCnt !== $etxCnt) {
			return false;
		}
//print_r($stxMatch);
//print_r($etxMatch);
		for ($i = 0; $i < $stxCnt; $i++) {
			if ($stxMatch[$i][0][1] >= $etxMatch[$i][0][1]) {
				return false;
			}
		}
		return true;
	}
}
//$strsok = array(
//	'あいうえお',
//	'<skip_translation>あいうえお</skip_translation>',
//	'あ<skip_translation>いうえ</skip_translation>お',
//	'あ<skip_translation>いう</skip_translation>え<skip_translation>お</skip_translation>'
//);
//foreach ($strsok as $str) {
//	if (SkipTranslation_Validator::validSkipTag($str) === true) {
//		echo '---OK.---'.$str.'<br>'.PHP_EOL;
//	} else {
//		echo '---ERROR.---'.$str.'<br>'.PHP_EOL;
//	}
//}
//
//$strsng = array(
//	'<skip_translation>あいうえお',
//	'あい<skip_translation>うえお',
//	'あいうえお</skip_translation>',
//	'あいう</skip_translation>えお',
//	'<skip_translation>あい</skip_translation>う<skip_translation>えお',
//	'あ</skip_translation>い<skip_translation>うえお'
//);
//foreach ($strsng as $str) {
//	if (SkipTranslation_Validator::validSkipTag($str) === false) {
//		echo '---OK.---'.$str.'<br>'.PHP_EOL;
//	} else {
//		echo '---ERROR.---'.$str.'<br>'.PHP_EOL;
//	}
//}
//
?>