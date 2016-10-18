<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

require_once APP_ROOT_PATH.'/class/action/AjaxAction.class.php';
require_once APP_ROOT_PATH.'/class/html/HtmlFormatter.class.php';
require_once APP_ROOT_PATH.'/class/toolbox/FileSharingAdapter.class.php';

class SaveHtmlAction extends AjaxAction {

	public function execute() {
		parent::execute();

		try {
			$fileName = $this->getParameter('fileName').'.html';
			$contents = $this->createContents();

			$file = FileSharingAdapter::factory(FileSharingAdapterType::HTML);
//			$file->save($fileName, $contents);

			$context = array(
				'folderId' => $this->getParameter('folderId'),
				'fileName' => $this->getFileName($this->getParameter('fileName')),
				'description' => $this->getParameter('description'),
				'readPermission' => $this->getParameter('readPermission'),
				'editPermission' => $this->getParameter('editPermission'),
			);
			$file->save($context, $contents);

			$this->buildSuccessResult(null);
		} catch (Exception $e) {
			$this->buildErrorResult($e->getMessage());
		}
	}

	/**
	 * @return String Formatted HTML
	 */
	private function createContents() {
		$contents = $this->getParameter('contents');

		if (!$contents) return $contents;

		$formatter = new HtmlFormatter();
		return $formatter->format($contents);
	}

	private function getFileName($base) {
		return $base . '.html';
	}
}
?>