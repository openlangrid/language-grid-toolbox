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
require(dirname(__FILE__).'/../../mainfile.php');
header('Content-Type: text/html; charset=utf-8;');
require_once(dirname(__FILE__).'/../db/adapter/DaoAdapter.class.php');
require_once(dirname(__FILE__).'/../ServiceGridClient.class.php');

$client = new ServiceGridClient();


echo '<h2>ServiceGridClient実装テスト</h2>';
//echo '<pre>';
//echo 'translationSet=>';
//$translationSet = $serviceGridTranslationSetting->getServiceSettings('1', '1');
//print_r($translationSet);
//echo '</pre>';
//$sourceLang, $targetLang, $source, $translationBindingSetName
echo '<pre>';
echo 'translationSet=>';
$result = $client->backtranslate('ja', 'en', '今日はとても暑いですね。', 'BBS');
print_r($result);
$result = $client->translate('en', 'ja', 'hello.', 'TEXT_TRANSLATION');
print_r($result);
$result = $client->backtranslate('en', 'ja', 'This is a pen.', 'TEXT_TRANSLATION');
print_r($result);
echo '</pre>';
?>