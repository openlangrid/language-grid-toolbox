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
require_once dirname(__FILE__).'/class/SubProfileManager.class.php';
$man = new SubProfileManager();
$userData = $man->getData($_GET["uid"]);
$config = $man->getConfiguration();
$title = $man->getTitle();
$c = count($title);
$length = array();
$subProfileOrder = array();
$data = array();
$dispNum = $man->getDisplayNumber();

for ($i = 0; $i < $c; $i++) {
	$v = $dispNum[$i];
	$data[] = $userData["sub".$v."_value"];
	$length[]=$config["sub".$v."_length"];
	$subProfileOrder[]=$v;
}

$xoopsTpl->assign(array("data"=>$data,"length"=>$length,"subProfileOrder"=>$subProfileOrder));
$xoopsTpl->assign(array("title"=>$title));
?>
