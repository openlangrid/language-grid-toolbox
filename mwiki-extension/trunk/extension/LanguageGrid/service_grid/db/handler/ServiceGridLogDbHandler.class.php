<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//  ------------------------------------------------------------------------ //
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