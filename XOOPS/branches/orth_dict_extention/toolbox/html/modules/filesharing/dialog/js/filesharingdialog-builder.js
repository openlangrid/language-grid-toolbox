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
/* $Id: filesharingdialog-builder.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var FileSharingDialogBuilder = Class.create();
Object.extend(FileSharingDialogBuilder.prototype, PanelBuilder.prototype);
Object.extend(FileSharingDialogBuilder.prototype, {

	options: null,

	dialogPanel : null,

	initialize: function(id, options) {
		PanelBuilder.prototype.initialize.apply(this, arguments);
		this.id = id;
		this.options = options;
	},

	build: function() {

		this.dialogPanel = new FileSharingDialogDialogPanel(this.id, this.options);

		var h = this._buildHeadPanel();
		var b = this._buildBodyPanel();
		var f = this._buildFootPanel();

		FileSharingDialogFrame.setDialogPanelObserver(this.dialogPanel);
		FileSharingDialogFrame.setHeadPanelObserver(h);
		FileSharingDialogFrame.setBodyPanelObserver(b);
		FileSharingDialogFrame.setFootPanelObserver(f);

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
		return new FileSharingDialogHeadPanel(this.options);
	},

	/* ボディパネルクラスを生成 */
	_buildBodyPanel : function() {
		return new FileSharingDialogBodyPanel(this.options);
	},

	/* フッタパネルクラスを生成 */
	_buildFootPanel : function() {
		return new FileSharingDialogFootPanel(this.options);
	}
});

var FileSharingLoadDialogBuilder = Class.create();
Object.extend(FileSharingLoadDialogBuilder.prototype, FileSharingDialogBuilder.prototype);
Object.extend(FileSharingLoadDialogBuilder.prototype, {

	initialize: function(id, options) {
		FileSharingDialogBuilder.prototype.initialize.apply(this, arguments);
	}
});

var FileSharingSaveDialogBuilder = Class.create();
Object.extend(FileSharingSaveDialogBuilder.prototype, FileSharingDialogBuilder.prototype);
Object.extend(FileSharingSaveDialogBuilder.prototype, {

	initialize: function(id, options) {
		FileSharingDialogBuilder.prototype.initialize.apply(this, arguments);
	},

	/* ヘッダパネルクラスを生成 */
	_buildHeadPanel : function() {
		return new FileSharingDialogHead_SavePanel(this.options);
	},

	/* フッタパネルクラスを生成 */
	_buildFootPanel : function() {
		var f = new FileSharingDialogFoot_SavePanel(this.options);
		FileSharingDialogFrame.addFileSelectObserver(f);
		return f;
	}
});
