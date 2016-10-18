<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
// Copyright (C) 2010  NICT Language Grid Project
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
/**
 * $Id: text-resource-template-download.php 3879 2010-08-02 10:15:16Z yoshimura $
 */
//require_once(XOOPS_ROOT_PATH.'/api/class/client/FileSharingClient.class.php');
//error_reporting(0);
require_once(dirname(__FILE__).'/../class/fileio/TextResourceConverter.class.php');

try {
	$mid = @$_GET['mid'];

	$converter = new TextResourceConverter();
	$binfile = $converter->createTemplate($mid);

	if ($binfile) {
		ob_clean();

		$filename = 'textresource.txt';
		$fsize = strlen($binfile);

		mb_http_output('pass');

		if(ereg(' MSIE ', $_SERVER['HTTP_USER_AGENT'])) {
			$encoding = 'sjis-win';
			$outname = mb_convert_encoding($filename,$encoding,"UTF-8");
		}else{
			$outname = $filename;
		}

		header("HTTP/1.1 200 OK");
		header("Status: 200 OK");
		header("Expires: Thu, 01 Dec 1994 16:00:00 GMT");
		header("Last-Modified: ". gmdate("D, d M Y H:i:s"). " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Accept-Ranges: ".strlen($fsize)."bytes");
		header("Content-Disposition: attachment; filename=\"".($outname)."\"");
		header("Content-Length: ".$fsize);
		header("Content-Type: application/octet-stream");

		print $binfile;
		flush();
		ob_flush();
	} else {
//		header("HTTP/1.0 404 Not Found");
		throw new Exception(_MI_UIC_TR_TEMPLATE_FILE_NOT_FOUND);
	}
} catch (Exception $e) {
	header("HTTP/1.0 404 Not Found");
	echo $e->getMessage();
}
exit();
?>