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

require_once XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php';
require_once dirname(__FILE__).'/util/user.php';
require_once dirname(__FILE__).'/common_util.php';

// this class is wrapper of ToolboxVO_FileSharing_File Object
class AbstractFile {
	const FILE_SHARING_CATEGORY_ID = 1;
	
	protected $fileObject;
	
	
	public function __construct($fileObject) {
		$this -> fileObject = $fileObject;
	}
	
	public function getId() {
		return $this -> fileObject -> id;
	}
	
	public function getName() {
		if(is_a($this, "Folder") && $this -> isRoot() ) {
			return "File sharing top";
		} else {
			return $this -> fileObject -> name;
		}
	}
	
	public function getUser() {
		return new User($this -> getUserId());	
	}
	
	public function getUserId() {
		$pc = new ProfileClient();
		$user = $pc -> getProfile($this -> fileObject -> owner);
		return $user['contents']->id;
	}
	
	public function getUserName() {
		return $this -> getUser() -> getName();
	}
	
	public function getReadPermission() {
		return $this -> fileObject -> readPermission -> type;
	}
	
	public function getWritePermission() {
		return $this -> fileObject -> editPermission -> type;
	}
	
	public function canRead() {
		return $this -> hasPermission($this -> fileObject -> readPermission);
	}
	
	public function canWrite() {
		return $this -> hasPermission($this -> fileObject -> editPermission);
	}
	
	protected function hasPermission($permission) {
		return strtoupper($permission -> type) == 'PUBLIC' ||
			   (strtoupper($permission -> type) == 'USER' && $permission -> userId == getCurrentUserLoginId());
	}
	
	public function getDescription() {
		return $this -> fileObject -> description;
	}
	
	public function getUpdateDate() {
		return $this -> fileObject -> updateDate;
	}
	
	public function getUpdateDateAsFormatString() {
		return CommonUtil::formatTimestamp($this -> getUpdateDate(), _COM_DTFMT_YMDHI);
	}
	
	public function getExt() {
		return ereg_replace(".*\\.(.{3,4})$", "\\1", $this -> getName());
	}
	
	public function htmlStyleClass() { 
		$result = "";
		if($this -> canRead()) $result .= " readable ";
		if($this -> canWrite()) $result .= " writeable ";
		return $result;
	}
	
}

class Folder extends AbstractFile {
	const ROOT_ID = "0";
	
	public function getChilds() {
		return array_merge($this -> getFolders(), $this -> getFiles());
	}
	
	public function getFolders() {
		return self::findAll($this -> getId());
	}
	
	public function getFiles() {
		return File::findAll(array('cid' => $this -> getId()));
	}
	
	public function getParentId() {
		return $this -> fileObject -> parentId;
	}
	
	public function isRoot() {
		return $this -> getParentId() == self::ROOT_ID;
	}
	
	public function getParents() {
		if($this -> isRoot()) {
			return array();
		} else {
			$parent = self::findById($this -> getParentId());
			$parents = $parent -> getParents();
			array_push($parents, $parent);
			return  $parents;
		}
	}
	
	static public function findAll($pid = self::ROOT_ID) {
		$results = array();
		$client = new FileSharingClient();
		if(!$pid) $pid = self::ROOT_ID;
		$response = $client -> getAllFolders($pid);
		foreach($response['contents'] as $file) {
			array_push($results, new Folder($file));
		}
		return $results;
	}
	
	static public function findById($pid) {
		$client = new FileSharingClient();
		$response = $client -> getFolder($pid);
		if($response['contents'])
			return new Folder($response['contents']);
	}

	static public function getRoot() {
		$client = new FileSharingClient();
		$response = $client -> getAllFolders(self::ROOT_ID);
		if($response['contents'][0])
			return new Folder($response['contents'][0]);
	}
}

class File extends AbstractFile {
	
	public function getAbsolutePath() {
		return $this -> getPath();
	}
	
	public function getPath() {
		return $this -> fileObject -> path;
	}
	
	public function getFolderId() {
		return $this -> fileObject -> folderId;
	}
	
	static public function findAll($options = array()) {
		$results = array();
		$client = new FileSharingClient();
		$response = $client -> getAllFiles($options['cid'], @$options['offset'], @$options['limit']);
		foreach($response['contents'] as $file) {
			array_push($results, new File($file));
		}
		return $results;
	}
	
	static public function findById($fileId) {
		$client = new FileSharingClient();
		$response = $client -> getFile($fileId);
		if($response['status'] == 'OK') {
			return new File($response['contents']);
		}
	}
	
//	public function isWorkDocument() {
//		return $this -> hasWorkDcoument();
//	}
//	
//	public function hasWorkDocument() {
//		return !!$this -> getWorkDocument();
//	}
//	
//	private $workDocument;
//	public function getWorkDocument() {
//		if(!$this -> workDocument) {
//			$this -> workDocument = WorkDocument::findByFileId($this -> getId());
//		}
//		return $this -> workDocument;
//	}
	
}
?>
