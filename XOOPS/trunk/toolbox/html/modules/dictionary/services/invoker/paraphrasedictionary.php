<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// dictionaries and parallel texts.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
require_once(dirname(__FILE__) . '/../exception/ProcessFailedException.php');
require_once(dirname(__FILE__) . '/../exception/UnsupportedLanguagePairException.php');

require_once(dirname(__FILE__).'/../../../../mainfile.php');
require_once(dirname(__FILE__).'/../../php/lib/user-dictionary-controller.php');

//require_once(XOOPS_ROOT_PATH.'/modules/langrid_config/common.php');

$dId = $_GET['serviceId'];
$id = str_replace('_', ' ', $dId);
$udc = new UserDictionaryController();
if (!$udc->isDeploy($id)) {
	die();
}

//debugLog(__FILE__.', start id='.$id);

try{
	if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
		require_once('SOAP/Server.php');
		require_once(dirname(__FILE__) . '/../service/ParaphraseDictionaryService.php');
		header("Content-type: text/xml; charset=UTF-8");
		$server = new SOAP_Server();
		$server->addObjectMap(
			new ParaphraseDictionaryService($id, 5)
			, "http://paraphrasedictionary.ws_1_2.wrapper.langrid.nict.go.jp"
		);
		$server->service(file_get_contents("php://input"));
	}
}catch(InvalidParameterException $e){
	header("Content-Type: text/xml; charset=UTF-8");
	echo $e->getSoapMessage();
}catch(UnsupportedLanguagePairException $e){
	header("Content-Type: text/xml; charset=UTF-8");
	echo $e->getSoapMessage();
}catch(ProcessFailedException $e){
	header("Content-Type: text/xml; charset=UTF-8");
	echo $e->getSoapMessage();
}catch(Exception $e){
	echo $e;
}
//debugLog(__FILE__.', end id='.$id);
?>