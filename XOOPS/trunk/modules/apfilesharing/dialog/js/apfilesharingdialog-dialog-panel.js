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
/* $Id: apfilesharingdialog-dialog-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var apfilesharingDialogDialogPanel = Class.create();
Object.extend(apfilesharingDialogDialogPanel.prototype, DialogPanel.prototype);
Object.extend(apfilesharingDialogDialogPanel.prototype, {

	idPrefix: 'apfilesharingdialog_dialog_',

	headPanel:null,
	bodyPanel:null,
	footPanel:null,

	selectedFileId: null,

	initialize: function(id, options) {
		DialogPanel.prototype.initialize.apply(this, arguments);
	},

	setHeadPanel : function(headPanel) {
		this.headPanel = headPanel;
	},

	setBodyPanel : function(bodyPanel) {
		this.bodyPanel = bodyPanel;
	},

	setFootPanel : function(footPanel) {
		this.footPanel = footPanel;
	},

	draw: function() {
		this.element.appendChild(this.headPanel.draw());
		this.element.appendChild(this.bodyPanel.draw());
		this.element.appendChild(this.footPanel.draw());

//		apfilesharingDialogState.Condition.addObserver(this.footPanel);
		return this.element;
	},

	show: function() {
		Glayer.show();
		DialogPanel.prototype.show.apply(this, arguments);
	},

	hide: function() {
		DialogPanel.prototype.hide.apply(this, arguments);
		Glayer.show();
		Glayer.hide();
	},

//	onBodyLoaded: function() {
//		this.footPanel.show();
//	},

	/* １個のファイルが選択されたイベントハンドラ */
	onFileSelected: function(selectedFileId) {
		this.selectedFileId = selectedFileId;
	}

//	onOk: function() {
//		this.options.onOk.apply(this, [this.selectedFileId]);
//	}
});

