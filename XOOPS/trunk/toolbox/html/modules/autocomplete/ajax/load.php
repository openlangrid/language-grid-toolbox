<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/*
 * $Id: load.php 3616 2010-04-09 09:32:59Z yoshimura $
 */

require_once(XOOPS_ROOT_PATH.'/api/class/client/ResourceClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/ParallelTextClient.class.php');
require_once(XOOPS_ROOT_PATH.'/api/class/client/TranslationTemplateClient.class.php');
require XOOPS_ROOT_PATH.'/modules/langrid/include/Languages.php';

$keyword = $_POST['keyword'];
$index = $_POST['index'];
$data = array();
$tmp = explode('&', $index);
foreach ($tmp as $t) {
	$a = explode('=', $t);
	$data[$a[0]] = $a[1];
}

$client = new ResourceClient();
$resource = $client->getLanguageResource($data['name']);
if ($resource['status'] != 'OK') {
	die();
}
$vo = null;
switch ($resource['contents']->type) {
	case 'PARALLELTEXT':
		$client = new ParallelTextClient();
		$res = $client->searchRecord($data['name'], $keyword, $data['lang'], "PREFIX");
		if ($res['status'] == 'OK') {
			$vo = $res['contents'][0];
		}
		break;
	case 'TRANSLATION_TEMPLATE':
		$client = new TranslationTemplateClient();
		$res = $client->searchRecord($data['name'], $keyword, $data['lang'], "prefix");
		if ($res['status'] == 'OK') {
			$vo = $res['contents'][0];
		}
		break;
	default:
		return false;
		break;
}

if ($vo == null) {
	die();
}

$langs = array();
foreach ($vo->expressions as $exp) {
	$cd = $exp->language;
	$nm = $LANGRID_LANGUAGE_ARRAY[$cd];
	$langs[$cd] = $nm;
}

header('Content-Type: application/json; charset=utf-8;');
echo json_encode(array('vo' => $vo, 'languages' => $langs));

die();

//
//$c = new AutoComplete();
//$c->initSearch($language, $keyword);
//$dist = $c->find($keyword);
//if ($dist === false) {
//	die();
//}
//
//header('Content-Type: application/json; charset=utf-8;');
//echo json_encode($dist);
?>
