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
require_once 'test_util.php';
require_once XOOPS_TRUST_PATH.'/modules/'.$mydirname.'/class/'.basename(__FILE__);

require_once XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php';

echo '<p>'.camelize(basename(__FILE__)).' Test start</p>';
try {

$client = new FileSharingClient();
$response = $client -> getAllFolders("0");

$rt = Folder::getRoot();

assertEquals($rt -> getId(), $response['contents'][0] -> id);

foreach($rt -> getFolders() as $f) {
	assertEquals($rt -> getId(), $f -> getParentId());
}
foreach($rt -> getFiles() as $f) {
	var_dump($f);
	echo '<br>';
}



//$f = $rt -> getFiles();
//assertEquals(count($rt->getChilds()), count($f) + count($dirs));


//$client = new FileSharingClient();
//$response = $client -> getFile($f[0]->getId());
//var_dump($response);

echo '<p style="background-color:#F8FFF8;border:2px solid green;">SUCCESS ALL</p>';
} catch(Exception $e) {
	echo '<p style="background-color:#F8FFF8;border:2px solid red;">'.$e -> getMessage().'</p>';
}
