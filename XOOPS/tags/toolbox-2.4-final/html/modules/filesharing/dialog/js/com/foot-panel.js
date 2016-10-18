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
/* $Id: foot-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

/* ダイアログフッタ部基底 */

var FootPanel = Class.create();
Object.extend(FootPanel.prototype, Panel.prototype);
Object.extend(FootPanel.prototype, {

	initialize: function(options) {
		Panel.prototype.initialize.apply(this, arguments);
		this.options = options;
		this.element = document.createElement('div');

		Panel.prototype.hide.apply(this, arguments);
	},

	draw: function() {
		this.getElement().addClassName('dialog-foot');
		this.getElement().addClassName('clearfix');

		this.update(new Template(this.Templates.base).evaluate({
			OK_BUTTON_ID: this.getOkButtonId(),
			CANCEL_BUTTON_ID: this.getCancelButtonId(),
			OK_BUTTON_LABEL: this.options.Text.OkButton,
			CANCEL_BUTTON_LABEL: this.options.Text.CancelButton
		}));

		this.addEvent('dialog_ok_click', this.getOkButtonId(), 'click', this.onOk.bindAsEventListener(this));
		this.addEvent('dialog_cancel_click', this.getCancelButtonId(), 'click', this.onCancel.bindAsEventListener(this));

		return this.element;
	},

	show: function() {
		Panel.prototype.show.apply(this, arguments);
		this.startEventObserving();
	},

	hide: function() {
		Panel.prototype.hide.apply(this, arguments);
		this.stopEventObserving();
	},

	_ID_ : {
		OK_BUTTON_ID : '-ok-button-id',
		CANCEL_BUTTON_ID : '-cancel-button-id'
	},

	getOkButtonId : function() {
		return this.options.Id + this._ID_.OK_BUTTON_ID;
	},
	getCancelButtonId : function() {
		return this.options.Id + this._ID_.CACEL_BUTTON_ID;
	},

	onOk: function(event) {
		if (FileSharingDialogFrame.notifySelectedOk()) {
			FileSharingDialogFrame.notifyDialogClose();
		}
	},

	onCancel: function(event) {
		FileSharingDialogFrame.notifyDialogClose();
	}

});

FootPanel.prototype.Templates = {
	base: ''
		+ '<div class="area-fr"><a id="#{OK_BUTTON_ID}" class="button button-ok">#{OK_BUTTON_LABEL}</a></div>'
		+ '<div class="area-fr"><a id="#{CANCEL_BUTTON_ID}" class="button button-cancel">#{CANCEL_BUTTON_LABEL}</a></div>'
}
