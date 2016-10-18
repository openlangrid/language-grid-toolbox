<?php
//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides accurate
// translation using the autocomplete feature based on parallel texts and
// translation template.
// Copyright (C) 2010  CITY OF KYOTO
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
/** $Id: autocomplate_js.php 3617 2010-04-12 02:36:46Z yoshimura $ */

require('../../../mainfile.php');

// 言語リソースをロード
$Legacy_LanguageManager = new Legacy_LanguageManager();
$Legacy_LanguageManager->loadModuleMessageCatalog('autocomplate');

$moduleurl = XOOPS_URL.'/modules/autocomplete';
?>

var AutoComplateDefines = {
	URL: {
		MODULE_URL: '<?php echo $moduleurl; ?>'
	},
	Label: {
		ShowWindow: '<?php echo _MD_AUTOCOMPLETE_SETTING; ?>'
	}
};

<?php
$js = file_get_contents(dirname(__FILE__).'/auto_complete_main.js');
echo $js;
?>