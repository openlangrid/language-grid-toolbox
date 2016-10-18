<?php
interface ServiceGridLangridServicesDAO{

	public function queryAll();
	/*
	 * <#if locale="ja">
	 * @param $serviceId
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 * </#if>
	 */
	function queryGetByServiceId($serviceId, $allowedAppProvision, $serviceType = '');

	/*
	 * <#if locale="ja">
	 * エンドポイントを指定して検索
	 * @param $endpoint
	 * @param $serviceType [optional] - サービスタイプを指定する場合
	 * @return ServiceGridLangridService objects in array.
	 * </#if>
	 */
	function queryGetByEndPoint($endpoint, $allowedAppProvision, $serviceType = '');

//
//	/*
//	 * <#if locale="ja">
//	 * サービスタイプを指定して検索
//	 * @param $serviceType
//	 * @return ServiceGridLangridService objects in array.
//	 * </#if>
//	 */
//	function queryFindByServiceTypes($serviceTypes);

	/*
	 * <#if locale="ja">
	 * サービスタイプと管理形態を指定して検索
	 * @param String $serviceType サービスタイプ
	 * @param String|Array $allowedAppProvisions 管理形態（CLIENT_CONTROL | SERVER_CONTROL | IMPORTED）
	 * @return ServiceGridLangridService objects in array.
	 * </#if>
	 */
	function queryFindServicesByTypeAndProvisions($serviceType, $allowedAppProvisions);
}
?>