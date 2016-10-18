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

var SaveHtmlAction = Class.create(AbstractAction, {

	dialog: null,
	errorMessages: null,

	initialize: function() {

	},

	getContents: function() {
		return '';
	},

	getParameters: function() {
		return {
//			fileName: $F('wc-save-html-file-name'),
			folderId : this.fileItem.folderId,
			fileName : this.fileItem.fileName,
			description : this.fileItem.description,
			readPermission : this.fileItem.readPermission,
			editPermission : this.fileItem.editPermission,
			contents: this.getContents()
		};
	},

	execute: function() {
		Logger.info('SaveHtmlAction.execute');

//		this.dialog = new Dialog({
//			title: Resource.SAVE_HTML,
//			body: Resource.Template.SAVE_HTML_DIALOG,
//			buttonSet: Dialog.ButtonSet.OK_CANCEL,
//			delegate: {
//
//				okClicked: function(event) {
//					if (!this.getParameters().fileName) {
//						alert(Resource.FILE_NAME_IS_EMPTY);
//						return;
//					}
//
//					$('wc-save-html-dialog-status-area').update(Resource.Image.NOW_LOADING + ' ' + Resource.NOW_SAVING);
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

		var options = {
			dialogMode : 'Save',
			onOk : this.onDialogOk.bind(this)
		};
		this.dialog = new FileSharingDialog('wc-html-save-dialog', options);
		this.dialog.show();
	},

	onDialogOk : function(item) {
		this.fileItem = item;
		this.send();
	},

	send: function() {
		new Ajax.Request(Resource.Url.SAVE_HTML, {
			asynchronous: false,
			postBody: Object.toQueryString(this.getParameters()),
			onSuccess: this.onSuccess.bind(this),
			onException: this.onException.bind(this),
			onFailure: this.onFailure.bind(this),
			onComplete: this.onComplete.bind(this)
		});
	},

	onSuccess: function(transport) {
		Logger.info('SaveHtmlAction.onSuccess');
		var response = transport.responseText.evalJSON();

		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}

		this.dialog.hide();
	},

	onException: function(t, e) {
		Logger.info('SaveHtmlAction.onException');
		Logger.error(e);

//		var span = document.createElement('span');
//		span.className = 'wc-red';
//		span.appendChild(document.createTextNode(e.message));
//
//		$('wc-save-html-dialog-status-area').update(span);
		alert(e.message);
	},

	onFailure: function(e) {
		Logger.info('SaveHtmlAction.onFailure');
		Logger.error(e);
	},

	onComplete: function() {
		Logger.info('SaveHtmlAction.onComplete');
	}
});

var SaveSourceHtmlAction = Class.create(SaveHtmlAction, {
	getContents: function() {
		return Model.Translation.getSourceAsString();
	}
});

var SaveTargetHtmlAction = Class.create(SaveHtmlAction, {
	getContents: function() {
		return Model.Translation.getTargetAsString();
	}
});