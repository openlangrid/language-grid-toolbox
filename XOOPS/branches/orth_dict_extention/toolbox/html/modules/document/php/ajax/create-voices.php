<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
require_once dirname(__FILE__).'/../../../../mainfile.php';
require_once dirname(__FILE__).'/../VoiceManager.php';

$result = array(
	'status' => 'OK',
	'message' => 'Success',
	'contents' => array()
);

// tmp以下にmp3を生成、「翻訳文+時間」にランダム値（0〜10000）を加えて作ったmp3ファイルのハッシュ値を返す
try {
	$lang = $_POST['language'];
	$sources = $_POST['sources'];
	
	$manager = new VoiceManager();
	$result['contents'] = $manager->createVoices($lang, $sources);
} catch (Exception $e) {
	$result['status'] = 'ERROR';
	$result['message'] = 'Error!';
	$result['contents'] = array();
}

echo json_encode($result);
?>