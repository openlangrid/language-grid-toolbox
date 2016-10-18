//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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
/* $Id: file-list-popup-panel.js 3662 2010-06-16 02:22:17Z yoshimura $ */

var FileListPopupPanel = Class.create();
Object.extend(FileListPopupPanel.prototype, PopupPanel.prototype);
Object.extend(FileListPopupPanel.prototype, {

	WIDTH : '900',

	module : null,
	bodyPanel : null,

	initialize : function() {
		PopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({});
	},

	loadRootDirectory : function() {
		new Ajax.Updater(
			this.Config.Id.FILE_LIST_LOAD_AREA,
			Global.Url.FILE_DIALOG,
			{
				evalScripts: true

			}
		);
	}
});

FileListPopupPanel.prototype.Config = {
	Id : {
		FILE_LIST_LOAD_AREA : 'folderShowContainer'
	}
};

FileListPopupPanel.prototype.Event = {

	load : function() {
		new Ajax.Updater(
			this.Config.Id.FILE_LIST_LOAD_AREA,
			Global.Url.FILE_DIALOG,
			{
				evalScripts: true

			}
		);
	}

};

FileListPopupPanel.prototype.Templates = {
	base : ''
		+ '	<div style="width: 900px;" class="area-popup">'
		+ '		<div class="area-fr">'
		+ '			<a class="btn-popup-close" href="javascript: hideFileDialog();">x</a>'
		+ '		</div>'
		+ '		<br class="clear">'
		+ '		<strong class="h-pagetitle">#{dialog_title}</strong>'
		+ '		<div class="tab-part-bg">'
		+ '			<div class="tab-pain" id="loadFromToolbox">'
		+ '				<div id="folderShowContainer">'
		+ '				</div>'
		+ '			</div>'
		+ '		</div>'
		+ '		<div class="area-fr-p4">'
		+ '			<a style="width: 100px;" class="btn-s" onclick="load(this);" href="javascript: void(0);">'
		+ '				<span class="btn-save">選択</span>'
		+ '			</a>'
		+ '					&nbsp;'
		+ '			<a class="btn" href="javascript: hideFileDialog();">'
		+ '				<span class="btn-cancel">キャンセル</span>'
		+ '			</a>'
		+ '		</div>'
		+ '		<br class="clear">'
		+ '	</div>',
	css : '<style type="text/css">'
		+ '.area-popup {text-align: left;}'
		+ '.area-fr {float: right;}'
		+ '.btn-popup-close {'
		+ '  background-image:url(./image/button/btn_bg.png);background-repeat:repeat-x;width:40px;'
		+ '}'
		+ '</style>'
};