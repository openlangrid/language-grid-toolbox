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

require_once(XOOPS_ROOT_PATH.'/api/class/client/LangridAccessClient.class.php');

header('Content-Type: text/html; charset=utf-8;');

$sourceLang = $_POST['sourceLanguageCode'];
$targetLang = $_POST['targetLanguageCode'];
$source = $_POST['sourceText'];

$translationClient =& new LangridAccessClient();

$sourceLines = explode("\n",$source);
$targetList = array();
$backTransList = array();

foreach ($sourceLines as $line) {
    $line = unescape_magic_quote($line);
    if (strlen($line) > 0) {
        $result = $translationClient->backTranslate($sourceLang, $targetLang, $line, "SITE");
        if ($result['status'] != 'OK') {
            break;
        }
        $targetList[] = $result['contents'][0]->intermediateResult;
        $backTransList[] = $result['contents'][0]->targetResult; 
    } else {
        $targetList[] = '';
        $backTransList[] = '';
    }
}

$retobj = array(
    'status' => $result['status'],
    'message' => $result['message'],
    'contents' => array(
        'targetText' => array(
            'contents' => implode("\n", $targetList)
            ),
        'backTranslateText' => array(
            'contents' => implode("\n", $backTransList)
            )));

echo json_encode($retobj);
exit;

?>