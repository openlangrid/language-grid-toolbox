<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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
require('../../mainfile.php');
include(XOOPS_ROOT_PATH.'/header.php');
require_once(dirname(__FILE__).'/class/get-supported-language-pair-class.php');

$xoopsOption['template_main'] = 'langrid-test.html';
$GetSupportedLanguagePair =& new GetSupportedLanguagePair();
$langPairs = $GetSupportedLanguagePair->getLanguagePair();
$optTmp = '<option value="%s">%s</option>';
$opt = '';

foreach ($langPairs as $langPair) {
	$val = $langPair[0].'2'.$langPair[1];
	$opt .= sprintf($optTmp, $val, $val);
}

$xoopsTpl->assign(array('LangOpt' => $opt));

require_once XOOPS_ROOT_PATH . "/footer.php";
?>