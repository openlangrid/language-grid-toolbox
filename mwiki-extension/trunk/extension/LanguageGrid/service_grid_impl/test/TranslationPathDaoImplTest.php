<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Extension. This provides MediaWiki
// extensions with access to the Language Grid.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
ini_set('display_errors', 'yes');
error_reporting(E_ALL);

$adapter = DaoAdapter::getAdapter();
//$defaultDictionaryBindDaoImpl = $adapter->getDefaultDictionaryBindDao();
//$defaultDictionarySettingDaoImpl = $adapter->getDefaultDictionarySettingDao();
//$langridServicesDaoImpl = $adapter->getLangridServicesDao();
//$translationBindDaoImpl = $adapter->getTranslationBindDao();
//$translationExecDaoImpl = $adapter->getTranslationExecDao();
$translationPathDaoImpl = $adapter->getTranslationPathDao();
//$translationSetDaoImpl = $adapter->getTranslationSetDao();

$html[] = '<h2>TranslationPathDaoImplTest</h2>';

$html[] = '<h3>queryAll()</h3>';
$html[] = echoPre($translationPathDaoImpl->queryAll());

$html[] = '<h3>queryBySetId($userId = 0, $setId = 1, $sourceLang = null, $targetLang = null)</h3>';
$html[] = echoPre($translationPathDaoImpl->queryBySetId(0, 1));

$html[] = '<h3>queryBySetId($userId = 0, $setId = 1, $sourceLang = en, $targetLang = ja)</h3>';
$translationPathObj = $translationPathDaoImpl->queryBySetId(0, 1, 'en', 'ja');
$html[] = echoPre($translationPathObj);

$html[] = '<h3>getTranslationExecs($translationPathObj = [@en,ja])</h3>';
$html[] = echoPre($translationPathDaoImpl->getTranslationExecs($translationPathObj[0]));

?>