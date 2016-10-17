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
/**
 * <#if locale="en">
 * Entry point
 * <#elseif locale="ja">
 * エントリポイント
 * </#if>
 */
if (!defined('MEDIAWIKI')) {
echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
EOT;
exit( 1 );
}
$myextensionpath = dirname(__FILE__);
define('MYEXTPATH', $myextensionpath);

$wgExtensionCredits['other'][] = array(
	'name' => 'LanguageGrid',
	'author' => 'Language Grid Project, NICT.',
	'url' => 'http://langrid-tool.nict.go.jp/',
	'description' => 'This extension allows other MediaWiki extensions to access language services on the Language Grid.',
	'descriptionmsg' => '',
	'version' => '0.00',
);

$wgExtensionFunctions[] = 'wgLanguageGridSetupNamespaces';

function wgLanguageGridSetupNamespaces() {
	global $wgExtraNamespaces;

	$namespaceId = 100;
	if (count($wgExtraNamespaces) > 0) {
		while(true) {
			if (!array_key_exists( $namespaceId, $wgExtraNamespaces ) ) {
				break;
			}
			$namespaceId += 2;
		}
	}

	define( 'NS_LG_RESOURCES', $namespaceId);
	define( 'NS_LG_RESOURCES_TALK', $namespaceId + 1);
	
	$wgExtraNamespaces[NS_LG_RESOURCES] = 'Resource';
	$wgExtraNamespaces[NS_LG_RESOURCES_TALK] = 'Resource_talk';
}

/*
 * <#if locale="en">
 * Load PHP-Class
 * <#elseif locale="ja">
 * PHP-Classロード
 * </#if>
 */
$wgAutoloadClasses['LanguageGridTabInstaller'] = $myextensionpath.'/LanguageGrid.hooks.php';
$wgAutoloadClasses['LanguageGridAjaxController'] = $myextensionpath.'/LanguageGrid.ajax.php';

/*
 * <#if locale="en">
 * Load PHP-Class(API related)
 * <#elseif locale="ja">
 * PHP-Classロード(API関連)
 * </#if>
 */
$wgAutoloadClasses['TranslationPathDbHandler'] = $myextensionpath.'/service_grid/db/handler/TranslationPathDbHandler.class.php';
$wgAutoloadClasses['LangridServicesDbHandler'] = $myextensionpath.'/service_grid/db/handler/LangridServicesDbHandler.class.php';


// for service_grid(_impl)
$wgAutoloadClasses['DaoAdapter'] = $myextensionpath.'/service_grid/db/adapter/DaoAdapter.class.php';
$wgAutoloadClasses['ServiceGridDefaultDictionaryBind'] = $myextensionpath.'/service_grid/db/dto/ServiceGridDefaultDictionaryBind.class.php';

// Message resource load
$wgExtensionMessagesFiles['LanguageGrid'] = $myextensionpath.'/LanguageGrid.i18n.php';


/*
 * <#if locale="en">
 * Register Hook
 * Insert page tab
 * <#elseif locale="ja">
 * Hookを登録
 * ページタブを差し込む
 * </#if>
 */
$wgHooks['SkinTemplateTabs'][] = array( new LanguageGridTabInstaller(), 'insertTabs' );
/*
 * <#if locale="en">
 * Register unique Action
 * <#elseif locale="ja">
 * 独自のActionを登録
 * </#if>
 */
$wgHooks['CustomEditor'][] = array( new LanguageGridTabInstaller(), 'hookAction');

#Ajax
$wgAjaxExportList[] = 'LanguageGridAjaxController::invoke';
/*
 * <#if locale="en">
 * <#elseif locale="ja">
 * 記事表示時に翻訳設定ファイルチェック
 * </#if>
 */
$wgHooks['ArticlePageDataAfter'][] = array(new ServiceGridHooks(), 'checkSettingFile');


?>
