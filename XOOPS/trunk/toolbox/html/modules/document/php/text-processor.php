<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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

define("MAX_OFFSET", 100000);

if(isset($_GET['lang']) && isset($_GET['source'])){
	if(!isset($_GET['mode']) || $_GET['mode'] == 'preprocess'){
		$lang = $_GET['lang'];
		$source = $_GET['source'];
		echo preprocessOriginal($lang, $source);
	} else if($_GET['mode'] == 'split'){
		$lang = $_GET['lang'];
		$source = $_GET['source'];
		$dirty = preprocessOriginal($lang, $source);
		echo $dirty."\n<br>";
		while($dirty != ''){
			$parsed = get_first_sentence($lang, $dirty);
			$contents[]  = $parsed['first'];
			$dirty = $parsed['remain'];
			echo "first: '".$parsed['first']."',  remain: '".$parsed['remain']."'\n<br>";
		}
		echo var_export($contents, true);
	}
}

function preprocessOriginal($lang, $content){
	$content = str_replace("\\","",$content);
	$content = str_replace("&nbsp;"," ",$content);

	$content = preg_replace("/[ \t\x0b\x0c]*((\r\n?)|(\r?\n))[ \t\x0b\x0c]*/","[[#ret]]",$content);

	$enabled_tags = array('ul', 'li', 'ol','dt', 'dl', 'dd', 'table', 'tr', 'th', 'td', 'br', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');

	$allowable_tags = '';
	foreach($enabled_tags as $tag){
		$allowable_tags .= '<'.$tag.'>';
	}
	$content = strip_tags($content, $allowable_tags);

	$content = ExceptionWord::encodeExceptionWord($content);
	$content = ExceptionWord::encodeInvalidSeparatorWithLanguage($content, $lang);

	return $content;
}

function get_first_sentence($lang, $content){
	$separators = ExceptionWord::getSeparators($lang);

	mb_internal_encoding("UTF-8");

	$tag = '';
	$firstTagOffset = MAX_OFFSET;
	while(true){
		$content = trim($content);

		if(!mb_ereg('<\/?[^<>]*>', $content, $regs)) {
			break;
		}

		$tempOffset = mb_strpos($content, $regs[0]);
		if($tempOffset != 0) {
			$firstTagOffset = $tempOffset;
			break;
		}

		$tag .= $regs[0];
		$content = mb_substr($content, mb_strlen($regs[0]));
	}

	$separatorIndex = MAX_OFFSET;
	foreach($separators as $sep){
		$pos = mb_strpos($content, $sep);
		if($pos !== FALSE && $pos+1 < $separatorIndex) {
			$separatorIndex = $pos+1;
		}
	}

	$retIndex = mb_strpos($content, '[[#ret]]');
	if($retIndex === FALSE){
		$retIndex = MAX_OFFSET;
	} else if($retIndex === 0){
		$first = '[[#ret]]';
		$tag = '';
		$remain = mb_substr($content, mb_strlen('[[#ret]]'));
		return compact("first","tag","remain");
	}

	$minIndex = min($separatorIndex, $firstTagOffset);
	$minIndex = min($minIndex, $retIndex);
	if($minIndex == MAX_OFFSET) {
		$first = $content;
		$remain = '';
	} else {
		$first = mb_substr($content, 0, $minIndex);
		$remain = mb_substr($content, $minIndex);
	}

	$first = trim(ExceptionWord::dec($first));

	return compact("first","tag","remain");
}

function __make_editor_translation_results($editorManager, $targetEditorNumbers){
	$retArray = array();
	foreach($targetEditorNumbers as $targetEditorNumber) {
		$text = "";
		for($i=0;$i<$editorManager->count($targetEditorNumber);$i++){
			if(preg_match('/^\s*<.*>\s*$/', $editorManager->getTag($i,$targetEditorNumber))){
				$text .= $editorManager->getTag($i,$targetEditorNumber);
			}
			$text .= '<span id="sentence-'.($targetEditorNumber+1).'-'.($i+1).'"';
			if($editorManager->hasErrorFlag($i,$targetEditorNumber)) {
				$text .= 'style="color:red;font-weight:bold;"';
			}
			$text .= '>';
			$text .= htmlspecialchars($editorManager->getSentence($targetEditorNumber,$i), ENT_NOQUOTES, 'UTF-8');
			$text .= '</span>';
			$text .= ' ';
		}
		$text .= $editorManager->getTag($editorManager->count($targetEditorNumber),$targetEditorNumber);
		$retArray[] = $text;
	}

	return $retArray;
}

class BrowserIdentifier{
	public static function getBrowserName(){
		$userAgent = $_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/opera/i', $userAgent)) {
			return 'Opera';
		} else if(preg_match('/msie/i', $userAgent)) {
			return 'Internet Explorer';
		} else if(preg_match('/chrome/i', $userAgent)) {
			return 'Google Chrome';
		} else if(preg_match('/safari/i', $userAgent)) {
			return 'Safari';
		} else if(preg_match('/firefox/i', $userAgent)) {
			return 'Firefox';
		} else if(preg_match('/gecko/i', $userAgent)) {
			return 'Gecko';
		} else {
			return $userAgent;
		}
	}

	public static function isIE(){
		return self::getBrowserName() == 'Internet Explorer';
	}

	public static function isFF(){
		return self::getBrowserName() == 'Firefox';
	}

	public static function isSafari(){
		return self::getBrowserName() == 'Safari';
	}

	public static function isOpera(){
		return self::getBrowserName() == 'Opera';
	}

	public static function isChrome(){
		return self::getBrowserName() == 'Google Chrome';
	}

	public static function isGecko(){
		return self::getBrowserName() == 'Gecko';
	}
}

class ExceptionWord{
	protected static $spaceLanguages = array('bg','de','en','es','fr','it','pt');

	protected static $separatorArrays = array(
		'bg' => array('.','?','!'),
		'de' => array('.','?','!'),
		'en' => array('.','?','!'),
		'es' => array('.','?','!'),
		'fr' => array('.','?','!'),
		'it' => array('.','?','!'),
		'ja' => array('。','．','.','？','！','?','!'),
		'ko' => array('.','?','!'),
		'pt' => array('.','?','!'),
		'zh' => array('。','？','?','!','！','．','.')
	);

	protected static $exceptionWords = array(
		'dot' => array(
			/*a:*/ array('a.','Abb.','accel.','ahd.','Al.','Anm.','Anon.','approx.','Apr.','Apt.','art.','Aug.','av.','Ave.'),
			/*b:*/ array('b.','Bd.','Bde.','bld.','bldg.','Blvd.','bzw.'),
			/*c:*/ array('c.','ca.','cf.','chap.','chaps.','cho.','Co.','col.','col Ped.','Corp.','corp.','cresc.'),
			/*d:*/ array('d.','Dec.','decresc.','ders.','dept.','dimin.','do.','Dr.',),
			/*e:*/ array('e.','ea.','ed.','eds.','enc.','env.','etc.','exp.','ex.'),
			/*f:*/ array('f.','Feb.','ff.','fig.','Fl.','figs.','fol.','Fri.'),
			/*g:*/ array('f.','Gl.','govt.'),
			/*h:*/ array('g.','Hg.','hg.','Hgg.','Hrsg.','hmhge.','hrsg.'),
			/*i:*/ array('i.','ib.','ibid.','id.','Inc.','inc.','inv.'),
			/*j:*/ array('j.','Jan.','Jg.','Jul.','Jun.','Jr.'),
			/*k:*/ array('k.'),
			/*l:*/ array('l.','ll.','Ln.','Ltd.','ltd.','lib.'),
			/*m:*/ array('m.','Mar.','mdse.','Messers.','mhd.','mo.','Mon.','Mr.','Mrs.','Ms.'),
			/*n:*/ array('n.','nd.','nhd.','Nm.','nn.','no.','nos.','Nov.','Nr.'),
			/*o:*/ array('o.','Oct.','od.','op.cit.'),
			/*p:*/ array('p.','par.','pars.','Ph.D.','pl.','p.m.','pmk.','po.','policli.','pp.','Prof.','pseud.'),
			/*q:*/ array('q.','qtr.'),
			/*r:*/ array('r.','rall.','Rd.','Re.','rec.','REG.','Ret.','rinforz.','rinfz.','rit.','ritard.','Rm.','Rp.'),
			/*s:*/ array('s.','Sat.','sec.','Seg.','Sep.','Sept.','SFOR.','Sig.','smorz.','Sp.','spp.','so.','St.','st.','Sun.','Syn.'),
			/*t:*/ array('t.','Taf.','T.B.','T.H.I.','Thu.','t.i.d.','trans.','transl.','Tue'),
			/*u:*/ array('u.','ut.','UVs.'),
			/*v:*/ array('v.','vgl.','viz.','Vol.','vol.','volz.','vs.','ver.'),
			/*w:*/ array('w.','WC.','Wed.','wk.','wks.'),
			/*x:*/ array('x.'),
			/*y:*/ array('y.'),
			/*z:*/ array('z.'),
			/*other:*/ array('übers.')
		),
		'question' => array(array()),
		'exclamation' => array(array()),
		'kuten' => array(array()),
		'mbdot' => array(array()),
		'mbexclamation' => array(array()),
		'mbquestion' => array(array())
	);

	protected static $replace = array(
		array('name' => 'dot', 'symbol' => '.', 'rule' => '[[#dot]]'),
		array('name' => 'question', 'symbol' => '?', 'rule' => '[[#question]]'),
		array('name' => 'exclamation', 'symbol' => '!', 'rule' => '[[#exclamation]]'),
		array('name' => 'kuten', 'symbol' => '。', 'rule' => '[[#kuten]]'),
		array('name' => 'mbdot', 'symbol' => '．', 'rule' => '[[#mbdot]]'),
		array('name' => 'mbexclamation', 'symbol' => '！', 'rule' => '[[#mbexclamation]]'),
		array('name' => 'mbquestion', 'symbol' => '？', 'rule' => '[[#mbquestion]]')
	);

	public static function getSeparators($lang){
		if(array_search($lang,array_keys(self::$separatorArrays))) {
			return self::$separatorArrays[$lang];
		} else {
			return self::$separatorArrays["en"];
		}
	}

	public static function encodeExceptionWord($text){
		for($i=0;$i<count(self::$replace);$i++){
			$flatArray = __flatten(self::$exceptionWords[self::$replace[$i]['name']]);
			if(count($flatArray) ==0) {
				continue;
			}
			$flatArray = array_map('strtolower', $flatArray);
			$head = 0;
			$tail = 0;
			preg_match_all('/(\s+)|(<[^>]*>)/',$text,$matches,PREG_OFFSET_CAPTURE);
			$matches = $matches[0];
			if(count($matches) == 0){
				continue;
			}
			$headTailLists = array();
			for($j=0;$j<=count($matches);$j++){
				if($j == 0) {
					$headTailLists[] = array('head' => 0, 'tail' => $matches[$j][1]);
				} else if($j == count($matches)) {
					$headTailLists[] = array('head' => $matches[$j-1][1] + strlen($matches[$j-1][0]), 'tail' => strlen($text));
				} else {
					$headTailLists[] = array('head' => $matches[$j-1][1] + strlen($matches[$j-1][0]), 'tail' => $matches[$j][1]);
				}
			}
			$headTailLists = array_reverse($headTailLists);
			foreach($headTailLists as $list){
				if(array_search(strtolower(substr($text,$list['head'],$list['tail']-$list['head'])),$flatArray) !== FALSE){
					$substr = substr($text,$list['head'],$list['tail']-$list['head']);
					$textHead = substr($text,0,$list['head']);
					$textTail = substr($text,$list['tail']);
					$substr = str_replace(self::$replace[$i]['symbol'],self::$replace[$i]['rule'],$substr);
					$text = $textHead.$substr.$textTail;
				}
			}
		}
		return $text;
	}

	public static function encodeUrl($text){
		return self::enc($text, "/s?https?:\/\/[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,%#]+/");
	}

	public static function encodeDecimal($text){
		return self::enc($text, "/[0-9]*\.[0-9]+/");
	}

	protected static function enc($text, $pattern){
		while(preg_match($pattern, $text, $matches, null, $index = 0)){
			$index = strpos($text,$matches[0],$index);
			$textHead = substr($text,0,$index);
			$textTail = substr($text,$index+strlen($matches[0]));
			$substr = $matches[0];
			for($i=0;$i<count(self::$replace);$i++) {
				$substr = preg_replace('/\\'.self::$replace[$i]['symbol'].'/',self::$replace[$i]['rule'],$substr);
			}
			$text = $textHead.$substr.$textTail;
			$index += strlen($substr);
		}
		return $text;
	}

	public static function dec($text){
		foreach(self::$replace as $elem) {
			$text = str_replace($elem['rule'],$elem['symbol'],$text);
		}
		return $text;
	}

	public static function encodeInvalidSeparatorWithLanguage($text, $language){
		$text = self::enc($text, "/[.!?][-_~*.!?()a-zA-Z0-9;\/:@&=+$,%#]*[-_~*()a-zA-Z0-9;\/:@&=+$,%#]/i");
		if(in_array($language, array('ja','ko','zh'))){
			foreach(self::$replace as $elem){
				while(mb_ereg("\\".$elem['symbol']."」",$text,$regs)){
					foreach($regs as $reg){
						$index = strpos($text,$reg);
						$text = substr($text,0,$index).str_replace($elem['symbol'],$elem['rule'],$reg).substr($text,$index+strlen($reg));
					}
				}
			}
		}
		if(in_array($language, array('en','de','es','pt','fr','it',"ko"))){
			while(preg_match('/[.!?]"/', $text, $matches  , PREG_OFFSET_CAPTURE)){
				foreach(self::$replace as $elem){
					$reg = $matches[0][0];
					$index = $matches[0][1];
					if($reg == $elem['symbol'].'"'){
						$text = substr($text,0,$index).str_replace($elem['symbol'],$elem['rule'],$reg).substr($text,$index+strlen($reg));
						break;
					}
				}
			}
		}
		while(preg_match('/([.!?>]|^)\s+(\d+\.?)+\./', $text, $matches  , PREG_OFFSET_CAPTURE)){
			$reg = $matches[0][0];
			$index = $matches[0][1];
			$text = substr($text,0,$index).substr($reg,0,strlen($reg)-1).'[[#dot]]'.substr($text,$index+strlen($reg));
		}
		return $text;
	}
	
	public static function updateSeparator($key, $values) {
	    self::$separatorArrays[$key] = $values;
	}
}

function __flatten($array){
	$retArray = array();
	for($i=0;$i<count($array);$i++) {
		if(is_array($array[$i])) {
			$retArray = array_merge($retArray, $array[$i]);
		} else {
			array_push($retArray, $array[$i]);
		}
	}
	return $retArray;
}
?>