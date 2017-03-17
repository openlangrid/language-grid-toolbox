<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to share
// files with other users.
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
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

function format_pager($nav_html){
	$ret = "";
	$ret .= '<div id="file-pager" style="width: #{width}px;">';
	$ret .= '<ul class="clearfix">';
	$count = 0;
	$tmp = explode("> ",$nav_html);
	foreach($tmp as $pg){
		if(trim($pg) != ""){
			$ret .= '<li>';
			if(substr($pg,0,4) == "... "){
				$ret .= "<span>...</span></li>\n<li>";
				$pg = substr($pg,4);
			}
			$head = "";
			$bottom = "";
			if(strpos($pg,'">') > 0){
				list($head,$bottom) = explode('">',$pg);
				if(strpos($bottom,"&laquo;") > 0){
					$ret .= $head."\">&lt;&lt; Previous</a>";
				}elseif(strpos($bottom,"&raquo;") > 0){
					$ret .= $head."\">Next &gt;&gt;</a>";
				}else{
					$ret .= $head."\">".preg_replace('/[^0-9]/','',$bottom)."</a>";
					$count++;
				}
			}else{
				$pg = preg_replace('/[^0-9]/','',$pg);
				$ret .= "<span>".$pg."</span>";
			}

			$ret .= "</li>\n";
		}
	}
	$ret .= "</ul></div>\n";

	$width = $count * 40 + 200;
	$ret = str_replace('#{width}', $width, $ret);

	return $ret;
}
?>
