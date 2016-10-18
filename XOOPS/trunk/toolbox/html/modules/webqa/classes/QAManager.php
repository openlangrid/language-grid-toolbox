<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
class QAManager {

	public function __construct() {
		$this->spoofingLoginUser();
	}

	public function getCategory() {
//		global $xoopsModuleConfig;
//		return $xoopsModuleConfig['webqa_posting'];

//		require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');
//		$client = new LangridAccessClient();
//		return $client->translate('en', 'ja', 'Those scenes are spoofing other movies.', 'bbs');

		return array(
			array('id' => '1', 'name' => 'category1', 'qCount' => '2', 'language' => 'en')
			,array('id' => '2', 'name' => 'category2', 'qCount' => '2', 'language' => 'en')
			,array('id' => '3', 'name' => 'category3', 'qCount' => '2', 'language' => 'en')
		);

	}

	public function search() {
		return array(
			array('id' => '1', 'question' => '質問の本文1', 'answers' => array('回答本文1', '回答本文2'), 'datetime' => 'YYYY/MM/DD HH:MI:SS')
			,array('id' => '2', 'question' => '質問の本文2', 'answers' => array('回答本文1', '回答本文2', '回答本文3'), 'datetime' => 'YYYY/MM/DD HH:MI:SS')
		);
	}

	public function load() {
		return array('id' => '1', 'question' => '質問の本文1', 'answers' => array('回答本文1', '回答本文2'), 'datetime' => 'YYYY/MM/DD HH:MI:SS');
	}

	public function getLanguageBySearch() {
		return array('en', 'ja');
	}

	public function getLanguageByPosting() {
		return array('en', 'ja');
	}


	/**
	 * 管理者になりすます。
	 */
	public function spoofingLoginUser() {
		require_once(XOOPS_ROOT_PATH.'/modules/user/class/users.php');
		$root =& XCube_Root::getSingleton();
		$userhandler = new UserUsersHandler($root->mController->mDB);
		$user =& $userhandler->get('1');
		$root->mContext->mXoopsUser =& $user;
	}

}

?>
