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
/* $Id: head-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

/* ダイアログヘッダ部基底 */

var HeadPanel = Class.create();
Object.extend(HeadPanel.prototype, Panel.prototype);
Object.extend(HeadPanel.prototype, {

	initialize: function(options) {
		Panel.prototype.initialize.apply(this, arguments);
		this.options = options;
		this.element = document.createElement('div');

		Panel.prototype.hide.apply(this, arguments);

		this.addEvent('closeDialog', this.getCloseButtonId(), 'click', this.onCloseClicked.bindAsEventListener(this), false);
	},

	draw: function() {
		this.getElement().addClassName('dialog-head');
		this.getElement().addClassName('clearfix');

		this.update(new Template(this.Templates.base).evaluate({
			CLOSE_BUTTON_ID: this.getCloseButtonId(),
			dialogTitle: this._getDialogTitle()
		}));
		return this.element;
	},

	show: function() {
		this.startEventObserving();
		Panel.prototype.show.apply(this, arguments);
	},

	hide: function() {
		this.stopEventObserving();
		Panel.prototype.hide.apply(this, arguments);
	},

	_getDialogTitle : function() {
		return this.options.Text.dialogTitle;
	},

	_ID_ : {
		CLOSE_BUTTON_ID : '-close-button-id'
	},

	getCloseButtonId : function() {
		return this.options.Id + this._ID_.CLOSE_BUTTON_ID;
	},

	onCloseClicked : function(event) {
		FileSharingDialogFrame.notifyDialogClose();
	}

});

HeadPanel.prototype.Templates = {
	base: ''
		+ '<div class="area-fr"><a id="#{CLOSE_BUTTON_ID}" class="button button-close">X</a></div>'
		+ '<div class="area-fl"><h2>#{dialogTitle}</h2></div>'
}
