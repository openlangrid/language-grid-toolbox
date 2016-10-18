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

// HTMLタグパース用
require_once dirname(__FILE__).'/../lib/simple_html_dom.php';

//$html = file_get_html("http://langrid.nict.go.jp/en/index.html");

$str = '
<!--コメントだよ-->
<html>
<!--コメントだよ-->
	<body class="aaa" id="bbb" alt=">">
		<div>
			<span>Aaa</span>
		</div>
		<div>
			<ul>
				<li>Li<span>s</span>t1</li>
				<li><span></span>List2</li>
			</ul>
		</div>
	</body>
</html>
';

$str2 = '
<html>AAA</html>
';

$str = preg_replace('/(\n|\t)/',	'', $str);
$html = str_get_html($str);
foreach ($html->childNodes() as $node) {
	parse($node);
}
//parse3($html);
function parse3($parent) {
	$stack = array();
	if (!empty($parent->nodes)) {
		foreach ($parent->nodes as $node) {
			parse3($node);
		}
	} else {
		if ($parent->tag == 'text') {
			var_dump('text: '.$parent->text());			
		} else {
			var_dump($parent->tag);
		}
	}
}
function parse2($parent) {
	if ($parent->tag == 'comment') {
		return;
	}
	
	if ($parent->tag != 'text') {
		$tags = getOpenCloseTag($parent);
		var_dump($tags['open']);
	} else {
		var_dump('text: '.$parent->text());
	}
	
	$childNodes = $parent->childNodes();
	foreach ($childNodes as $node) {
		var_dump($node->tag);
//		if (!empty($parent->children)) {
			parse2($node);
//		} else {
//			var_dump('tag: '.$parent->tag.'text: '.$parent->text());
//		}
	}
	
	if ($parent->tag != 'text') {
		var_dump($tags['close']);
	}
}
die;

function getOpenCloseTag($parent) {
	if ($parent->innertext() != '') {
		$matches = explode($parent->innertext(), $parent->outertext());
		$open = '';
		$close = '';
		for ($i = 0, $count = count($matches); $i < $count; $i++) {
			if ($i < $count / 2) {
				$open .= $matches[$i].$parent->innertext();
			} else {
				$close .= $matches[$i].$parent->innertext();
			}
		}
		$open = substr($open, 0, -strlen($parent->innertext()));
		$close = substr($close, 0, -strlen($parent->innertext()));
	} else {
		$html = preg_match('/^(<.*>)(<\/.*>)$/', $parent->outertext(), $matches);
		$open = $matches[1];
		$close = $matches[2];
	}
	
	return array('open' => $open, 'close' => $close);
}

// これだと最初にコメント来たとき対応できない
function parse($parent) {
	if ($parent->tag == 'comment') {
		return;
	}
	
	if ($parent->tag != 'text') {
		$tags = getOpenCloseTag($parent);
		$open = $tags['open'];
		$close = $tags['close'];
		var_dump($open);
	} else {
		var_dump('text: '.$parent->text());
	}
	
	if (!empty($parent->nodes)) {
		foreach ($parent->nodes as $node) {
			if (!empty($parent->children)) {
				parse($node);
			} else {
				var_dump('text:'.$node->text());
			}
		}
	}
	
	if ($parent->tag != 'text') {
		var_dump($close);
	}
}
die;
foreach ($html->nodes as $node) {
	if (empty($node->children)) {
		if ($node->tag == 'text') {
			var_dump($node->text());			 
		} else {
			var_dump($node->tag);
		}
	}
}
die;
//
require_once dirname(__FILE__).'/../class/html/HtmlTokenizer.class.php';

$html = file_get_contents("http://langrid.nict.go.jp/en/index.html");

$tokenizer = new SimpleHtmlTokenizer($html);
$tokens = $tokenizer->getTokens();

echo "<pre>";
$html = print_r($tokens, 1);
$html = htmlspecialchars($html, ENT_QUOTES);
print_r($html);
echo "</pre>";

?>