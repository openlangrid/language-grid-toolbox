<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
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
require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__) . '/../database/TemplateParallelTextDAO.php');

// start validate time.
if(!isset($_GET['serviceId'])){
	return;
}

// dirty
$dId = $_GET['serviceId'];
$id = str_replace('_', ' ', $dId);
try{
	$dao = new TemplateParallelTextDAO($id);
	if( ! $dao->isDeploy()) {
		echo "no deployed";
		die();
	}
} catch(Exception $e){
	echo "Invalid Request.";
	die();
}
require_once(dirname(__FILE__) . '/../database/TemplateParallelTextDAO.php');
$templateFile = dirname(__FILE__) . '/templates/TemplateParallelText.xml.template';
try{
	if(!file_exists($templateFile)) {
		throw new Exception('procces failed.');
	}
	$template = file_get_contents($templateFile);
	$targetNamespace = XOOPS_URL
					. '/modules/template/services/templateparalleltext/'
					. urlencode($dId);
	$endpointUrl = XOOPS_URL
					. '/modules/template/services/invoker/templateparalleltext.php?serviceId='
					. urlencode($dId);
	$temp = $template;
	$temp = preg_replace('/\$\{targetNamespace\}/', $targetNamespace, $temp);
	$temp = preg_replace('/\$\{serviceName\}/', urlencode($dId), $temp);
	$wsdl = preg_replace('/\$\{endpointUrl\}/', $endpointUrl, $temp);
	header("Content-Type: text/xml; charset=UTF-8");
	header('Content-Disposition: inline; filename="' . $dId . '"');
	echo $wsdl;
}catch(Exception $e){
	echo $e->getMessage();
}
?>
