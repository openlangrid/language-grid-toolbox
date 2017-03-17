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
/* $Id: LoadServiceInfoAction.class.php 6266 2012-01-31 05:05:26Z mtanaka $ */

require_once(MY_MODULE_PATH.'/class/action/AjaxAction.class.php');
require_once(MY_MODULE_PATH.'/class/manager/LangridServiceDaoAdapter.class.php');

class LoadServiceInfoAction extends AjaxAction {

    public function LoadServiceInfoAction() {
    	parent::__construct();
    }

    public function execute() {
		parent::execute();

		$serviceId = $this->getParameter('serviceId');
		$res = $this->load($serviceId);
		$this->buildSuccessResult($this->getContents($res));
    }

	protected function load($serviceId) {
		$daoAdapter = new LangridServiceDaoAdapter();
		$res = $daoAdapter->searchLangridService($serviceId);
		if(trim($res['license']) == ""){
			$res['license'] = "-";
		}else{
			$res['license'] = $this->httpAutoLink($res['license']);
		}
		return $res;
	}

    protected function getContents($res) {
		$contents = '';
		$contents .= '<div class="info-body">';
		$contents .= '<form>';
		$contents .= '<h1>'.$res['service_name'].'</h1>';
		$contents .= '<div class="info-contents">';
		$contents .= '<dl>';
		$contents .= '<dt>'._MD_LANGRID_INFO_POP_PROVIDER.':</dt>';
		$contents .= '<dd>'.$res['organization'].'&nbsp;</dd>';
		$contents .= '<dt>'._MD_LANGRID_INFO_POP_COPYRIGHT.':</dt>';
		$contents .= '<dd>'.$res['copyright'].'&nbsp;</dd>';
		$contents .= '<dt>'._MD_LANGRID_INFO_POP_LICENSE.':</dt>';
		$contents .= '<dd>'.$res['license'].'&nbsp;</dd>';
		$contents .= '<dt>'._MD_LANGRID_INFO_POP_DESCRIPTION.':</dt>';
		$contents .= '<dd>'.$res['description'].'&nbsp;</dd>';
		$contents .= '</dl>';
		$contents .= '</div>';
		$contents .= '<div style="margin-top: 8px; text-align:center;">';
		$contents .= '<a class="btn" style="margin-left:150px;" onclick="Element.hide($(\'baloon-'.$res['service_id'].'\'));">';
		$contents .= '<img src="./images/icon/icn_close.gif" />'._MD_LANGRID_SETTING_CLOSE_BUTTON.' ';
		$contents .= '</a>';
		$contents .= '</div>';
		$contents .= '</form>';
		$contents .= '<br class="clear" />';
		$contents .= '</div>';

		return $contents;
    }

	public function httpAutoLink($text){
		return preg_replace("/(https?|ftp)(:\/\/[[:alnum:]\+\$\;\?\.%,!#~*\/:@&=_-]+)/",
					 "<a href=\"\\1\\2\" target=\"_blank\">\\1\\2</a>" , $text);
	}
}
?>