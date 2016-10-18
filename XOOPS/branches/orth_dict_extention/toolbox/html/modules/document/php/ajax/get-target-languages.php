<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
require_once('../../../../mainfile.php');
require_once('../TranslatorSettings.php');
require_once('../Validator.php');
try{
	if(!isset($_POST['sourceLang'])){
		throw new Exception("Parameter 'Source Language Code' is required.");
	}
	$dLang = $_POST['sourceLang'];
	if(get_magic_quotes_gpc()) {
		$dLang = stripslashes($dLang);
	}
	$lang = str_replace("&amp;", "&", htmlspecialchars($dLang, ENT_QUOTES, "UTF-8"));
	$validater = new Validator();
	if(!$validater->validateSupportedSourceLanguage($dLang)){
		throw new Exception("Parameter 'Soruce Language Code' is not supported.");
	}

	$settings = new TranslatorSettings();
	$languages = $settings->getTargetLanguageTags($lang);
	$response = array();
	$response['targetLangTags'] = $languages;

	echo json_encode(array($response));
}catch(Exception $e){
	$languages = array();
	$lanugages[] = "<option value='unknown'>unknown</option>";
	$response = array();
	$response['targetLangTags'] = $languages;
	$response['message'] = $e->getMessage();

	echo json_encode(array($response));
}
?>