<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
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

error_reporting(E_ALL);
require('../../mainfile.php');
header('Content-Type: text/html; charset=utf-8;');
//include(XOOPS_ROOT_PATH.'/header.php');

require_once(dirname(__FILE__).'/../class/client/ProfileClient.class.php');

$client = new ProfileClient();

echo '<h2>getAllUserIDs()</h2>';
echo '<pre>';
print_r($client->getAllUserIDs());
echo '</pre>';

echo '<h2>getCurrentUserID()</h2>';
echo '<pre>';
print_r($client->getCurrentUserID());
echo '</pre>';

echo '<h2>getProfile(test)</h2>';
echo '<pre>';
print_r($client->getProfile('test'));
echo '</pre>';

echo '<h2>getProfile(nouser)</h2>';
echo '<pre>';
print_r($client->getProfile('nouser'));
echo '</pre>';

echo '<h2>setProfile()</h2>';
echo '<pre>';
$obj = $client->getProfile('test');
$profile = $obj['contents'];
$profile->name = 'お名前';
print_r($client->setProfile($profile, 'test'));
echo '</pre>';

echo '<h2>getAllUserIDs()</h2>';
echo '<pre>';
print_r($client->getAllUserIDs());
echo '</pre>';

?>
