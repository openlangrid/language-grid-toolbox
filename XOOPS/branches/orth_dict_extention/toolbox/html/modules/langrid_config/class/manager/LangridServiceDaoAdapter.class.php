<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2010  NICT Language Grid Project
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
/* $Id: LangridServiceDaoAdapter.class.php 4968 2010-12-27 08:09:06Z kitajima $ */

/*
 * LangridServiceDAOのアダプタ（ラッパ）
 * DAOに直接実装するには個別的過ぎる機能を実装する。
 */

require_once(XOOPS_ROOT_PATH.'/service_grid/db/adapter/DaoAdapter.class.php');

//require_once(XOOPS_ROOT_PATH.'/modules/langrid/include/Functions.php');	// TODO:言語コードから言語名を取得するのに必要。
require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/include/Functions.php');	// TODO:言語コードから言語名を取得するのに必要。

require_once(dirname(__FILE__).'/../toolbox/ResourceAdapter.class.php');

class LangridServiceDaoAdapter {

	/* service_grid/db/dao/ServiceGridLanguageGridDaoインターフェイスの実装クラス */
	private $mLangridServiceDaoImpl = null;
	private $mAllowedAppProvisions = array(
		'CLIENT_CONTROL',
		'IMPORTED'
	);

	/*
	 * コンストラクタ
	 */
    public function __construct() {
    	$this->mLangridServiceDaoImpl = DaoAdapter::getAdapter()->getLangridServicesDao();
    }

	/*
	 * 設定画面で利用する各種サービスの情報を返す
	 */
	public function loadLangridService() {
		$contents = array();

		$as = $this->mLangridServiceDaoImpl->queryFindServicesByTypeAndProvisions('MORPHOLOGICALANALYSIS', $this->getAllowedAppProvisions());
		$ts = $this->mLangridServiceDaoImpl->queryFindServicesByTypeAndProvisions('TRANSLATION', $this->getAllowedAppProvisions());
		$ds = $this->mLangridServiceDaoImpl->queryFindServicesByTypeAndProvisions('BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH', $this->getAllowedAppProvisions());
		$ss = $this->mLangridServiceDaoImpl->queryFindServicesByTypeAndProvisions('SIMILARITYCALCULATION', $this->getAllowedAppProvisions());

		$contents['analyzeServices'] = $this->__formatLangridServiceObjects($as);
		$contents['translationServices'] = $this->__formatLangridServiceObjects($ts);

		// ユーザ辞書を辞書サービスリストに追加
		$a = new ResourceAdapter();
		$contents['dictionaryServices'] = array_merge($this->__formatLangridServiceObjects($ds), $a->getUserDictionary());
		
		$contents['langridServices'] = array(
			'similarityCalculations' => $this->__formatLangridServiceObjects($ss),
			'parallelTexts' => $a->getParallelTexts(),
			'translationTemplates' => $a->getTranslationTemplates()
		);
		
		$contents['supportLangs'] = $this->getTranslatorAllSupportLanguagePairs($ts);

		return $contents;
	}

	/*
	 * (non-php)
	 * @see langrid_config/class/action/LoadServiceInfoAction.class.php[LoadServiceInfoAction::load()]
	 */
	public function searchLangridService($serviceId) {
		$a = $this->mLangridServiceDaoImpl->queryGetByServiceId($serviceId, null);
		$b = $this->__formatLangridServiceObjects($a);
		return $b[0];
	}

	public function getAllowedAppProvisions() {
		return $this->mAllowedAppProvisions;
	}

	public function setAllowedAppProvisions($allowedAppProvisions) {
		$this->mAllowedAppProvisions = $allowedAppProvisions;
	}

	/*
	 * 言語グリッド上で利用可能な翻訳器のすべての言語対
	 */
	private function getTranslatorAllSupportLanguagePairs($ts) {
		$allpaths = array();
		foreach ($ts as $a) {
			$allpaths = array_merge($allpaths, explode(',', $a->getSupportedLanguagesPaths()));
		}
		sort($allpaths);

		$pairs = array();
		$srcLangs = array();
		$tgtLangs = array();
		foreach($allpaths as $path) {
			$pair = explode('2', $path);
			$pairs[] = $pair;
			$srcLangs[] = $pair[0];
			$tgtLangs[] = $pair[1];
		}

		$srcLangs = array_merge(array(), array_unique($srcLangs));
		$tgtLangs = array_merge(array(), array_unique($tgtLangs));

		$source = array();
		foreach ($srcLangs as $lang) {
			$source = array_merge($source, array($lang=>getLanguageName($lang)));
		}
		$target = array();
		foreach ($tgtLangs as $lang) {
			$target = array_merge($target, array($lang=>getLanguageName($lang)));
		}

		asort($source);
		asort($target);
		return array('sourceLanguages'=>$source, 'targetLanguages'=>$target);
	}

	/*
	 * サービス情報をJS側の書式に変換
	 */
	private function __formatLangridServiceObjects($objects) {
		$list = array();
		foreach ($objects as $o) {
			$a = array();
			$a['service_id'] = $o->getServiceId();
			$a['service_type'] = $o->getServiceType();
			$a['allowed_app_provision'] = $o->getAllowedAppProvision();
			$a['service_name'] = $o->getServiceName();
			$a['endpoint_url'] = $o->getEndpointUrl();
			$a['supported_languages_paths'] = $o->getSupportedLanguagesPaths();
			$a['copyright'] = $o->getCopyright();
			$a['license'] = $o->getLicense();
			$a['description'] = $o->getDescription();
			$a['organization'] = $o->getOrganization();
			$list[] = $a;
		}
		return $list;
	}
}
?>