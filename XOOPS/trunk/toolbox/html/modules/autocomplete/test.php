<?php
class Trie {
	public $root;
	function __construct() {
		$this->root = new TrieNode();
	}

	function addString($str, $index) {
		$this->root->addString($str, $index);
	}

	function findString($str) {
		return $this->root->findString($str);
	}

	function toString() {
		$this->root->toString();
	}
}

class TrieNode {
	public $index;
	//associative array pointing to next TrieNode
	public $children = array();

	function findString($str) {
		if (strlen($str) == 0) { //we have reached the last node!
			return $this->index;
		} else {
			if (!isset($this->children[$str[0]])) {
				return FALSE; //didn't find it!
			} else {
				$a = $this->children[$str[0]]->findString(substr($str,1));
				return $a;
			}
		}
	}

	function addString($str, $index) {
		if (strlen($str) > 0) {
			if (!isset($this->children[$str[0]])) { //node doesn't exists yet, make it
				$this->children[$str[0]] = new TrieNode();
			}
			$this->children[$str[0]]->addString(substr($str,1), $index);
		} else {
			$this->index = $index;
		}
	}

	function toString() {
		echo "Node<";
//		var_dump($this->children);
		print_r($this->children);
		echo "> Node";
		echo "Index ". $index ."\n";
		foreach($this->children as $child) {
			$child->toString();
		}
	}
}

function buildSuffixTrie($word) {
	$sufftrie = new Trie();
	for ($i = strlen($word) - 1; $i > -1;$i--) {
		$sufftrie->addString(substr($word, $i), $i);
	}
	return $sufftrie;
}


$t = new Trie();
//$t->addString("aap",2);
//$t->addString("aai",3);
//$t->addString("aard",5);
//$a = $t->findString("aa");
//echo $a;
//$t->toString();
$t->addString("abc", 1);
$t->addString("abd", 2);
print_r($t);


?>