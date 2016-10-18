<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

require_once XOOPS_ROOT_PATH.'/include/functions.php';
require_once dirname(__FILE__).'/../class/util/user.php';
require_once dirname(__FILE__).'/language-manager.php';

class Com_PostRevision {
	
	private $date;
	
	private $creator;
	
	private $expression;

	public function __construct($revision) {
		$this -> date = $revision -> date;
		$this -> creator = new User($revision -> creator);
		$this -> expression = $revision -> expression;
		//$this -> expressions = $revision['expression'];
	}
	
	public function getContents(){
		return $this -> revision['contents'];
	}
	
	public function getCreatorName() {
		return $this -> creator -> getName();
	}
	
	public function getDate() {
		return $this->date;
	}
	
	public function getCreateDateAsFormatString() {
		return CommonUtil::formatTimestamp($this->getDate(), _COM_DTFMT_YMDHI);
	}
	
	public function getEditLog() {
		$languageManager = LanguageManager::getManager();
		$languageManager->getSelectedLanguage();
		
		foreach($this->expression as $exp) {
			if($exp->language == $languageManager->getSelectedLanguage()) {
				return $exp->body;
			}
		}
		return $this->expression[0]->body;
	}
	
	public function getSelectedLanguage() {
	}

	
	static public function findAllByMessageId($messageId) {
		$root =& XCube_Root::getSingleton();
		$moduleName = $root->mContext->mModule->mXoopsModule->get('dirname');
		
		$bbs = new BBSClient($moduleName);
		$revisions = $bbs -> getMessageRevisions($messageId);
		$results = array();
		if ($revisions['status'] == 'OK') {
			foreach($revisions['contents'] as $revi) {
				$postRevi = new Com_PostRevision($revi);
				array_push($results, $postRevi);
			}
		}
		
		return $results;
	}

}
?>