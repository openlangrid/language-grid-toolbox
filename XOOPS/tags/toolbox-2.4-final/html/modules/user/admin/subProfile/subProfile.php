<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides user management
// functions.
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
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once "../../../../mainfile.php";
require_once dirname(__FILE__)."/../../subProfile/class/SubProfileManager.class.php";
$man = new SubProfileManager();
$tmpConfig = $man->getConfiguration();
$subCount = 3;
$config = array();
$keys = array("checkedYes", "checkedNo", "titleValue", "lengthValue", "defaultValue");
$keysLen = count($keys);
$yes = "";
$no = "";
for ($i = 1; $i <= $subCount; $i++) {
	$config[$i]["order"] = $i;
	$config[$i]["display"] = str_replace("{0}", $i, _MI_USER_LANG_SUB_DISPLAY);
	$config[$i]["title"] = str_replace("{0}", $i, _MI_USER_LANG_SUB_TITLE);
	$config[$i]["length"] = str_replace("{0}", $i, _MI_USER_LANG_SUB_LENGTH);
	$config[$i]["default"] = str_replace("{0}", $i, _MI_USER_LANG_SUB_DEFAULT);
	if ($tmpConfig["sub".$i."_display"]){
		$yes = "checked";
		$no = "";
	}
	else {
		$yes = "";
		$no = "checked";
	}
	$config[$i]["checkedYes"] = $yes;
	$config[$i]["checkedNo"] = $no;
	$config[$i]["titleValue"] = $tmpConfig["sub".$i."_title"];
	$config[$i]["lengthValue"] = $tmpConfig["sub".$i."_length"];
	$config[$i]["defaultValue"] = $tmpConfig["sub".$i."_default"];

}
$root = &XCube_Root::getSingleton();
$root->mController->executeHeader();
$render = &$root->mContext->mModule->getRenderTarget();
$render->setTemplateName("subProfile.html");
$render->setAttribute("config",$config);
$root->mController->executeView();
?>
