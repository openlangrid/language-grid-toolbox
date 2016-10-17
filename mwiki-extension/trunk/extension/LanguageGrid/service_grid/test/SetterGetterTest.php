<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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
require_once(dirname(__FILE__).'/../class/dto/ServiceGridTranslationBind.class.php');

$client =& new ServiceGridTranslationBind();


$client->setPathId("hoge");

$client->setExecId("hoge2");

echo '<h2>Set して Get してみた</h2>';
echo '<pre>';
print_r($client->getPathId());
echo '</pre>';
echo '<pre>';
print_r($client->getExecId());
echo '</pre>';
echo '<pre>';
print_r($client->getBindId());
echo 'blank';
echo '</pre>';

?>