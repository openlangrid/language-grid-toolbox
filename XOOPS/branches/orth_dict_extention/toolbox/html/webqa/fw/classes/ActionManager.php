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
class ActionManager {
	function __construct() {

	}

	function dispatch(&$context) {
		// GETパラメータを基に、適切なActionクラスをロードして実行する
		$action = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['action'] );

		if ($action == '') {
			$action = 'default';
		}
		$context->set('action', $action);

		$actionFile = APP_ACTION_DIR.'/'.ucfirst($action).'Action.php';
		$actionClass = ucfirst($action).'Action';

		if (file_exists($actionFile)) {
			require_once($actionFile);
		} else {
			die("Action({$action})に対応するファイルが見つかりません。");
		}

		if (class_exists($actionClass)) {
			$obj = new $actionClass;
			$obj->initMlLang($context);
			return $obj->dispatch($context);
		} else {
			die("Action({$action})に対応するクラスが見つかりません。");
		}
	}
}
?>
