<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
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
global $wgRequest, $wgOut, $wgServer, $wgScriptPath, $wgTitle, $wgArticle;

$html = array();
$html[] = '<h3>サービスグリッド実装のテストページ</h3>';


if ($wgRequest->getCheck('TranslationSetDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/TranslationSetDaoImplTest.php');
} else if ($wgRequest->getCheck('TranslationPathDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/TranslationPathDaoImplTest.php');
} else if ($wgRequest->getCheck('TranslationExecDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/TranslationExecDaoImplTest.php');
} else if ($wgRequest->getCheck('TranslationBindDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/TranslationBindDaoImplTest.php');
} else if ($wgRequest->getCheck('LangridServiceDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/LangridServiceDaoImplTest.php');
} else if ($wgRequest->getCheck('DefaultDictionaryBindDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/DefaultDictionaryBindDaoImplTest.php');
} else if ($wgRequest->getCheck('DefaultDictionarySettingDaoImplTest')) {
	require_once(dirname(__FILE__).'/test/DefaultDictionarySettingDaoImplTest.php');
} else {
	$html[] = '<h4>各DAOのテストを行います。</h4>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&TranslationSetDaoImplTest=1').'">TranslationSetDaoImplTest.php</a>';
	$html[] = '<br>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&TranslationPathDaoImplTest=1').'">TranslationPathDaoImplTest.php</a>';
	$html[] = '<br>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&TranslationExecDaoImplTest=1').'">TranslationExecDaoImplTest.php</a>';
	$html[] = '<br>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&TranslationBindDaoImplTest=1').'">TranslationBindDaoImplTest.php</a>';
	$html[] = '<br>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&LangridServiceDaoImplTest=1').'">LangridServiceDaoImplTest.php</a>';
	$html[] = '<br>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&DefaultDictionaryBindDaoImplTest=1').'">DefaultDictionaryBindDaoImplTest.php</a>';
	$html[] = '<br>';
	$html[] = '<a href="'.$wgTitle->getFullURL('action=edit&daotest=1&DefaultDictionarySettingDaoImplTest=1').'">DefaultDictionarySettingDaoImplTest.php</a>';
}

$wgOut->addHTML(implode(PHP_EOL, $html));


function echoPre($a) {
	return '<pre>'.print_r($a, true).'</pre>';
}

?>