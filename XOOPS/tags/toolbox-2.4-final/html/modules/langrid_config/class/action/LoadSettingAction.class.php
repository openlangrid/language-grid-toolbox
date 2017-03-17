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
/* $Id: LoadSettingAction.class.php 4679 2010-11-04 09:04:22Z kitajima $ */

require_once(MY_MODULE_PATH.'/class/action/AjaxAction.class.php');
require_once(MY_MODULE_PATH.'/class/manager/LangridServiceDaoAdapter.class.php');

class LoadSettingAction extends AjaxAction {

	/**
	 * @overwrite
	 */
    public function __construct() {
    	parent::__construct();
    }

    /**
     * @overwrite
     */
    public function execute() {
    	parent::execute();

    	try {
    		$contents = $this->load();
	    	$this->buildSuccessResult($contents);
    	} catch (Exception $e) {
    		$this->buildErrorResult($e->getMessage());
    	}
    }

    public function load() {
		$contents = array();
		$contents = array_merge($contents, $this->loadServices());

		$contents['DefaultDicts'] = $this->loadDefaultDictionary();
		$contents['setting'] = $this->loadSetting();

		return $contents;
    }

	/**
	 * 言語グリッドサービス情報をロード
	 */
    protected function loadServices() {
		$daoAdapter = new LangridServiceDaoAdapter();
    	$contents = $daoAdapter->loadLangridService();
		return $contents;
    }

	/**
	 * デフォルト辞書設定をロード
	 */
    protected function loadDefaultDictionary() {
    	return null;
    }

	/**
	 * 翻訳パス設定をロード
	 */
    protected function loadSetting() {
    	return null;
    }
}
?>