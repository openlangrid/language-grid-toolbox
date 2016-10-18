<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2010  NICT Language Grid Project
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

$js = array(

	// Library
	XOOPS_URL.'/modules/infoMainte/js/InfoManagerClass.js',

	// Common header
	'./js/common.js',

	// Util
	'./js/util/language-util.js',
	'./js/util/logger.js',
	'./js/util/observable.js',
	'./js/util/observer.js',

	// Validator
	'./js/validator/validator.js',
	'./js/validator/url-validator.js',

	// Model
	'./js/model.js',

	// Action
	'./js/action/abstract-action.js',
	'./js/action/add-template-action.js',
	'./js/action/allfixed-action.js',
	'./js/action/change-language-action.js',
	'./js/action/display-action.js',
	'./js/action/display-cache-action.js',
	'./js/action/download-html-action.js',
	'./js/action/contract-action.js',
	'./js/action/expand-action.js',
	'./js/action/load-apply-template-action.js',
	'./js/action/load-html-by-file-action.js',
	'./js/action/load-html-by-url-action.js',
	'./js/action/load-template-action.js',
	'./js/action/load-workspace-action.js',
	'./js/action/save-html-action.js',
	'./js/action/save-template-action.js',
	'./js/action/save-workspace-action.js',
	'./js/action/target-language-change-action.js',
	'./js/action/show-tag-line-action.js',
	'./js/action/toggle-template-action.js',
	'./js/action/toggle-action.js',
	'./js/action/translation-action.js',

	// UI Component
	'./js/ui/component/apply-template-panel.js',
	'./js/ui/component/button.js',
	'./js/ui/component/dialog.js',
	'./js/ui/component/language-selectors.js',
	'./js/ui/component/license-panel.js',
	'./js/ui/component/html-selector.js',
	'./js/ui/component/template-panel.js',
	'./js/ui/component/translation-panel.js',

	// UI Main
	'./js/ui/ui-factory.js',
	'./js/ui/web-creation-frame.js',

	// InfoMainte
	'./js/auto-save-manager.js',

	// Main
	'./js/web-creation-main.js'
);
?>