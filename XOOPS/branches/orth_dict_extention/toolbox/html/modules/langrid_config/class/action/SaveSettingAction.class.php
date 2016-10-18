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
/* $Id: SaveSettingAction.class.php 3905 2010-08-10 06:17:51Z yoshimura $ */

require_once(MY_MODULE_PATH.'/class/action/AjaxAction.class.php');

class SaveSettingAction extends AjaxAction {

    public function SaveSettingAction() {
    	parent::__construct();
    }

    public function execute() {
		parent::execute();

		$res = array();
		try {
			$postData = $this->parsePostData();
//			debugLog('--- post data ---.');
//			debugLog(print_r($postData, true));
			if ($postData) {
				foreach ($postData as $data) {
					$ids = $this->save($data);
					$res[$data['index']] = implode(',', $ids);
				}
			} else {
				throw new Exception("Post data is empty.");
			}
	    	$this->buildSuccessResult($res);
		} catch (Exception $e) {
			$this->buildErrorResult($e->getMessage());
		}
    }

    protected function save($data) {
    	return null;
    }

	/**
	 * (non-php)
	 */
    protected function parsePostData() {
		$data = $this->getParameter('data');
		if ($data == null || !is_array($data)) {
			throw new Exception('SaveSettingAction post data is not found.');
		}

		$postData = array();

		foreach ($data as $row) {
			$tokens = explode('&', $row);
			$post = array();
			foreach ($tokens as $token) {
				$keyval = explode('=', $token);
				$post[$keyval[0]] = $keyval[1];
			}
			$post['id'] = urldecode($post['id']);
			for($i=1;$i<=3;$i++){
				$post['global_dict_'.$i] = urldecode($post['global_dict_'.$i]);
				$post['local_dict_'.$i] = urldecode($post['local_dict_'.$i]);
				$post['temp_dict_'.$i] = urldecode($post['temp_dict_'.$i]);
			}
			for($i=1;$i<=4;$i++){
				$post['morph_analyzer'.$i] = urldecode($post['morph_analyzer'.$i]);
			}
			$postData[] = $post;
		}

		return $postData;
    }

}
?>