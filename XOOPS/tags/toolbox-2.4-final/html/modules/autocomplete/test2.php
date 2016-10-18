<?php
require_once(dirname(__FILE__).'/header.php');
require_once(dirname(__FILE__).'/class/AutoComplete.php');

$keyword = 'テスト';

$c = new AutoComplete();
$res = $c->initSearch("ja", $keyword);

//print_r($c->getTrieTree());

print_r($c->find($keyword))

//print_r($res);
//var_dump($c);
?>