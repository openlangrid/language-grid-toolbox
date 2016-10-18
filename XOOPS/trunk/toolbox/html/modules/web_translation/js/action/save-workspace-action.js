//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

var SaveWorkspaceAction = Class.create(AbstractAction, {

	_errorMessages: [],

	/**
	 *
	 */
	initialize: function() {

	},

	valid: function() {
		this._errorMessages = [];
		return (this._errorMessages.length == 0);
	},

	getErrorMessages: function() {
		return this._errorMessages;
	},

	getParameters: function() {
		Logger.info('SaveWorkspaceAction.getParameters');

		var p = {
//			fileName: $F('wc-save-workspace-file-name'),
			folderId : this.fileItem.folderId,
			fileName : this.fileItem.fileName,
			description : this.fileItem.description,
			readPermission : this.fileItem.readPermission,
			editPermission : this.fileItem.editPermission,
			sourceLanguage: Model.Language.getSourceLanguage(),
			targetLanguage: Model.Language.getTargetLanguage()
		};

		Model.ApplyTemplate.get().each(function(t, i) {
			p['appliedTemplates[' + i + '][id]'] = t.id;
		});

		Model.Translation.getResult().each(function(line, i) {
			p['result[' + i + '][status]'] = line.status;
			p['result[' + i + '][source]'] = line.source;
			p['result[' + i + '][target]'] = line.target;
		});

		Model.Template.get().each(function(t, i) {
			p['templates[' + i + '][source]'] = t.source;
			p['templates[' + i + '][target]'] = t.target;
		});

		return p;
	},

	execute: function() {
		Logger.info('SaveWorkspaceAction.execute');

		if (!this.valid()) {
			var m = this.getErrorMessages();
			alert(m.join('\n'));
			return;
		}

//		this.dialog = new Dialog({
//			title: Resource.SAVE_WORKSPACE,
//			body: Resource.Template.SAVE_WORKSPACE_DIALOG,
//			buttonSet: Dialog.ButtonSet.OK_CANCEL,
//			delegate: {
//				okClicked: function(event) {
//					if (!this.getParameters().fileName) {
//						alert(Resource.FILE_NAME_IS_EMPTY);
//						return;
//					}
//
//					$('wc-save-workspace-dialog-status-area').update(Resource.Image.NOW_LOADING + ' ' + Resource.NOW_SAVING);
//					setTimeout(this.send.bind(this), 10);
//				}.bind(this),
//
//				cancelClicked: function(event) {
//					this.dialog.hide();
//				}.bind(this)
//			}
//		});
//
//		this.dialog.show();
//
//		$('wc-save-workspace-file-name').value = Model.FileName.getWorkspace();

		var options = {
			dialogMode : 'Save',
			onOk : this.onDialogOk.bind(this)
		};
		this.dialog = new FileSharingDialog('wc-workspace-save-dialog', options);
		this.dialog.show();
	},

	onDialogOk : function(item) {
		this.fileItem = item;
		this.send();
	},

	send: function() {
		new Ajax.Request(Resource.Url.SAVE_WORKSPACE, {
			asynchronous: false,
			postBody: Object.toQueryString(this.getParameters()),
			onSuccess: this.onSuccess.bind(this),
			onException: this.onException.bind(this),
			onFailure: this.onFailure.bind(this),
			onComplete: this.onComplete.bind(this)
		});
	},

	onSuccess: function(transport) {
		Logger.info('SaveWorkspaceAction.onSuccess');

		var response = transport.responseText.evalJSON();

		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}

		Model.EditState.setChange(false);

		Model.FileName.setWorkspace(this.getParameters().fileName);
		this.dialog.hide();
	},

	onException: function(t, e) {
		Logger.info('SaveWorkspaceAction.onException');
		Logger.error(e);

//		var span = document.createElement('span');
//		span.className = 'wc-red';
//		span.appendChild(document.createTextNode(e.message));
//
//		$('wc-save-workspace-dialog-status-area').update(span);
		alert(e.message);
	},

	onFailure: function(e) {
		Logger.info('SaveWorkspaceAction.onFailure');
		Logger.error(e);
	},

	onComplete: function() {
		Logger.info('SaveWorkspaceAction.onComplete');
	}
});