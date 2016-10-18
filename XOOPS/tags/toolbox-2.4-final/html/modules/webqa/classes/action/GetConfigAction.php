<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__).'/../Abstract_WebQABackendAction.class.php');

class GetConfigAction extends Abstract_WebQABackendAction {
	public function dispatch() {
		$context = 0;
		$param = $this->_getParam();
		
		switch($param){
			case 1:		//webqa_posting
				require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
				$qaclient =& new QAClient();

				$config = $this->getModuleConfig();
				$name = $config['webqa_posting'];

				$result =& $qaclient->getAllRecords($name);

				if ($result['status'] == 'OK') {
					$context = 1;
				}
				break;
			case 2:		//webqa_search
				require_once(XOOPS_ROOT_PATH.'/api/class/client/QAClient.class.php');
				$qaclient =& new QAClient();

				$config = $this->getModuleConfig();
				$names = explode(',', $config['webqa_search']);
				foreach ($names as $name) {
					if (!$name) {
						continue;
					}
					$result =& $qaclient->getAllRecords($name);
					if ($result['status'] == 'OK') {
						$context = 1;
					}
				}
				
				break;
//			case 3:		//webqa_for_bbs
//				require_once(XOOPS_ROOT_PATH.'/api/class/client/BBSClient.class.php');
//				$bbsclient =& new BBSClient('forum');
//
//				$config = $this->getModuleConfig();
//				$fid = $config['webqa_for_bbs'];
//
//				$result =& $bbsclient->getForum($fid);
//				if ($result != NULL) {
//					$context = 1;
//				}
//				break;
		}
		
		return json_encode($context);
	}

	private function _getParam() {
		return @$this->getParameter('mode');
	}
}
?>
