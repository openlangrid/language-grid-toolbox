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
require('../../../mainfile.php');
require(dirname(__FILE__).'/../classes/action/SearchAction.php');

$action =& new SearchAction();
$action->execute();
$action->outputResponse();


function mylog($text) {
	$file = __FILE__.'.log';
	$msg = mtime()." ".$text.PHP_EOL;
	$fno = fopen($file, 'a');
	fwrite($fno, $msg);
	fclose($fno);
}
function log_error($val) {
	error_log(print_r($val, 1), 3, __FILE__.'.log');
}
function mtime() {
	list($micro, $Unixtime) = explode(" ", microtime());
	$sec = $micro + date("s", $Unixtime); // 秒"s"とマイクロ秒を足す
	$sec = sprintf('%02.3f', $sec);
	return date("Y/m/d H:i:", $Unixtime).$sec;
}

?>
