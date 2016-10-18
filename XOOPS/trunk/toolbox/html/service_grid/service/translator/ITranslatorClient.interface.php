<?php
/**
 * <#if locale="en">
 * Superclass of translation client class
 * <#elseif locale="ja">
 * 翻訳器基底
 * </#if>
 */
interface ITranslatorClient {

	public function translate($source);
	public function getServiceId();
	public function getSoapBindings();
}
?>