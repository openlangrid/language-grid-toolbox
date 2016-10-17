//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user
// to open or save files on the File Sharing function.
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
/* $Id: apfilesharingdialog-builder.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var apfilesharingDialogBuilder = Class.create();
Object.extend(apfilesharingDialogBuilder.prototype, PanelBuilder.prototype);
Object.extend(apfilesharingDialogBuilder.prototype, {

	options: null,

	dialogPanel : null,

	initialize: function(id, options) {
		PanelBuilder.prototype.initialize.apply(this, arguments);
		this.id = id;
		this.options = options;
	},

	build: function() {

		this.dialogPanel = new apfilesharingDialogDialogPanel(this.id, this.options);

		var h = this._buildHeadPanel();
		var b = this._buildBodyPanel();
		var f = this._buildFootPanel();

		apfilesharingDialogFrame.setDialogPanelObserver(this.dialogPanel);
		apfilesharingDialogFrame.setHeadPanelObserver(h);
		apfilesharingDialogFrame.setBodyPanelObserver(b);
		apfilesharingDialogFrame.setFootPanelObserver(f);

		this.dialogPanel.setHeadPanel(h);
		this.dialogPanel.setBodyPanel(b);
		this.dialogPanel.setFootPanel(f);
		this.dialogPanel.draw();

		// dialogPanelポップアップ化
		var popupLayer = new PopupbleAdapter(this.dialogPanel.getElement(), {dialogWidth: '800'});
		popupLayer.fetch();

		popupLayer.show();
		return this.dialogPanel;
	},

	/* ヘッダパネルクラスを生成 */
	_buildHeadPanel : function() {
		return new apfilesharingDialogHeadPanel(this.options);
	},

	/* ボディパネルクラスを生成 */
	_buildBodyPanel : function() {
		return new apfilesharingDialogBodyPanel(this.options);
	},

	/* フッタパネルクラスを生成 */
	_buildFootPanel : function() {
		return new apfilesharingDialogFootPanel(this.options);
	}
});

var apfilesharingLoadDialogBuilder = Class.create();
Object.extend(apfilesharingLoadDialogBuilder.prototype, apfilesharingDialogBuilder.prototype);
Object.extend(apfilesharingLoadDialogBuilder.prototype, {

	initialize: function(id, options) {
		apfilesharingDialogBuilder.prototype.initialize.apply(this, arguments);
	}
});

var apfilesharingSaveDialogBuilder = Class.create();
Object.extend(apfilesharingSaveDialogBuilder.prototype, apfilesharingDialogBuilder.prototype);
Object.extend(apfilesharingSaveDialogBuilder.prototype, {

	initialize: function(id, options) {
		apfilesharingDialogBuilder.prototype.initialize.apply(this, arguments);
	},

	/* ヘッダパネルクラスを生成 */
	_buildHeadPanel : function() {
		return new apfilesharingDialogHead_SavePanel(this.options);
	},

	/* フッタパネルクラスを生成 */
	_buildFootPanel : function() {
		var f = new apfilesharingDialogFoot_SavePanel(this.options);
		apfilesharingDialogFrame.addFileSelectObserver(f);
		return f;
	}
});
