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
/* $Id: apfilesharingdialog-foot-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var apfilesharingDialogFootPanel = Class.create();
Object.extend(apfilesharingDialogFootPanel.prototype, FootPanel.prototype);
Object.extend(apfilesharingDialogFootPanel.prototype, {

	initialize: function(options, obserbles) {
		FootPanel.prototype.initialize.apply(this, arguments);
	}
});

var apfilesharingDialogFoot_SavePanel = Class.create();
Object.extend(apfilesharingDialogFoot_SavePanel.prototype, apfilesharingDialogFootPanel.prototype);
Object.extend(apfilesharingDialogFoot_SavePanel.prototype, {

	initialize: function(options, obserbles) {
		apfilesharingDialogFootPanel.prototype.initialize.apply(this, arguments);
	},

	draw: function() {
		this.getElement().addClassName('dialog-foot');
		this.getElement().addClassName('clearfix');

		var readSelect = new Template(this.Templates.selectElem).evaluate({
			ID : this.getSaveReadPermId(),
			OPTIONS : new Template(this.Templates.optionElem).evaluate({
				VALUE1 : 'public',
				VALUE2 : 'user',
				LABEL1 : this.options.Text.SavePermOptPublic,
				LABEL2 : this.options.Text.SavePermOptUser
			})
		});
		var editSelect = new Template(this.Templates.selectElem).evaluate({
			ID : this.getSaveEditPermId(),
			OPTIONS : new Template(this.Templates.optionElem).evaluate({
				VALUE1 : 'public',
				VALUE2 : 'user',
				LABEL1 : this.options.Text.SavePermOptPublic,
				LABEL2 : this.options.Text.SavePermOptUser
			})
		});

		this.update(new Template(this.Templates.base).evaluate({
			OK_BUTTON_ID: this.getOkButtonId(),
			CANCEL_BUTTON_ID: this.getCancelButtonId(),
			SAVE_FILE_NAME_ID: this.getSaveFileNameId(),
			SAVE_FILE_DESC_ID: this.getSaveFileDescId(),
			OkButton: this.options.Text.SaveOkButton,
			CancelButton: this.options.Text.CancelButton,
			SaveFileName: this.options.Text.SaveFileName,
			SaveFileDesc: this.options.Text.SaveFileDesc,
			SavePermission: this.options.Text.SavePermission,
			SaveReadPermission: this.options.Text.SaveReadPermission,
			SaveEditPermission: this.options.Text.SaveEditPermission,
			ReadPermSelect: readSelect,
			EditPermSelect: editSelect
		}));

		this.addEvent('dialog_ok_click', this.getOkButtonId(), 'click', this.onOk.bindAsEventListener(this));
		this.addEvent('dialog_cancel_click', this.getCancelButtonId(), 'click', this.onCancel.bindAsEventListener(this));

		return this.element;
	},

	_ID_ : {
		OK_BUTTON_ID : '-ok-button-id',
		CANCEL_BUTTON_ID : '-cancel-button-id',
		SAVE_FILE_NAME_ID : '-save-file-name-id',
		SAVE_FILE_DESC_ID : '-save-file-desc-id',
		SAVE_READ_PERM_ID : '-save-read-perm-id',
		SAVE_EDIT_PERM_ID : '-save-edit-perm-id'
	},

	getOkButtonId : function() {
		return this.getPrefixedId(this._ID_.OK_BUTTON_ID);
	},
	getCancelButtonId : function() {
		return this.getPrefixedId(this._ID_.CACEL_BUTTON_ID);
	},
	getSaveFileNameId : function() {
		return this.getPrefixedId(this._ID_.SAVE_FILE_NAME_ID);
	},
	getSaveFileDescId : function() {
		return this.getPrefixedId(this._ID_.SAVE_FILE_DESC_ID);
	},
	getSaveReadPermId : function() {
		return this.getPrefixedId(this._ID_.SAVE_READ_PERM_ID);
	},
	getSaveEditPermId : function() {
		return this.getPrefixedId(this._ID_.SAVE_EDIT_PERM_ID);
	},

	getPrefixedId : function(id) {
		return this.options.Id + id;
	},

	onOk: function(event) {
		// フォルダの権限を確認
		if (!apfilesharingDialogFrame.getCurrentFolder().canEdit) {
			apfilesharingDialogFrame.setError(apfilesharingDialog_Global.Msg.PermissionErrorNoEdit);
			return;
		}

		// okデリゲートに渡す引数オブジェクトを生成
		var param = {
			folderId : apfilesharingDialogFrame.getCurrentFolder().id,
			fileName : $F(this.getSaveFileNameId()),
			description : $F(this.getSaveFileDescId()),
			readPermission : $F(this.getSaveReadPermId()),
			editPermission : $F(this.getSaveEditPermId())
		};

		apfilesharingDialogFrame.setState('currentFile', param);

		if (apfilesharingDialogFrame.notifySelectedOk()) {
			apfilesharingDialogFrame.notifyDialogClose();
		}
	},

	onFileSelect : function(event, item) {
		$(this.getSaveFileNameId()).value = item.name;
		$(this.getSaveFileDescId()).value = item.description;
	}
});

apfilesharingDialogFoot_SavePanel.prototype.Templates = {
	base : ''
		+ '<table cellspacing="0" cellpadding="0" border="0">'
		+ '<tr>'
		+ '<th>#{SaveFileName}</th>'
		+ '<td><input type="text" id="#{SAVE_FILE_NAME_ID}" name="#{SAVE_FILE_NAME_ID}" size="50"></td>'
		+ '</tr>'
		+ '<tr>'
		+ '<th>#{SaveFileDesc}</th>'
		+ '<td><input type="text" id="#{SAVE_FILE_DESC_ID}" name="#{SAVE_FILE_DESC_ID}" size="50"></td>'
		+ '</tr>'
		+ '<tr>'
		+ '<th>#{SavePermission}</th>'
		+ '<td>'
		+ '<span>#{SaveReadPermission}</span>&nbsp;'
		+ '<span>#{ReadPermSelect}</span>'
		+ '<br>'
		+ '<span>#{SaveEditPermission}</span>&nbsp;'
		+ '<span>#{EditPermSelect}</span>'
		+ '</td>'
		+ '</tr>'
		+ '</table>'
		+ '<div class="area-fr"><a id="#{OK_BUTTON_ID}" class="button button-ok">#{OkButton}</a></div>'
		+ '<div class="area-fr"><a id="#{CANCEL_BUTTON_ID}" class="button button-cancel">#{CancelButton}</a></div>'
	,
	selectElem : '<select id="#{ID}" name="#{ID}">#{OPTIONS}</select>',
	optionElem : '<option value="#{VALUE1}">#{LABEL1}</option><option value="#{VALUE2}">#{LABEL2}</option>'
};
