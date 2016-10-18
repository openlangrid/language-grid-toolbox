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
require_once(dirname(__FILE__) . '/../defines.php');
require_once(dirname(__FILE__) . '/../exception/InvalidParameterException.php');

require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__) . '/../database/TemplateParallelTextDAO.php');

$dId = $_GET['serviceId'];
$id = str_replace('_', ' ', $dId);
try{
	$dao = new TemplateParallelTextDAO($id);
	if( ! $dao->isDeploy()) {
		die();
	}
} catch(Exception $e){
	die();
}
try{
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
		require_once('SOAP/Server.php');
		require_once(dirname(__FILE__) . '/../service/TemplateParallelTextService.php');
		header("Content-type: text/xml; charset=UTF-8");
		$server = new SOAP_Server();
		$server->addObjectMap(
			new TemplateParallelTextService($id)
			, "http://templateparalleltext.ws_1_2.wrapper.langrid.nict.go.jp"
		);

		$server->service($HTTP_RAW_POST_DATA);
	}
}catch(InvalidParameterException $e){
	header("Content-Type: text/xml; charset=UTF-8");
	echo $e->getSoapMessage();
}catch(Exception $e){
	echo $e;
}
?>