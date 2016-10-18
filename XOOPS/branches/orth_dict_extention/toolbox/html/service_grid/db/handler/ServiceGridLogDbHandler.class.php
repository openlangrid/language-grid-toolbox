<?php
require_once(dirname(__FILE__).'/../dao/ServiceGridLogDAO.interface.php');
require_once(dirname(__FILE__).'/../adapter/DaoAdapter.class.php');
/**
 * <#if locale="en">
 * Log DB handler class
 * <#elseif locale="ja">
 * LogDBハンドラクラス
 * </#if>
 */
class ServiceGridLogDbHandler {

	protected $db = null;
	protected $m_LogDao = null;
    function __construct() {
    	// get adapter
		$adapter = DaoAdapter::getAdapter();
    	$this->m_LogDao = $adapter->getServiceGridLogDao();
    	$this->db = $adapter->getDataBase();
    }
    public function getServiceGridLogDao() {
    	return $this->m_LogDao;
    }
    /**
     * <#if locale="en">
     * <#elseif locale="ja">
     * Log追加
     * </#if>
     */
    public function create($sourceLang, $targetLang, $source, $result, $serviceName, $url) {
		global $wgUser, $wgTitle;
    	$logObj = $this->m_LogDao->create(true);
    	$logObj->set('source_lang', $sourceLang);
    	$logObj->set('target_lang', $targetLang);
    	$logObj->set('source', $source);
    	$logObj->set('result', $result);
    	$logObj->set('service_name', $serviceName);
    	$logObj->set('url', $url);
    	$logObj->set('executed_user', $wgUser->getName());
		$now = wfTimestamp( TS_UNIX, wfTimestampNow()) + 9 * 60 * 60;
		$date = $this->db->timestamp($now);
    	$logObj->set('executed_time', $date);
		$this->m_LogDao->insert($logObj);
    	
    	return true;
    }
}
?>