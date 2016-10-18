<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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

require_once(dirname(__FILE__).'/api/class/client/Wikimedia_LangridAccessClient.class.php');
$client =& new Wikimedia_LangridAccessClient();

$wgOut->addHTML('<pre>');
$wgOut->addHTML('<h3>LangridExtension内で記事を識別する情報</h3>');
$wgOut->addHTML(print_r(LanguageGridArticleIdUtil::getTitleDbKey(), true));
$wgOut->addHTML('</pre>');

$wgOut->addHTML('<pre><h3>サポート言語対 getSupportedTranslationLanguagePairs()</h3>');
$wgOut->addHTML(print_r($client->getSupportedTranslationLanguagePairs($wgTitle), true));
$wgOut->addHTML('</pre>');

$src = 'はじめまして、私は、おみそを長年作り続けております。';
$dist =& $client->translate('ja', 'en', $src, $wgTitle);
$wgOut->addHTML('<pre>');
$wgOut->addHTML(print_r($dist, true));;
$wgOut->addHTML('</pre>');
//$wgOut->addHTML('<pre>');
//$wgOut->addHTML('<h3>翻訳テスト</h3>');
//$wgOut->addHTML('<h4>INPUT : ja source='.$src.'</h4>');
//$wgOut->addHTML(print_r($dist, true));
//$wgOut->addHTML('</pre>');
//
//
//$src = 'Miso is a traditional food in Japan.';
//$dist =& $client->translate('en', 'ja', $src, $wgTitle);
//
//$wgOut->addHTML('<pre>');
//$wgOut->addHTML('<h3>翻訳テスト</h3>');
//$wgOut->addHTML('<h4>INPUT : en source='.$src.'</h4>');
//$wgOut->addHTML(print_r($dist, true));
//$wgOut->addHTML('</pre>');
//
//$srcAry = array('日本の伝統的な文化について知りたいです。', '京都の歴史について知りたいです。', '日本人にとっても京都は魅力的な土地です。');
////$dist =& $client->multisentenceTranslate('ja', 'en', $srcAry, $wgTitie, SourceTextJoinStrategyType::Normal);
//$dist =& $client->multisentenceTranslate('ja', 'en', $srcAry, $wgTitie, SourceTextJoinStrategyType::Customized);
//
//$wgOut->addHTML('<pre>');
//$wgOut->addHTML('<h3>複数行翻訳テスト</h3>');
//$wgOut->addHTML('<h4>INPUT : ja source='.print_r($srcAry, true).'</h4>');
//$wgOut->addHTML(print_r($dist, true));
//$wgOut->addHTML('</pre>');
//
//$srcAry = array('Are you a student? ', 'From when would you like to move?', 'Do you learn in Japan? ');
//$dist =& $client->multisentenceBackTranslate('en', 'ja', $srcAry, $wgTitle, SourceTextJoinStrategyType::Customized);
////$dist =& $client->multisentenceBackTranslate('en', 'ja', $srcAry, $wgTitle, SourceTextJoinStrategyType::Normal);
//
//$wgOut->addHTML('<pre>');
//$wgOut->addHTML('<h3>複数行折返し翻訳テスト</h3>');
//$wgOut->addHTML('<h4>INPUT : en source='.print_r($srcAry, true).'</h4>');
//$wgOut->addHTML(print_r($dist, true));
//$wgOut->addHTML('</pre>');

?>
