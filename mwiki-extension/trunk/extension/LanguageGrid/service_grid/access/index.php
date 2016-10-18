<?php
////$sourceText = '全角　スペース 半角スペース	タブ';
//$sourceText = 'Hello world by japan.　in';
//$d = strtolower(preg_replace('/\s+/', ' ', mb_convert_kana($sourceText, 's', 'utf-8')));
//echo $d;
//die();
//require_once(dirname(__FILE__).'/languagegrid_access/client/translation/Atomic_Translation.php');
//$Atomic_Translation = new Atomic_Translation("ToshibaMT");
//$Atomic_Translation_Res = $Atomic_Translation->translate("en", "zh", "This is an atomic language grid service, I have access for it.");
//print_r_pre($Atomic_Translation_Res);

//require_once(dirname(__FILE__).'/languagegrid_access/client/back_translation_with_temporal_dictionary/BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.php');
//$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  = new BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch("NICTJServer", "NICTJServer");
////$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setMorphologicalAnalyzer("TreeTagger");
//$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setMorphologicalAnalyzer("Mecab");
//$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setBilingualDictionaries(array("WikipediaEnDictionaryEntertainment"));
//$tempDictSoapVars = SoapValueCreation::createTemporalDictionaries(array(array("私", "WTS"),array("言語グリッド","Language Grid"),array("情報通信研究機構", "NICT")));
////$dist = $BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->backTranslate("ja", "en", "私たちは、言語グリッドを応援しています。", $tempDictSoapVars, "ja");
////print_r_pre($dist);

require_once(dirname(__FILE__).'/client/translation_with_temporal_dictionary/TranslationCombinedWithBilingualDictionary.php');
$TranslationCombinedWithBilingualDictionary = new TranslationCombinedWithBilingualDictionary("NICTJServer");
$TranslationCombinedWithBilingualDictionary->setMorphologicalAnalyzer("Mecab");
$TranslationCombinedWithBilingualDictionary->setBilingualDictionaries(array());
//$tempDictSoapVars = SoapValueCreation::createTemporalDictionaries(array(array("ChukanTest","試験"), array("Boku", "僕")));
$tempDictSoapVars = SoapValueCreation::createTemporalDictionaries(array());
$dist = $TranslationCombinedWithBilingualDictionary->translate("ja", "en", "お前さんはmisoが好き", null, "ja");
print_r_pre($dist);


//require_once(dirname(__FILE__).'/languagegrid_access/client/backtranslation/Composite_BackTranslation.php');
//$Composite_BackTranslation = new Composite_BackTranslation("NICTJServer", "NICTJServer");
//

//require_once(dirname(__FILE__).'/sa.php');
//$i = 0;
//foreach ($sa as $source) {
////	$dist = $Composite_BackTranslation->backTranslate("ja", "en", $source);
//	$dist = $BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->backTranslate("ja", "en", $source, $tempDictSoapVars, "ja");
//	print_r_pre(array("Index"=>$i++, "Source"=>$source));
//	print_r_pre($dist);
//}
//
////require_once(dirname(__FILE__).'/languagegrid_access/client/msic/ServiceManagement.class.php');
////$ServiceManagement = new ServiceManagement();
////$dist = $ServiceManagement->searchServices("TRANSLATION");
////print_r_pre($dist);
//
function print_r_pre($arg) {
	echo '<pre style="font-size:8px;">'.PHP_EOL;
	print_r($arg);
	echo '</pre>'.PHP_EOL;
}
//require_once(dirname(__FILE__).'/langrid/client/translation/Atomic_Translation.class.php');
//require_once(dirname(__FILE__).'/langrid/client/translation/TranslationWithBackup.class.php');
//require_once(dirname(__FILE__).'/langrid/client/translation_with_temporal_dictionary/TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.php');
//require_once(dirname(__FILE__).'/langrid/client/backtranslation/Composite_BackTranslation.php');
//require_once(dirname(__FILE__).'/langrid/client/back_translation_with_temporal_dictionary/BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch.php');
//require_once(dirname(__FILE__).'/langrid/client/morphological_analysis/Atomic_MorphologicalAnalysis.class.php');
//require_once(dirname(__FILE__).'/langrid/client/bilingual_dictionary/Atomic_BilingualDictionary.class.php');
//require_once(dirname(__FILE__).'/langrid/client/bilingual_dictionary/BilingualDictionaryCrossSearch.class.php');
//require_once(dirname(__FILE__).'/langrid/client/multihop_translation/TwoHopTranslation.class.php');
require_once(dirname(__FILE__).'/languagegrid_access/client/multihop_translation/ThreeHopTranslation.class.php');
//
//$times = array();
//
$sourceLang = "ja";
$targetLang = "en";
//$source = "日本国民は、正当に選挙された国会における代表者を通じて行動し、われらとわれらの子孫のために、諸国民との協和による成果と、わが国全土にわたって自由のもたらす恵沢を確保し、政府の行為によって再び戦争の惨禍が起ることのないやうにすることを決意し、ここに主権が国民に存することを宣言し、この憲法を確定する。そもそも国政は、国民の厳粛な信託によるものであって、その権威は国民に由来し、その権力は国民の代表者がこれを行使し、その福利は国民がこれを享受する。これは人類普遍の原理であり、この憲法は、かかる原理に基くものである。われらは、これに反する一切の憲法、法令及び詔勅を排除する。";
//$source = 'バートランド・ラッセルは『怠惰への讃歌』というエッセイの中で、「働く時間は1日 4時間に短縮すべし。功利主義からすれば甚だ無駄に見える時間を作り、学問における探求や美への追求を行えばいい」「文化的に価値のあるものは、非効率的な時間から生まれる」と提言している。このエッセイが執筆されたのは1932年。80年近く経った現在、「1日4時間労働」になる見込みはまだないが、ラッセルの言葉はなかなか示唆に富んでいる。新しいサービスや事業を作り出したり、顧客への付加価値を高める提案をしたりするためには、あえて「効率を上げること」や「無駄を省く」以外の視点が必要なのだ。そもそも、「生産性向上」とはどのようなことだろうか。「生産性」とは、財団法人 日本生産性本部が公表する「労働生産性指数」調査によれば「1時間当たりの生産量（業種によっては1時間当たりの販売金額）」のことを指す。こうした外部環境の変化の中でITエンジニアとして生き延びるためには、「1人で2人分の量がこなせる」「付加価値の高いものが作れる」、あるいは「コスト削減のアイデアを実現できる」などの付加価値を周囲に認めてもらう必要が出てくる。「仕事があるITエンジニア」と「仕事がないITエンジニア」の差は、今後さらに広がっていくだろう。ITエンジニアにとっては、いち技術者として高レベルの生産性向上を意識すべき時が来たのである。では、ITエンジニアは「生産性」を向上させるため、具体的にどのような行動をすればいいのだろうか。10分かかる作業を5分で終わらせることができれば、単純に生産性は2倍になる。生産の「量」を増やすなら、無駄な時間を省く時間管理や、ツールを利用して1件当たりにかける手間を省く仕事術が有効だ。無駄を省くことは、「コストの削減」にもつながる。しかしながら。';
$source = 'そのために、TK-80のユーザーの多くは、1つ1つの数字の組み合わせが何を意味しているか理解できないまま、とにかくリストどおりに間違いなくキーを押していく結果になりやすい。';
//
////$atomicTranslator = new Atomic_Translation('NICTJServer');
////print_r($atomicTranslator->translate("ja", "en", "日本国民は、正当に選挙された国会における代表者を通じて行動し、われらとわれらの子孫のために、諸国民との協和による成果と、わが国全土にわたって自由のもたらす恵沢を確保し、政府の行為によって再び戦争の惨禍が起ることのないやうにすることを決意し、ここに主権が国民に存することを宣言し、この憲法を確定する。そもそも国政は、国民の厳粛な信託によるものであって、その権威は国民に由来し、その権力は国民の代表者がこれを行使し、その福利は国民がこれを享受する。これは人類普遍の原理であり、この憲法は、かかる原理に基くものである。われらは、これに反する一切の憲法、法令及び詔勅を排除する。"));
//
////$TranslationWithBackup = new TranslationWithBackup('NICTJServer2', 'NICTJServer');
////print_r($TranslationWithBackup->translate("ja", "en", "日本国民は、正当に選挙された国会における代表者を通じて行動し、われらとわれらの子孫のために、諸国民との協和による成果と、わが国全土にわたって自由のもたらす恵沢を確保し、政府の行為によって再び戦争の惨禍が起ることのないやうにすることを決意し、ここに主権が国民に存することを宣言し、この憲法を確定する。そもそも国政は、国民の厳粛な信託によるものであって、その権威は国民に由来し、その権力は国民の代表者がこれを行使し、その福利は国民がこれを享受する。これは人類普遍の原理であり、この憲法は、かかる原理に基くものである。われらは、これに反する一切の憲法、法令及び詔勅を排除する。"));
////for($i = 0; $i < 10; $i++) {
////$times[] = echoTime("start");
////$TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch = new TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch('NICTJServer');
////$TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setMorphologicalAnalyzer("Mecab");
////$TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setBilingualDictionaries(array("LsdDb", "KyotoTourismDictionaryDb"));
////$tempDictSoapVars = SoapValueCreation::createTemporalDictionaries(array(array("日本国民","All Japan"), array("代表者","Hatoyama Yukio")));
////$dist = $TranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->translate($sourceLang, $targetLang, $source, $tempDictSoapVars, $targetLang);
//////print_r($dist);
////$times[] = echoTime("_end_");
////}
//
////$Composite_BackTranslation = new Composite_BackTranslation("GoogleTranslate", "GoogleTranslate");
////$dist = $Composite_BackTranslation->backTranslate($sourceLang, $targetLang, $source);
////print_r($dist);
//
////$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch  = new BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch("GoogleTranslate", "GoogleTranslate");
////$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setMorphologicalAnalyzer("Mecab");
////$BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->setBilingualDictionaries(array("LsdDb", "KyotoTourismDictionaryDb"));
////$tempDictSoapVars = SoapValueCreation::createTemporalDictionaries(array(array("日本国民","All Japan"), array("代表者","Hatoyama Yukio")));
////$dist = $BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->backTranslate($sourceLang, $targetLang, $source, $tempDictSoapVars, $targetLang);
////print_r($dist);
//
////$Atomic_MorphologicalAnalysis = new Atomic_MorphologicalAnalysis("Mecab");
////$dist = $Atomic_MorphologicalAnalysis->analyze($sourceLang, $source);
//////print_r($dist);
////foreach ($dist['contents'] as $translation) {
////	echo ',"',$translation->lemma.'"';
////}
//
////$Atomic_BilingualDictionary = new Atomic_BilingualDictionary("LsdDb");
////$dist = $Atomic_BilingualDictionary->search($sourceLang, $targetLang, "生命", "prefix");
////print_r($dist);
//
////$headWords = array("日本","国民","は","、","正当だ","選挙","する","れる","国会","に","おける","代表","者","を","通ずる","行動","する","、","われら","と","われら","の","子孫","の","ため","に","、","諸","国民","と","の","協和","に","よる","成果","と","、","わが国","全土","に","わたる","自由だ","の","もたらす","恵","沢","を","確保","する","、","政府","の","行為","に","よる","再び","戦争","の","惨禍","が","起る","こと","の","ない","や","うに","する","こと","を","決意","する","、","ここ","に","主権","が","国民","に","存ずる","する","こと","を","宣言","する","、","この","憲法","を","確定","する","。","そもそも","国政","は","、","国民","の","厳粛だ","信託","に","よる","もの","だ","、","その","権威","は","国民","に","由来","する","、","その","権力","は","国民","の","代表","者","が","これ","を","行使","する","、","その","福利","は","国民","が","これ","を","享受","する","。","これ","は","人類","普遍","の","原理","だ","、","この","憲法","は","、","かかる","原理","に","基","くも","のだ","。","われら","は","、","これ","に","反する","一切","の","憲法","、","法令","及び","詔","勅","を","排除","する","。");
////foreach ($headWords as $word) {
////$BilingualDictionaryCrossSearch = new BilingualDictionaryCrossSearch(array("LsdDb", "KyotoTourismDictionaryDb", "WikipediaJaDictionary", "KyotoSpecialityGlossary"));
////$dist = $BilingualDictionaryCrossSearch->search($sourceLang, $targetLang, $word, "partial");
////print_r($dist);
////}
////for ($i = 0; $i < 20; $i++) {
////$TwoHopTranslation = new TwoHopTranslation("NICTJServer", "NICTJServer");
////$dist = $TwoHopTranslation->multihopTranslate("ja", array("ko"), "ja", $source);
////print_r($dist);
////
$ThreeHopTranslation = new ThreeHopTranslation("NICTJServer", "NICTJServer", "NICTJServer");

require_once(dirname(__FILE__).'/sa.php');
$i = 0;
foreach ($sa as $source) {
//	$dist = $Composite_BackTranslation->backTranslate("ja", "en", $source);
//	$dist = $BackTranslationCombinedWithBilingualDictionaryWithLongestMatchSearch->backTranslate("ja", "en", $source, $tempDictSoapVars, "ja");
	$dist = $ThreeHopTranslation->multihopTranslate($sourceLang, array("en", "ja"), $targetLang, $source);
	print_r_pre(array("Index"=>$i++, "Source"=>$source));
	print_r_pre($dist);
}



////}
//echo '<hr>';
//foreach ($times as $time) {
//	echo $time;
//	echo '<br>';
//}
//function echoTime($label = "") {
//	list($micro, $Unixtime) = explode(" ", microtime());
//	$sec = $micro + date("s", $Unixtime); // 秒"s"とマイクロ秒を足す
//	return $label.':'. date("Y-m-d g:i:", $Unixtime).$sec;
//}



?>