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
require_once(dirname(__FILE__).'/../class/client/extras/Develope_LangridAccessClient.class.php');

//$client2 =& new Develope_LangridAccessClient();
$client2 =& new LangridAccessClient();
$dictIds = array();
$sourceLang = 'ja';
$targetLang = 'en';
$source = '今日は"お腹"が痛いです。';
$options = array();
$options['type'] = 'lite';
$result = $client2->translate($sourceLang, $targetLang, $source, "SITE", $options);
echo '<pre>';
print_r($result);
echo '</pre>';
//echo '<h2>translate($sourceLang, $targetLang, $source, $translationBindingSetName) </h2>';
//echo '<pre>';
//print_r($client->translate('en', 'ja', 'How are you?', 'SITE') );
//echo '</pre>';

$client =& new LangridAccessClient();

echo '<h2>backTranslate($sourceLang, $intermediatetLang, $source, $translationBindingSetName) </h2>';
echo '<pre>';
print_r($client->backTranslate('en', 'ja', 'How are "you"?', 'SITE', $options) );
echo '</pre>';
//
//$client2 = new Develope_LangridAccessClient();
//
//echo '<h2>multisentenceTranslate($sourceLang, $targetLang, $source, $translationBindingSetName) </h2>';
//echo '<pre>';
//print_r($client2->multisentenceTranslate('en', 'ja', array('How are you?', 'Hello, world.'), 'SITE') );
//echo '</pre>';
//
//echo '<h2>multisentenceBackTranslate($sourceLang, $intermediatetLang, $source, $translationBindingSetName) </h2>';
//echo '<pre>';
//print_r($client2->multisentenceBackTranslate('en', 'ja', array('How are you?', 'Hello, world.'), 'SITE') );
//echo '</pre>';

//echo '<h2>getAllBindingSets()</h2>';
//echo '<pre>';
//print_r($result);
//echo '</pre>';

//echo '<h2>getAllMultihopTranslationBindings(ALL)</h2>';
//echo '<pre>';
//print_r($client->getAllMultihopTranslationBindings('ALL'));
//echo '</pre>';

//echo '<h2>createBindingSet($bindingSetName, $type, $bShared)</h2>';
//echo '<pre>';
//print_r($client->createBindingSet('NewBindName', 'translation', false));
//echo '</pre>';

//echo '<h2>deleteBindingSet(HOGE)</h2>';
//echo '<pre>';
//print_r($client->deleteBindingSet('HOGE'));
//echo '</pre>';

//echo '<h2>getAllLanguageServices()</h2>';
//echo '<pre>';
//print_r($client->getAllLanguageServices('translation'));
//echo '<hr>';
//print_r($client->getAllLanguageServices('dictionary'));
//echo '<hr>';
//print_r($client->getAllLanguageServices('paralleltext'));
//echo '<hr>';
//print_r($client->getAllLanguageServices('morphological_analyzer'));
//echo '<hr>';
//print_r($client->getAllLanguageServices('foobar'));
//echo '<hr>';
//echo '</pre>';



//echo '<h2>getAllBindingSets</h2>';
//echo '<pre>';
//print_r($client->getAllBindingSets("translation"));
//echo '</pre>';
////echo '<h2>createBindingSet($bindingSetName, $type, $bShared)</h2>';
////echo '<pre>';
////print_r($client->createBindingSet('HOGE', '', true));
////echo '</pre>';
//
//echo '<h2>backTranslate()</h2>';
//echo '<pre>';
////print_r($client->deleteBindingSet('HOGE'));
////print_r($client->getAllBindingSets("translation"));
////print_r($client->getAllMultihopTranslationBindings('BBS'));
////$vo = $client->getMultihopTranslationBinding('BBS', '1');
////$mtb = $vo['contents'];
////$mtb->translationBindings[0]->morphologicalAnalysisServiceId = 'MeCab';
//


//echo '<h2>addMultihopTranslationBinding</h2>';
//$mtb = new ToolboxVO_LangridAccess_MultihopTranslationBinding();
//$b = new ToolboxVO_LangridAccess_TranslationBinding();
//$b->sourceLang = 'zh';
//$b->targetLang = 'en';
//$b->translationServiceId = 'ToshibaMT';
//$b->morphologicalAnalysisServiceId = 'ICTCLAS';
//$b->globalDictionaryServiceIds = array('KyotoTourismDictionaryDb', 'EDRDictionary');
//$b->localDictionaryServiceIds = array('LOCAL', 'BindDict');
//$b->temporalDictionaryNames = array();
//
//$mtb->translationBindings[] = $b;
//
//$b = new ToolboxVO_LangridAccess_TranslationBinding();
//$b->sourceLang = 'en';
//$b->targetLang = 'ko';
//$b->translationServiceId = 'GoogleTranslate';
//$b->morphologicalAnalysisServiceId = 'TreeTger';
//$b->globalDictionaryServiceIds = array('EDRDictionary');
//$b->localDictionaryServiceIds = array();
//$b->temporalDictionaryNames = array('TestDict');
//
//$mtb->translationBindings[] = $b;
//echo '<pre>';
//print_r($client->addMultihopTranslationBinding('BBS', array('zh', 'en', 'ko'), $mtb->translationBindings));
//echo '</pre>';
//echo '<hr>';


//echo '<h2>setMultihopTranslationBinding</h2>';
//$mtb = new ToolboxVO_LangridAccess_MultihopTranslationBinding();
//$b = new ToolboxVO_LangridAccess_TranslationBinding();
//$b->sourceLang = 'zh';
//$b->targetLang = 'ja';
//$b->translationServiceId = 'GoogleTranslate';
//$b->morphologicalAnalysisServiceId = 'ICTCLAS';
//$b->globalDictionaryServiceIds = array('KyotoTourismDictionaryDb');
//$b->localDictionaryServiceIds = array('LOCAL', 'BindDict');
//$b->temporalDictionaryNames = array();
//
//$mtb->translationBindings[] = $b;
//
//$b = new ToolboxVO_LangridAccess_TranslationBinding();
//$b->sourceLang = 'ja';
//$b->targetLang = 'ko';
//$b->translationServiceId = 'NICTJServer';
//$b->morphologicalAnalysisServiceId = 'Mecab';
//$b->globalDictionaryServiceIds = array();
//$b->localDictionaryServiceIds = array();
//$b->temporalDictionaryNames = array('TestDict');
//
//$mtb->translationBindings[] = $b;
//echo '<pre>';
//echo $client->setMultihopTranslationBinding('BBS', '21', $mtb->translationBindings);
//echo '</pre>';


//print_r($client->backTranslate('ja', 'en', '日本人', 'BBS'));
////echo $client->deleteMultihopTranslationBinding('BBS', '4');
////print_r($client->getSupportedTranslationLanguagePairs('BBS'));
////print_r($client->getAllLanguageServices('translation'));
//echo '</pre>';
//
////echo '<h2>backTranslate()</h2>';
////echo '<pre>';
////print_r($client->backTranslate("en", "ja", "How are you?"));
////echo '</pre>';
//
?>
