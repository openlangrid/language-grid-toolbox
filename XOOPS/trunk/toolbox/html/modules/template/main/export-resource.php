<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
require_once dirname(__FILE__).'/../class/manager/qa-permission-manager.php';
require_once dirname(__FILE__).'/../class/manager/translation-template-export-manager.php';
try {
	$name = $_GET['name'];
	if (!$name) {
		throw new Exception('Invalid parameter.');
	}
	$permissionManager = new QaPermissionManager();
	$permission = $permissionManager->getMyPermission($name);
	if ($permission < QaEnumPermission::READ) {
		throw new Exception('Invalid permission.');
	}

	$exportManager = new TranslationTemplateExportManager($name);
	$result = $exportManager->export();

	$utf16LEcontent = chr(255).chr(254).mb_convert_encoding($result, "UTF-16LE", "UTF-8");
	header('Content-Type: text/plain');
	header('Content-Disposition: attachment;filename="'.str_replace(' ', '_', $name).'.txt"');
	header('Cache-Control: max-age=0');
	echo $utf16LEcontent;

} catch (Exception $e) {
	header("HTTP/1.1 500 Internal Server Error");
	echo $e->getMessage();
}
exit();
?>