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

require_once dirname(__FILE__).'/../../lib/simple_html_dom.php';

class HtmlToken {
	public $type = '';
	public $token = '';
}

interface HtmlTokenizer {

	/**
	 * <#if local="ja">
	 * コンストラクタ
	 * </#if>
	 *
	 * @param unknown_type $html
	 */
	public function __construct($html);

	/**
	 * <#if local="ja">
	 * トークンの合計を返す
	 * </#if>
	 *
	 * @return int
	 */
	public function countTokens();

	/**
	 * <#if local="ja">
	 * 次のトークンがあるかないか
	 * </#if>
	 *
	 * @return bool
	 */
	public function hasMoreTokens();

	/**
	 * <#if local="ja">
	 * 次のトークンを得る
	 * </#if>
	 *
	 * @return String
	 */
	public function nextToken();
}

class SimpleHtmlTokenizer implements HtmlTokenizer {

	protected $pointer = 0;
	protected $tokens;

	/**
	 *
	 * @param $html
	 */
	public function __construct($html) {
		$this->tokenize($html);
	}

	/**
	 *
	 */
	public function getTokens() {
		return $this->tokens;
	}

	/**
	 *
	 */
	public function countTokens() {
		return count($this->tokens);
	}

	/**
	 *
	 */
	public function hasMoreTokens() {
		return isset($this->tokens[$this->pointer]);
	}

	/**
	 *
	 */
	public function nextToken() {
		return $this->tokens[$this->pointer++];
	}

	/**
	 *
	 * @param unknown_type $html
	 */
	protected function tokenize($html) {

		$this->tokens = array();
		$token = new HtmlToken();
		$stateChanged = false;
		$state = 0;

		if (substr($html, 0, 1) == '<') {
			$state = 1;
		}

		$exps = array('SCRIPT', 'STYLE');

		for ($i = 0, $length = strlen($html); $i < $length; $i++) {

			$char = substr($html, $i, 1);
			$nextChar = substr($html, $i + 1, 1);

			$token->token .= $char;

			switch ($state) {
			case 0:

				/* タグの外 */

				if ($nextChar == '<') {
					$state = 1;
					$token->type = 'text';
					$stateChanged = true;
				}

				break;

			case 1:

				/* タグ自身の中 */

				if ($char == '>') {

					foreach ($exps as $exp) {
						if (strtoupper(substr($token->token, 0, strlen($exp) + 1)) == '<'.$exp) {
							$state = 2;
							break;
						}
					}

					if ($state == 1) {
						$state = ($nextChar == '<') ? 1 : 0;
					}

					$token->type = 'tag';
					$stateChanged = true;
				}
				break;

			case 2:

				/* script,cssタグの間 */

				foreach ($exps as $exp) {
					if (strtoupper(substr($html, $i, strlen($exp) + 2)) == ('</'.$exp)) {
						$state = 1;
//						$token->type = 'tag';
//						$stateChanged = true;
						break;
					}
				}
				break;
			}

			if ($stateChanged || ($i == ($length - 1))) {

				$token->token = preg_replace('/(\t|\n|\r)/', '', $token->token);

				if (!preg_match('/^(\t|\n|\r| |　)*$/u', $token->token)) {
					$this->tokens[] = $token;
				}
				$token = new HtmlToken();
				$stateChanged = false;
			}
		}
	}
}

class StandardsHtmlTokenizer extends SimpleHtmlTokenizer {

	/**
	 *
	 * @param unknown_type $html
	 */
	protected function tokenize($html) {
		$this->tokens = array();
		$html = str_get_html($html);
	
		foreach ($html->childNodes() as $node) {
			$this->parse($node);
		}
	}
	
	private function parse($parent) {
		if ($parent->tag == 'comment') {
			return;
		}
		
		$token = new HtmlToken();
		if ($parent->tag != 'text') {
			$tags = $this->getOpenCloseTag($parent);
			$open = $tags['open'];
			$close = $tags['close'];
			
			$token->type = 'tag';
			$token->token = $open;
		} else {
			$token->type = 'text';
			$token->token = $parent->text();
		}
		$this->addToken($token);
				
		foreach ($parent->nodes as $node) {
			if (!empty($parent->children)) {
				$this->parse($node);
			} else {
				$token = new HtmlToken();
				if ($parent->tag == 'script' || $parent->tag == 'style') {
					$token->type = 'tag';
				} else {
					$token->type = 'text';
				}
				$token->token = $node->text();
				$this->addToken($token);
			}
		}
		
		if ($parent->tag != 'text' && $close) {
			$token = new HtmlToken();
			$token->type = 'tag';
			$token->token = $close;
			$this->addToken($token);
		}
	}
	
	/**
	 * begine/end tag
	 * @param unknown_type $parent
	 */
	private function getOpenCloseTag($parent) {
		if (($parent->innertext() != '') && ($parent->innertext() == $parent->outertext()) && $parent->tag == 'unknown') {
			$open = $parent->innertext();
			$close = null;
		} else if ($parent->innertext() != '') {
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
			$isMatch = preg_match('/^(<.*>)(<\/.*>)$/', $parent->outertext(), $matches);
			if ($isMatch) {
				$open = $matches[1];
				$close = $matches[2];
			} else {
				$open = $parent->outertext();
				$close = null;
			}
		}
		
		return array('open' => $open, 'close' => $close);
	}
	
	private function addToken($token) {
		$token->token = preg_replace('/(\t|\n|\r)/', '', $token->token);

		if (!preg_match('/^( |　)*$/u', $token->token)) {
			$this->tokens[] = $token;
		} else {
			$token->type = 'tag';
			$this->tokens[] = $token;
		}
	}
}
?>
