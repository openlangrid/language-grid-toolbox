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

define('XOOPS_DEFAULT_THEME', XOOPS_THEME_PATH . '/' . APP_THEME_NAME);

function themedb_get_template($tpl_name, &$tpl_source, &$smarty_obj)
{
	$filename = XOOPS_DEFAULT_THEME . '/' . $tpl_name;
	if (! file_exists($filename)) {
		return false;
	}

	$fd = fopen($filename, "r");
	$data = fread($fd, filesize($filename));
	fclose($fd);

    if ($data) {
        $tpl_source = $data;
        return true;
    } else {
        return false;
    }
}

function themedb_get_timestamp($tpl_name, &$tpl_tstamp, &$smarty_obj)
{
	$filename = XOOPS_DEFAULT_THEME . '/' . $tpl_name;
	if (! file_exists($filename)) {
		return false;
	}

	$stat = stat($filename);

    if ($stat) {
        $tpl_tstamp = $stat[9];
        return true;
    } else {
        return false;
    }
}

function themedb_get_secure($tpl_name, &$smarty_obj)
{
    return true;
}

function themedb_get_trusted($tpl_name, &$smarty_obj)
{
}


function register_themedb_resource(&$smarty) {
	$smarty->register_resource("db", array("themedb_get_template",
										   "themedb_get_timestamp",
										   "themedb_get_secure",
										   "themedb_get_trusted"));
}

