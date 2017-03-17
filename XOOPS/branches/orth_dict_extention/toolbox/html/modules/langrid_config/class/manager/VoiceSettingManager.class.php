<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2009-2010  NICT Language Grid Project
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
/* $Id: $ */

require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/include/Functions.php');

class VoiceSettingManager {

	public static function save($data, $setId = 7) {
		self::delete($setId);

		$adapter = DaoAdapter::getAdapter();
		$db = $adapter->getDataBase();
		$table = $db->prefix('langrid_config_voice_setting');
		$userId = $adapter->getUserId();

		$sql = "INSERT INTO %s (`language`, `user_id`, `service_id`, `set_id`) VALUES('%s', '%s', '%s', '%d')";
		foreach ($data as $key => $value) {
			if ($value == 'none') continue;
			$db->queryF(sprintf($sql, $table, $key, $userId, $value, $setId));
		}
	}

	public static function delete($setId = 7) {
		$adapter = DaoAdapter::getAdapter();
		$db = $adapter->getDataBase();
		$userId = $adapter->getUserId();

		$sql = 'DELETE FROM %s WHERE user_id=%d && set_id=%d';
		$table = $db->prefix('langrid_config_voice_setting');
		$sql = sprintf($sql, $table, $userId, $setId);

		$result = $db->queryF($sql);
	}

	public static function load($setId = 7) {
		$dao = DaoAdapter::getAdapter()->getLangridServicesDao();
		$services = $dao->queryFindServicesByTypeAndProvisions('TEXTTOSPEECH', 'CLIENT_CONTROL');

		$return = array();
		$languages = array();
		$user = self::getUserSetting(null, $setId);
		foreach ($services as $s) {
			$languages = explode(',', $s->getSupportedLanguagesPaths());
			foreach ($languages as $l) {
				if (empty($return[$l])) {
					$return[$l] = array(
						'language' => array(
							'code' => $l,
							'name' => getLanguageName($l)
						),
						'voices' => array(
							array(
								'selected' => (int)empty($user[$l]),
								'code' => 'none',
								'name' => _MI_LANGRID_CONFIG_VOICE_NONE
							)
						)
					);
				}
				$selected = (int)(isset($user[$l]) && $user[$l] == $s->getServiceId());
				$return[$l]['voices'][] = array(
					'selected' => $selected,
					'code' => $s->getServiceId(),
					'name' => $s->getServiceName()
				);
			}
		}

		$return = array_values($return);
		usort($return, array('self', 'cmp'));

		return $return;
	}

	public static function cmp($a, $b) {
		return strcasecmp($a["language"]["name"], $b["language"]["name"]);
	}

	public static function getEndpointUrlByLanguage($lang, $setId = 7) {
		$data = self::getUserSetting(null, $setId);
		$serviceId = $data[$lang];

		$dao = DaoAdapter::getAdapter()->getLangridServicesDao();
//		$services = $dao->queryGetByServiceId($serviceId, 'TEXTTOSPEECH');
		$services = $dao->queryGetByServiceId($serviceId, 'CLIENT_CONTROL', 'TEXTTOSPEECH');
		return $services[0]->getEndpointUrl();
	}

	public static function getUserSetting($uid = null, $setId = 7) {
		$adapter = DaoAdapter::getAdapter();
		$db = $adapter->getDataBase();
		$table = $db->prefix('langrid_config_voice_setting');
		if ($uid) {
			$userId = $uid;
		} else {
			$userId = $adapter->getUserId();
		}

		$sql = 'SELECT * FROM %s WHERE user_id=%d && set_id=%d';
		$sql = sprintf($sql, $table, $userId, $setId);

		$result = $db->queryF($sql);

		$return = array();
		while ($row = $db->fetchArray($result)) {
			$return[$row['language']] = $row['service_id'];
		}

		return $return;
	}

	/**
	 * <#if locale="ja">
	 * ユーザログイン時に管理者の設定をコピー登録する機能
	 * </#if>
	 */
	public static function copySettingAdminToUser() {
		require_once dirname(__FILE__).'/../toolbox/ToolboxUtil.class.php';
		if (ToolboxUtil::isAdmin()) {
			return;
		}
		$data = self::getUserSetting();
		if (count($data) > 0) {
			return;
		}

		$data = self::getUserSetting(1);
		self::save($data);
	}
}
?>
