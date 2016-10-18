<?php
class AutoCompleteTrieTree {

	public $root;

	function __construct() {
		$this->root = new AutoCompleteTrieNode();
	}

	function addString($str, $index, $vo) {
		$this->root->addString($str, $index, $vo);
	}

	function findString($str) {
		return $this->root->findString($str);
	}

	function findNode($str) {
		return $this->root->findNode($str);
	}

	function toString() {
		$this->root->toString();
	}
}

class AutoCompleteTrieNode {
	public $index;
	public $vo;
	public $keyString;
	public $bound;
	public $boundUniKey;
	//associative array pointing to next TrieNode
	public $children = array();

	function AutoCompleteTrieNode($keyString = null, $index = null) {
		$this->keyString = $keyString;
		$this->bound = (preg_match("/(\[[^\[\]]+?\])/u", $keyString) === 1);
		if ($this->bound) {
			$this->boundUniKey = preg_replace("/\[(\d)\]/u", "[$index&boundId=$1]", $keyString);
		}
	}

	function findString($str) {
		$_str = $this->mbStringToArray($str);
		if (mb_strlen($str) == 0) { //we have reached the last node!
			return $this->index;
		} else {
			if (!isset($this->children[$_str[0]])) {
				return FALSE; //didn't find it!
			} else {
				$a = $this->children[$_str[0]]->findString($this->mbArrayToString($_str,1));
				return $a;
			}
		}
	}

	function findNode($str) {
		$_str = $this->mbStringToArray($str);
		if (mb_strlen($str) == 0) {
			return $this;
		} else {
			if (!isset($this->children[$_str[0]])) {
				return false;
			} else {
				return $this->children[$_str[0]]->findNode($this->mbArrayToString($_str,1));
			}
		}
	}

	function addString($str, $index, $vo) {
		$_str = $this->mbStringToArray($str);
		if (mb_strlen($str) > 0) {
			if (!isset($this->children[$_str[0]])) { //node doesn't exists yet, make it
				$this->children[$_str[0]] = new AutoCompleteTrieNode($_str[0], $index);
			}
			$this->children[$_str[0]]->addString($this->mbArrayToString($_str,1), $index, $vo);
		} else {
			$this->index = $index;
			$this->vo = $vo;
		}
	}

	function hasChildren() {
		return count($this->children) > 0;
	}

	function countChildren() {
		return count($this->children);
	}

	function getChildren() {
		return $this->children;
	}

	function isBound() {
		return $this->bound;
	}

	function getBoundUniKey() {
		return $this->boundUniKey;
	}

	function toString() {
		echo "Node<";
		print_r($this->children);
		echo "> Node";
		echo "Index ". $index ."\n";
		foreach($this->children as $child) {
			$child->toString();
		}
	}

	function mbStringToArray ($sStr, $sEnc='UTF-8') {
		$aRes = array();

		preg_match_all('/(\[[^\[\]]+?\])|./u', $sStr, $aRes);
		return $aRes[0];

//		$aRes = array();
//		while ($iLen = mb_strlen($sStr, $sEnc)) {
//			array_push($aRes, mb_substr($sStr, 0, 1, $sEnc));
//			$sStr = mb_substr($sStr, 1, $iLen, $sEnc);
//		}
//		return $aRes;
	}

	function mbArrayToString ($strarray, $offset = 0) {
		$aRes = '';
		for ($i = $offset; $i < count($strarray); $i++) {
			$aRes .= $strarray[$i];
		}
		return $aRes;
	}
}
?>
