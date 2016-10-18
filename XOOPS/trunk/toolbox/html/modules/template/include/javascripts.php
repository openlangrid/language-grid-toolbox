<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
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
$javascripts = array(

	// component
	'/component/util/word-sets-hash.js',
	'/component/util/categories-hash.js',

	// config
	'/global.php',

	// validator
	'/validator/qa-resource-validator.js',

	// component
	'/component/panel/panel.js',
	'/component/panel/light-popup-panel.js',
	'/component/panel/popup-panel.js',

	'/component/util/language-utils.js',
	'/component/util/pager.js',
	'/component/util/per-page.js',

	// Edit
	// *popup
	'/state/edit/popup/qa-edit-master-set-popup-panel.js',
	'/state/edit/popup/qa-edit-master-word-popup-panel.js',
	'/state/edit/popup/qa-edit-insert-parameter-popup-panel.js',
	'/state/edit/popup/qa-edit-save-parameter-popup-panel.js',
	'/state/edit/popup/qa-edit-select-category-popup-panel.js',
	'/state/edit/popup/qa-edit-record-unsaved-popup-panel.js',
	'/state/edit/popup/qa-edit-master-category-popup-panel.js',
	'/state/edit/popup/qa-edit-record-delete-record-popup-panel.js',

	// *main
	'/state/edit/qa-edit-record-state-manager.js',
	'/state/edit/qa-edit-record-panel.js',
	'/state/edit/qa-edit-records-panel.js',
	'/state/edit/qa-edit-records-with-multi-resources-panel.js',
	'/state/edit/qa-edit-panel.js',
	'/state/edit/qa-edit-state.js',

	// Resources
	// *popup
	'/state/resources/popup/qa-delete-qa-popup-panel.js',
	'/state/resources/popup/qa-add-qa-popup-panel.js',
	'/state/resources/popup/qa-edit-qa-popup-panel.js',
	'/state/resources/popup/qa-import-qa-popup-panel.js',
	'/state/resources/popup/qa-resource-conroller-popup-panel.js',

	// *main
	'/state/resources/qa-resources-body-panel-table-sorter.js',
	'/state/resources/qa-resources-body-panel.js',
	'/state/resources/qa-resources-header-panel.js',
	'/state/resources/qa-resources-panel.js',
	'/state/resources/qa-resources-state.js',

	// Search
	'/state/search/qa-search-records-panel.js',
	'/state/search/qa-search-record-panel.js',
	'/state/search/qa-search-conditions-panel.js',
	'/state/search/qa-search-panel.js',
	'/state/search/qa-search-state.js',

	// main
	'/qa-main-frame.js',
	'/qa-main.js'
);

?>