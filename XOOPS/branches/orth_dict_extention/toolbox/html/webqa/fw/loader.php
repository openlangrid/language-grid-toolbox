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
// 必要なファイルのインクルードをここで一括して行う。
if (!defined('APP_ROOT_DIR')) {
	die("アプリのルートディレクトリが設定されていません。");
}


define('APP_FW_DIR', APP_ROOT_DIR.'/fw');
define('APP_FW_CLASS_DIR', APP_FW_DIR.'/classes');

$_require_list = array(
	APP_FW_DIR.'/config.php',
	APP_FW_CLASS_DIR.'/Context.php',
	APP_FW_CLASS_DIR.'/ActionManager.php',
	APP_FW_CLASS_DIR.'/ViewManager.php',
	APP_FW_CLASS_DIR.'/Controller.php',
	APP_FW_CLASS_DIR.'/Action.php',
	APP_FW_CLASS_DIR.'/View.php',
	APP_FW_CLASS_DIR.'/AbstractAction.php',
	APP_FW_CLASS_DIR.'/AbstractView.php',

	// for WebQA
	APP_ROOT_DIR.'/classes/QAManager.php',

	// for smarty.
	//APP_ROOT_DIR.'/fw/lib/Smarty-2.6.26/libs/Smarty.class.php',

	// resource filter for faking "db" descriptor
	APP_ROOT_DIR.'/fw/dbtemplatewrapper.php',
);

foreach ($_require_list as $file) {
	if (!file_exists($file)) {
		die("必要なファイル({$file})が読み込めません。");
	}
	require_once($file);
}

?>
