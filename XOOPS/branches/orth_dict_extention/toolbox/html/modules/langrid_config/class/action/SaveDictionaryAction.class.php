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
/* $Id: SaveDictionaryAction.class.php 3877 2010-07-30 11:39:01Z yoshimura $ */

require_once(MY_MODULE_PATH.'/class/action/AjaxAction.class.php');

class SaveDictionaryAction extends AjaxAction {

    public function SaveDictionaryAction() {
    	parent::__construct();
    }

    public function execute() {
		parent::execute();
    	try {
    		$this->save($this->getPostData());
	    	$this->buildSuccessResult('');
    	} catch (Exception $e) {
    		$this->buildErrorResult($e->getMessage());
    	}
    }

    protected function getPostData() {
		$data = array();
		$data['global_dict_ids'] = urldecode($this->getParameter('global_dict_ids'));
		$data['local_dict_ids'] = urldecode($this->getParameter('local_dict_ids'));
		$data['user_dict_ids'] = urldecode($this->getParameter('user_dict_ids'));
		return $data;
    }

    protected function save($data) {
    	return null;
    }
}
?>