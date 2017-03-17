<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

//require_once 'HTTP/Download.php';

$filename = !empty($_POST['createEditDownloadTemplateFileName']) ? $_POST['createEditDownloadTemplateFileName'] : "template.txt";
$filename = mb_convert_encoding($filename,"SJIS-win", "UTF-8");
$template = $_POST['template'];
$download_template = null;
$template_count = 1;

if( empty($template) ){
	return;
}
foreach($template as $val){
	$download_template .= "<PAIR ID=\"".$template_count."\">\n";
	$download_template .= "<SOURCE_TEXT><![CDATA[".$val['source']."]]></SOURCE_TEXT>\n";
	$download_template .= "<TARGET_TEXT><![CDATA[".$val['target']."]]></TARGET_TEXT>\n";
	$download_template .= "</PAIR>\n";
	$template_count++;
}

ini_set('zlib.output_compression', 'Off');
header('HTTP/1.1 200 OK');
header('Status: 200 OK');
header("Cache-Control: private, must-revalidate");
header('Accept-Ranges: '.strlen($download_template).'bytes');
header("Content-Disposition: attachment; filename=\"".($filename)."\"");
header("Content-Length: ".strlen($download_template));
header("Content-Type: application/octet-stream");
//header("Content-Type: text/plain");

echo $download_template;

/*
$httpDownload = new HTTP_Download();
$httpDownload->setData($download_template);
$httpDownload->setContentType('text/plain');
$httpDownload->setContentDisposition(HTTP_DOWNLOAD_ATTACHMENT,$filename);
$httpDownload->send();
*/
?>
