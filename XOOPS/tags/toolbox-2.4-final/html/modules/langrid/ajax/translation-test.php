<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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

require_once(dirname(__FILE__).'/../../../mainfile.php');
require_once(dirname(__FILE__).'/../php/langrid-client.php');

header('Content-Type: application/json; charset=utf-8;');

global $xoopsUser;
$path = $_POST['path'];
$source = $_POST['source'];

$langs = explode('2', $path);
$langridClient = new LangridClient(
	array(
		'sourceLang' => $langs[0],
		'targetLang' => $langs[1]
		),
	array(
			'appName'=>'TRANSLATION-SETTING-TEST',
			'loginUserId'=>$xoopsUser->getVar('uid'),
			'key01' => '0',
			'key02' => '0',
			'key03' => '0',
			'note1'=>''
		)
);

$dist = $langridClient->translate($source);

echo json_encode($dist);
?>