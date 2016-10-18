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

require_once(dirname(__FILE__).'/../class/client/LangridAccessClient.class.php');

$client = new LangridAccessClient();

echo '<h2>getSupportedTranslationLanguagePairs($translationBindingSetName = USER)</h2>';
echo '<pre>';
print_r($client->getSupportedTranslationLanguagePairs('USER'));
echo '</pre>';

echo '<h2>getSupportedTranslationLanguagePairsWithName($translationBindingSetName = USER)</h2>';
echo '<pre>';
print_r($client->getSupportedTranslationLanguagePairsWithName('USER'));
echo '</pre>';

echo '<h2>getSupportedTranslationLanguagePairs($translationBindingSetName = SITE)</h2>';
echo '<pre>';
print_r($client->getSupportedTranslationLanguagePairs('SITE'));
echo '</pre>';

echo '<h2>getSupportedTranslationLanguagePairsWithName($translationBindingSetName = SITE)</h2>';
echo '<pre>';
print_r($client->getSupportedTranslationLanguagePairsWithName('SITE'));
echo '</pre>';


//
//require_once(XOOPS_ROOT_PATH.'/modules/langrid/class/get-supported-language-pair-class.php');
//$GetSupportedLanguagePair = new GetSupportedLanguagePair();
//echo '<h2>GetSupportedLanguagePair->getLanguagePair()</h2>';
//echo '<pre>';
//print_r($GetSupportedLanguagePair->getLanguagePair());
//echo '</pre>';
//
//echo '<h2>GetSupportedLanguagePair->getLanguagePair()</h2>';
//echo '<pre>';
//print_r($GetSupportedLanguagePair->getLanguageNamePair());
//echo '</pre>';
?>
