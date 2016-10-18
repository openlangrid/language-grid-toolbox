<?php
class TranslatorClient_Hook {
	/**
	 * 実行する翻訳器サービスIDを書き換えるHook関数
	 * @param $serviceId [参照渡し]
	 * @return 無し
	 *
	 * 言語グリッドに登録済みの翻訳器サービスの場合は、サービスIDが、
	 * ローカル翻訳器サービスの場合は、エンドポイントURLが引数として
	 * 渡される。
	 */
	function replaceTranslatorServiceId(&$serviceId) {
	}
}
?>
