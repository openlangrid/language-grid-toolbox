<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

require_once XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php';
require_once(APP_ROOT_PATH.'/class/toolbox/ToolboxUtil.class.php');

/**
 * @desc Enum
 */
class FileSharingAdapterType {
	const WORKSPACE = 0;
	const TEMPLATE = 1;
	const HTML = 2;

	private function __construct() {}
}

interface IFileSharingAdapter {

//	/**
//	 *
//	 * @param String $name
//	 * @param String $contents
//	 * @param bool $overwrite
//	 */
//	public function save($name, $contents, $description = '', $overwrite = false);

	/**
	 * @return array
	 */
	public function load();

	/**
	 * int $id
	 */
	public function read($id);
}

class FileSharingAdapter implements IFileSharingAdapter {

	// FileSharingClient
	protected $client;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->client = new FileSharingClient();
	}

	/**
	 * static factory
	 * @param FileSharingAdapterType $type
	 */
	public static function factory($type) {
		switch ($type) {
		case FileSharingAdapterType::WORKSPACE:
			return new WorkSpaceFileSharingAdapter();
		case FileSharingAdapterType::TEMPLATE:
			return new TemplateFileSharingAdapter();
		case FileSharingAdapterType::HTML:
			return new HtmlFileSharingAdapter();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/web_creation/class/toolbox/IFileSharingAdapter#save($name, $contents, $overwrite = false)
	 */
	public function save($name, $contents, $description = '', $overwrite = true) {
		$path = $this->createFile($contents);

		$permission = new ToolboxVO_FileSharing_Permission();
		$permission->type = 'public';
		$permission->userId = ToolboxUtil::getLoginId();

		$result = $this->client->addFile($path, $name, $description
			, $this->getFolderId(), $permission, $permission, $overwrite);

		if (strtoupper($result['status']) == 'ERROR') {
			throw new Exception($result['message']);
		}

		return $result;
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/web_creation/class/toolbox/IFileSharingAdapter#load()
	 */
	public function load() {
		$list = array();

		$result = $this->client->getAllFiles($this->getFolderId());
		foreach ($result['contents'] as $file) {
			if (!$this->isReadable($file)) {
				continue;
			}

			if (!$this->isFileValid($file)) {
				continue;
			}

			$list[] = array(
				'id' => $file->id,
				'name' => $file->name
			);
		}

		return $list;
	}

	/**
	 * (non-PHPdoc)
	 * @see html/modules/web_creation/class/toolbox/IFileSharingAdapter#read($id)
	 */
	public function read($id) {
		$result = $this->client->getFile($id);

		if (strtoupper($result['status'] == 'ERROR')) {
			throw new Exception($result['message']);
		}

		return file_get_contents($result['contents']->path);
	}

	/**
	 *
	 * @param unknown_type $id
	 * @throws Exception
	 */
	public function getFile($id) {
		$result = $this->client->getFile($id);

		if (strtoupper($result['status']) != 'OK') {
			throw new Exception($result['message']);
		}

		return $result['contents'];
	}

	/**
	 *
	 * @param String $contents
	 * @param int $counter
	 * @return Stirng path
	 */
	protected function generatePath($contents, $counter) {
		$path = $this->getPath();
		$path .= '/'.md5(time().$contents.rand());

		if (!file_exists($path) || $counter > 10) {
			return $path;
		}

		return $this->generatePath($path, ++$counter);
	}

	/**
	 * TODO ファイル名が重複してるとき、ファイルポインタ取得に失敗した時
	 * @param unknown_type $contents
	 */
	protected function createFile($contents) {
		$path = $this->generatePath($contents, 0);

		$fp = fopen($path, 'w');
		fwrite($fp, $contents);
		fclose($fp);

		return $path;
	}

	/**
	 * @param unknown_type $file
	 * @return bool
	 */
	protected function isFileValid($file) {
		return preg_match('/^.+\.xml$/', $file->name);
	}

	/**
	 * @param unknown_type $file
	 * @return bool
	 */
	protected function isReadable($file) {
		$permission = strtoupper($file->readPermission->type);
		$userId = $file->readPermission->userId;
		$myUserId = ToolboxUtil::getUserId();

		return (($permission == 'PUBLIC') || ($userId == $myUserId));
	}

	/**
	 * @return String path
	 */
	protected function getPath() {
		global $xoopsModuleConfig;
		$path = $xoopsModuleConfig['web_creation_folder_path'];
		if ($path == null || $path == 'web_creation_folder_path is empty.') {
			throw new Exception('');
		}
		if (!file_exists($path)) {
			throw new Exception('['.$path.'] is not found.');
		}
		return $path;
	}

	/**
	 * @return int Folder ID
	 */
	protected function getFolderId() {
		global $xoopsModuleConfig;
		return $xoopsModuleConfig['web_creation_folder_id'];
	}
}

class FileSharingAdapterUsedByDialog extends FileSharingAdapter {
	/**
	 * (non-PHPdoc)
	 * @see html/modules/web_creation/class/toolbox/IFileSharingAdapter#save($name, $contents, $overwrite = false)
	 */
	public function save($context, $contents, $overwrite = true) {
		$path = $this->createFile($contents);

		$read = new ToolboxVO_FileSharing_Permission();
		$read->type = $context['readPermission'];
		$read->userId = ToolboxUtil::getLoginId();
		$edit = new ToolboxVO_FileSharing_Permission();
		$edit->type = $context['editPermission'];
		$edit->userId = ToolboxUtil::getLoginId();

		$result = $this->client->addFile(
			$path,
			$context['fileName'],
			$context['description'],
			$context['folderId'],
			$read,
			$edit,
			$overwrite
		);

		if (strtoupper($result['status']) == 'ERROR') {
			throw new Exception($result['message']);
		}

		return $result;
	}
}

class WorkSpaceFileSharingAdapter extends FileSharingAdapterUsedByDialog {
	public function read($id) {
		$contents = parent::read($id);
		if ($contents == null || !preg_match('/<root app=\\"web_creation\\">/', $contents)) {
			throw new Exception(_MI_WEB_CREATION_FILEREAD_INVALID_WORK);
		}
		return $contents;
	}
}

class TemplateFileSharingAdapter extends FileSharingAdapterUsedByDialog {
}

class HtmlFileSharingAdapter extends FileSharingAdapterUsedByDialog {

	/**
	 * @param unknown_type $file
	 * @return bool
	 */
	protected function isFileValid($file) {
		return preg_match('/^.+\.html$/', $file->name);
	}

	public function read($id) {
		$contents = parent::read($id);
		if ($contents == null || !preg_match('/<html/', $contents)) {
			throw new Exception(_MI_WEB_CREATION_FILEREAD_INVALID_HTML);
		}
		return $contents;
	}
}
?>