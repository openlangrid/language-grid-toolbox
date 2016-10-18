<?php 
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows users to share
// contents on the multilingual BBS to have a discussion over the contents
// through the BBS.
// Copyright (C) 2010  Graduate School of Informatics, Kyoto University
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

// language setting ----------

// api

$languageManager = LanguageManager::getManager();

$allLanguages = array();
foreach ($languageManager->getAllLanguages() as $languageTag) {
	$allLanguages[$languageTag] = $languageManager->getNameByTag($languageTag);
}

$toLanguages = array();
foreach ($languageManager->getToLanguages() as $languageTag) {
	$toLanguages[$languageTag] = $languageManager->getNameByTag($languageTag);
}

asort($allLanguages, SORT_STRING);
asort($toLanguages, SORT_STRING);

$xoopsTpl->assign('allLanguages',$allLanguages);
$xoopsTpl->assign('toLanguages',$toLanguages);
$xoopsTpl->assign('selectedLanguageTag',$languageManager->getSelectedLanguage());
$xoopsTpl->assign('selectedLanguageName',$languageManager->getNameByTag($languageManager->getSelectedLanguage()));
$xoopsTpl->assign('allLanguagePair',$languageManager->getAllLanguagePair());

if (empty($_SERVER['QUERY_STRING'])) {
	$pagenquery = $_SERVER['PHP_SELF'].'?lang=';
} elseif (isset($_SERVER['QUERY_STRING'])) {
	$query = explode("&",$_SERVER['QUERY_STRING']);
	$langquery = $_SERVER['QUERY_STRING'];
	$langquery = '&'.$langquery;

	// If the last parameter of the QUERY_STRING is sel_lang, delete it so we don't have repeating sel_lang=...
        If (strpos($query[count($query) - 1], 'lang=')  === 0 ) {
            $langquery = str_replace('&' . $query[count($query) - 1], '', $langquery);
        }

	$pagenquery = $_SERVER['PHP_SELF'].'?'.$langquery.'&lang=';
	$pagenquery = str_replace('?&','?',$pagenquery);
}
$xoopsTpl->assign('pagenquery',$pagenquery);

?>