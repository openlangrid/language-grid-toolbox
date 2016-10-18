<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to view
// contents of BBS without logging into Toolbox.
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
/**
 * @author kitajima
 */
error_reporting(0);
require_once dirname(__FILE__).'/../class/translator/langrid-access-client-adapter.php';
$laca = new LangridAccessClientAdapter(
		$_POST['sourceLanguageCode']
		, $_POST['targetLanguageCode']
		, $_POST['logOptions']);
		

$sourceTexts = explode("\n", $_POST['sourceText']);
$firstSourceText = array_shift($sourceTexts);
preg_match('/^>* */', $firstSourceText, $matches);
$firstSourceText = preg_replace('/^>* */', '', $firstSourceText);
$result = $laca->translate($firstSourceText);

$result['contents']->targetText['contents'] = $matches[0].$result['contents']->targetText['contents'];
$result['contents']->backText['contents'] = $matches[0].$result['contents']->backText['contents'];
$result['contents']->{$_POST['targetLanguageCode']}['translation']['contents'] = $matches[0].$result['contents']->{$_POST['targetLanguageCode']}['translation']['contents'];
$result['contents']->{$_POST['targetLanguageCode']}['backTranslation']['contents'] = $result['contents']->{$_POST['targetLanguageCode']}['backTranslation']['contents'];

foreach ($sourceTexts as $sourceText) {
	$temp = $laca->translate($sourceText);
	$result['contents']->targetText['contents'] .= "\n".$temp['contents']->backText['contents'];
	$result['contents']->backText['contents'] .= "\n".$temp['contents']->backText['contents'];
	$result['contents']->{$_POST['targetLanguageCode']}['translation']['contents'] .= "\n".$temp['contents']->{$_POST['targetLanguageCode']}['translation']['contents'];
	$result['contents']->{$_POST['targetLanguageCode']}['backTranslation']['contents'] .= "\n".$temp['contents']->{$_POST['targetLanguageCode']}['backTranslation']['contents'];
}
echo json_encode($result);
?>