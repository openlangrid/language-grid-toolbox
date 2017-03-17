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
require_once(dirname(__FILE__).'/../AbstractDao.class.php');

class LangridServicesConfigObject extends AbstractDaoObject {

	function LangridServicesConfigObject() {
		$this->initVar('config_id');
		$this->initVar('config_name');
		$this->initVar('config_value');
	}
}

class LangridServicesConfigDaoImpl extends AbstractDao implements ServiceGridLangridServicesConfigDAO {

	var $mTable = 'langrid_services_config';
	var $mPrimary = "config_id";
	var $mClass = "LangridServicesConfigObject";

	function getLangridServiceLastRefresh() {
		$obj = $this->get('1');
		if ($obj == null) {
			return 0;
		} else {
			return intval($obj->get('config_value'));
		}
	}

	function setLangridServiceLastRefresh($time = null) {
		if ($time == null) {
			$time = time();
		}
		$obj = $this->get('1');
		if ($obj == null) {
			$new = $this->create(true);
			$new->set('config_id', '1');
			$new->set('config_name', 'LanguageGridLastRefreshTime');
			$new->set('config_value', $time);
			$this->insert((array)$new->getVars(), false);
		} else {
			$obj->set('config_value', $time);
			$this->update((array)$obj->getVars());
		}
	}
}
?>
