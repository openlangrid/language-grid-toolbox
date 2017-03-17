<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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
 * @author kitajima
 */
require_once dirname(__FILE__).'/../../../../mainfile.php';
$root = XCube_Root::getSingleton();
?>
var Mode = {
	ADMIN : 0,
	USER : 1
};
var ServiceType = {
	DICTIONARY : 'BILINGUALDICTIONARYWITHLONGESTMATCHSEARCH',
	TRANSLATOR : 'TRANSLATION',
	MORPHOLOGICALANALYSIS : 'MORPHOLOGICALANALYSIS'
};
var Config = {
	Text : {
		ADD : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_ADD; ?>',
		ADD_SERVICE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_ADD_SERVICE; ?>',
		ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_ARE_YOU_REALLY_SURE_YOU_WANT_TO_REMOVE_THE_SERVICE; ?>',
		AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED; ?>',
		AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED; ?>',
		CANCEL : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_CANCEL; ?>',
		COPYRIGHT : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_COPYRIGHT; ?>',
		DICTIONARY : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_DICTIONARY; ?>',
		BIDIRECTIONAL : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_BIDIRECTIONAL; ?>',
		EDIT_SERVICE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_EDIT_SERVICE; ?>',
		ENDPOINT_URL : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_ENDPOINT_URL; ?>',
		HAS_DUPLICATED_PATHS : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_HAS_DUPLICATED_PATHS; ?>',
		HAS_SAME_PATHS : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_HAS_SAME_PATHS; ?>',
		IMPORT : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_IMPORT; ?>',
		LANGUAGE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_LANGUAGE; ?>',
		LANGUAGES : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_LANGUAGES; ?>',
		LICENSE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_LICENSE; ?>',
		NO_IMPORTED_SERVICES : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NO_IMPORTED_SERVICES; ?>',
		NOW_IMPORTING : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NOW_IMPORTING; ?>',
		NO_SERVICE_IS_SELECTED : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NO_SERVICE_IS_SELECTED; ?>',
		NOW_LOADING : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NOW_LOADING; ?>',
		NOW_REMOVING : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NOW_REMOVING; ?>',
		NOW_SAVING : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NOW_SAVING; ?>',
		NULL_LANGUAGE_NAME : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NULL_LANGUAGE_NAME; ?>',
		NULL_TABLE_VALUE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_NULL_TABLE_VALUE; ?>',
		OK : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_OK; ?>',
		PROVIDER : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_PROVIDER; ?>',
		REGISTRATION_DATE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_REGISTRATION_DATE; ?>',
		REMOVE_LANGUAGE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_REMOVE_LANGUAGE; ?>',
		REQUIRED_FIELD : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_REQUIRED_FIELD; ?>',
		MONODIRECTIONAL : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_MONODIRECTIONAL; ?>',
		SAVE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_SAVE; ?>',
		SERVICE_NAME : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_SERVICE_NAME; ?>',
		SERVICE_TYPE : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_SERVICE_TYPE; ?>',
		STRING_IS_BLANK : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_STRING_IS_BLANK; ?>',
		THE_INPUT_URL_IS_INVALID : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_THE_INPUT_URL_IS_INVALID; ?>',
		TRANSLATOR : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_TRANSLATOR; ?>',
		MORPHOLOGICALANALYSIS : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_MORPHOLOGICALANALYSIS ?>',
		BASIC_USERID : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_BASIC_USERID; ?>',
		BASIC_PASSWD : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_BASIC_PASSWD; ?>'
	},
	Message : {
		ON_AJAX_SERVER_ERROR : '<?php echo _MI_LANGRID_IMPORTED_SERVICES_ON_AJAX_SERVER_ERROR; ?>'
	},
	Url : {
		ADD_SERVICE : './?page=import&action=AddService',
		EDIT_SERVICE : './?page=import&action=EditService',
		LOAD_SERVICES : './?page=import&action=LoadServices',
		LOAD_SUPPORTED_LANGUAGES : './?page=import&action=LoadSupportedLanguages',
		REMOVE_SERVICE : './?page=import&action=RemoveService'
	},
	Image : {
		NOW_LOADING : '<img src="./images/imported_services/ajax.gif" /> '
	},
	ClassName : {
		SPAN_LINK : 'link',
		SPAN_NOT_LINK : 'notlink',
		BUTTON_DISABLED : 'langrid-common-button-disabled'
	},
	FireEventName : {
		TABLE_ROW_CLICKED : 'row:clicked'
	},
	Html : {
		Element : {
			BR : '<br />'
		},
		Attribute : {
			SELECTED : ' selected="selected"'
		}
	}
};