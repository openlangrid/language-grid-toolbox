<?php
//  ------------------------------------------------------------------------ //
// This is a program for Language Grid Toolbox. This provides a series of
// multilingual collaboration tools.
// Copyright (C) 2010 CITY OF KYOTO All Rights Reserved.
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

error_reporting(E_ALL);
require(dirname(__FILE__).'/../../mainfile.php');
header('Content-Type: text/html; charset=utf-8;');
require_once(dirname(__FILE__).'/../db/adapter/DaoAdapter.class.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridTranslationExec.class.php');
require_once(XOOPS_ROOT_PATH.'/service_grid/db/dto/ServiceGridTranslationPath.class.php');

$adapter = DaoAdapter::getAdapter();
$defaultDictionaryBindDaoImpl = $adapter->getDefaultDictionaryBindDao();
$defaultDictionarySettingDaoImpl = $adapter->getDefaultDictionarySettingDao();
$langridServicesDaoImpl = $adapter->getLangridServicesDao();
$translationBindDaoImpl = $adapter->getTranslationBindDao();
$translationExecDaoImpl = $adapter->getTranslationExecDao();
$translationPathDaoImpl = $adapter->getTranslationPathDao();
$translationSetDaoImpl = $adapter->getTranslationSetDao();

echo '<pre>';
print_r($langridServicesDaoImpl->queryFindByServiceTypes('TRANSLATION'));
echo '</pre>';
echo '<pre>';
print_r($langridServicesDaoImpl->queryFindByServiceTypes(array('TRANSLATION')));
echo '</pre>';
echo '<pre>';
print_r($langridServicesDaoImpl->queryFindByServiceTypes(array('TRANSLATION', 'IMPORTED_TRANSLATION')));
echo '</pre>';

die();

echo '<h2>DAO実装テスト</h2>';
echo '<pre>';
echo 'translationSet=>';
$translationSet = $translationSetDaoImpl->queryBySetName('BBS', '1');
print_r($translationSet);
echo '</pre>';
echo '<pre>';
echo 'translationPath=>';
$translationPaths = $translationPathDaoImpl->queryBySetId('1', $translationSet->getSetId());
print_r($translationPaths);
echo '</pre>';
foreach ($translationPaths as $translationPath) {
	$translationExecs = $translationExecDaoImpl->queryByPathId($translationPath->getPathId());
	echo '<pre>';
	echo 'translationExec=>';
	print_r($translationExecs);
	echo '</pre>';
	foreach ($translationExecs as $translationExec) {
		echo '<pre><font color="red">';
		echo 'translationBind=>';
		print_r($translationBindDaoImpl->queryByExecObject($translationExec));
		echo '</font></pre>';
	}
}
echo '<pre>';
echo 'defaultDictionaryBind=>';
print_r($defaultDictionaryBindDaoImpl->queryAll());
echo '</pre>';
echo '<pre>';
echo 'defaultDictionarySetting=>';
print_r($defaultDictionarySettingDaoImpl->queryAll());
echo '</pre>';
echo '<pre>';
echo 'langridServices=>';
print_r($langridServicesDaoImpl->queryAll());
echo '</pre>';
?>