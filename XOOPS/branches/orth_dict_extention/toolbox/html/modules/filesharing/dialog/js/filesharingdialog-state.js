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
/* $Id: filesharingdialog-state.js 4708 2010-11-09 08:38:56Z kitajima $ */

var FileSharingDialogFrame = new (Class.create({

	states : {},

	observers : {},

	deligates : {},

	initialize : function() {

	},

	setState : function(name, values) {
		this.states[name] = values;
	},
	getState : function(name) {
		if (this.states[name]) {
			return this.states[name];
		}
		return null;
	},

	setError : function(message) {
		alert(message);
	},

	setCurrentFolder : function(folder) {
		this.setState('currentFolder', folder);
	},
	getCurrentFolder : function() {
		return this.getState('currentFolder');
	},
//	getCurrentFolderId : function() {
//		return this.getState('currentFolder').id;
//	},

	addObserver : function(name, o) {
		this.observers[name] = o;
	},

	setDialogPanelObserver : function(o) {
		this.addObserver('dialogPanel', o);
	},
	setHeadPanelObserver : function(o) {
		this.addObserver('headPanel', o);
	},
	setBodyPanelObserver : function(o) {
		this.addObserver('bodyPanel', o);
	},
	setFootPanelObserver : function(o) {
		this.addObserver('footPanel', o);
	},

	addDeligateEvents : function(name, e) {
		this.deligates[name] = e;
	},

	setOkEvent : function(e) {
		this.addDeligateEvents('ok', e);
	},
	setHideEvent : function(e) {
		this.addDeligateEvents('hide', e);
	},
	setFileSelectEvent : function(e) {
		this.addDeligateEvents('fileSelect', e);
	},

	/* ダイアログを閉じる */
	notifyDialogClose : function() {
		if (this.observers.dialogPanel) {
			this.observers.dialogPanel.hide.apply(this.observers.dialogPanel, arguments);
		}

		if (this.deligates.hide) {
			this.deligates.hide.apply(this.deligates.hide);
		}
	},

	/* ファイルリストロード完了 */
	notifyLoadedFileLists : function() {
		if (this.observers.footPanel) {
			this.observers.footPanel.show.apply(this.observers.footPanel, arguments);
		}
		if (this.observers.bodyPanel) {
			this.observers.bodyPanel.onFileListLoaded.apply(this.observers.bodyPanel, arguments);
		}
	},

	fileSelectObservers : [],
	addFileSelectObserver : function(o) {
		this.fileSelectObservers.push(o);
	},

	/* ファイル選択を通知 */
	notifyFileSelect : function(item) {
		this.setState('currentFile', item);
		if (this.deligates.fileSelect) {
			this.deligates.fileSelect.apply(this.deligates.fileSelect, [item]);
		}

		var args = $A(arguments).clone();
		args.unshift(this);

		this.fileSelectObservers.each(function(o) {
			o.onFileSelect.apply(o, args);
		}.bind(this));
	},

	/* OKボタンクリックを通知 */
	notifySelectedOk : function() {
		var cf = this.getState('currentFile');
		if (cf == null) {
			return false;
		}

		if (this.deligates.ok) {
			this.deligates.ok.apply(this.deligates.ok, [cf]);
		}
		return true;
	},

	notifyAll : function() {
		var args = $A(arguments).clone();
		args.unshift(this);

		for (var name in this.observers) {
			this.observers[name].draw.apply(this.observers[name], args);
		}
	},

	clear : function() {
		this.states = {};
		this.observers = {};
		this.deligates = {};
		this.fileSelectObservers = [];
	}
}))();