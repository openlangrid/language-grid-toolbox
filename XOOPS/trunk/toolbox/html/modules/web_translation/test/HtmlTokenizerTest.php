<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

require_once dirname(__FILE__).'/../../../mainfile.php';
require_once dirname(__FILE__).'/../common.php';
require_once dirname(__FILE__).'/../class/http/HttpClient.class.php';
require_once dirname(__FILE__).'/../class/html/HtmlPathTranslator.class.php';
require_once dirname(__FILE__).'/../class/html/HtmlTokenizer.class.php';
require_once dirname(__FILE__).'/../class/translation/Translator.class.php';

// コンテンツ取得
$url = 'http://langrid.nict.go.jp/en/';
//$html = file_get_contents('http://langrid.nict.go.jp/en/index.html');

$c = new HttpClient();
$html = $c->getContents($url);

echo '<pre>';
echo htmlspecialchars($html, ENT_QUOTES);
var_dump(array());
$pt = new HtmlPathTranslator();
$html = $pt->translate($html, $url);

echo htmlspecialchars($html, ENT_QUOTES);

die;

$t = new StandardsHtmlTokenizer($html);
var_dump($t->getTokens());
//$trace = print_r($t->getTokens(), 1);
//$trace = htmlspecialchars($trace, ENT_QUOTES);

//$sourceLang = 'en';
//$targetLang = 'ja';
//$t = Translator::factory($sourceLang, $targetLang);
//var_dump($t->translate($trace));
die;
echo '<pre>';
print_r($trace);
//foreach ($t->getTokens() as $token) {
//	echo $token->token;
//}
//$t = new SimpleHtmlTokenizer($html);
//$trace = print_r($t->getTokens(), 1);
//$trace = htmlspecialchars($trace, ENT_QUOTES);
//print_r($trace);
?>