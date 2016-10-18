<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to search
// Q&As stored in Toolbox and post a question through a Web page.
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
$file = dirname(__FILE__).'/webqa_test.log';
mylog($file, "Start.");
require_once('../../mainfile.php');
require_once(XOOPS_ROOT_PATH.'/modules/user/class/users.php');
$root =& XCube_Root::getSingleton();
$userhandler =& new UserUsersHandler($root->mController->mDB);
$user =& $userhandler->get('1');
$root->mContext->mXoopsUser =& $user;
ob_end_clean();
$xoops_module_header = '';
$xoops_module_header .= $xoopsTpl->get_template_vars( "xoops_module_header" );

$mydirname = basename(dirname(__FILE__));
$myurl = XOOPS_URL."/modules/".$mydirname;
$mypath = XOOPS_ROOT_PATH."/modules/".$mydirname;

//require_once(XOOPS_ROOT_PATH.'/header.php');
//$file = dirname(__FILE__).'/access_log_'.date("YmdHis").'.txt';
echo '<h3>実行中にブラウザを閉じても処理が継続されるかのテスト</h3>';
echo '<h3>/html/modules/webqa/webqa_test.log をtailしておくことで処理の継続がテストできます。</h3>';
flush();
//ob_start();

echo "<pre>en2ja:We provide this site to allow anyone to try Toolbox functions. User accounts and data in this site are initialized monthly.</pre>";
require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');
for ($i = 0; $i < 10; $i++) {
	$lgClient =& new LangridAccessClient();
	$dist =& $lgClient->translate("en", "ja", "We provide this site to allow anyone to try Toolbox functions. User accounts and data in this site are initialized monthly.", "bbs");
	$result = $dist['contents'][0];
	mylog($file, $result->result);

	echo '<pre>';
	echo $result->result;
	echo '</pre>';
	flush();
//	ob_end_flush();
//	ob_start();
}


mylog($file, "END.");

echo '<h3>終了です。</h3>';
//foreach($css as $c){
//	$xoops_module_header .= "<link rel='stylesheet' type='text/css' href='".$mypath."css/".$c."' />\n";
//}
//$xoops_module_header .= '<script type="text/javascript" src="'.XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js"></script>'."\n";
//$langpairs = getLanguagePairScript();
//$xoops_module_header .= $langpairs;
//foreach($javaScripts as $script){
//	$xoops_module_header .= '<script charset="utf-8" type="text/javascript" src="'.$mypath.$script.'"></script>'."\n";
//}

//$xoopsTpl->assign(
//	array(
//		'languages' => $languages,
//		'xoops_module_header' => $xoops_module_header,
//		'revision' => $revision,
//		'module_url' => $MyUrl,
//		'howToUse' =>  $MyUrl.'/how-to-use/'._MI_WEBTRANS_HOW_TO_USE_LINK
//	)
//);

//include(XOOPS_ROOT_PATH.'/footer.php');



function mylog($file, $text) {
	$msg = date("H:i:s")." ".$text.PHP_EOL;
	$fno = fopen($file, 'a');
	fwrite($fno, $msg);
	fclose($fno);
}
?>