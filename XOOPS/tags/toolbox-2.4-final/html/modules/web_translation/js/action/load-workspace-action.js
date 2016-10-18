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

var LoadWorkspaceAction = Class.create(AbstractAction, {

	dialog: null,
	hidden: false,

	/**
	 *
	 */
	initialize: function() {
	},

	execute: function() {
		Logger.info('LoadWorkspaceAction.execute');

//		this.dialog = new Dialog({
//			title: Resource.LOAD_WORKSPACE,
//			body: Resource.Template.LOAD_WORKSPACE_DIALOG,
//			buttonSet: Dialog.ButtonSet.OK_CANCEL,
//			delegate: {
//				okClicked: function(event) {
//
//					if ($$('#wc-load-workspace-dialog-select-area select').length == 0) {
//						this.dialog.hide();
//					}
//
//					$('wc-load-workspace-dialog-status-area').update(Resource.Image.NOW_LOADING + ' ' + Resource.NOW_LOADING);
//					setTimeout(this.send.bind(this), 10);
//				}.bind(this),
//
//				cancelClicked: function(event) {
//					this.dialog.hide();
//				}.bind(this),
//
//				onHidePanel: function() {
//					this.hidden = true;
//				}.bind(this)
//			}
//		});
//
//		this.dialog.show();
//		this.hidden = false;
//
//		this.loadList();

		var options = {
			dialogMode : 'Open',
			onOk : this.onDialogOk.bind(this)
		};
		this.dialog = new FileSharingDialog('wc-workspace-load-dialog', options);
		this.dialog.show();
	},

	onDialogOk : function(item) {
		this.fileItem = item;
		this.send();
	},

//	loadList: function() {
//		$('wc-load-workspace-dialog-select-area').update(Resource.Image.NOW_LOADING + ' ' + Resource.NOW_LOADING);
//
//		new Ajax.Request(Resource.Url.LOAD_WORKSPACE, {
//			onSuccess: function(transport) {
//				Logger.info('LoadWorkspaceAction.loadList::onSuccess');
//
//				if (this.hidden) {
//					return;
//				}
//
//				var response = transport.responseText.evalJSON();
//
//				var select = document.createElement('select');
//				var files = response.contents;
//
//				if (files.length == 0) {
//					$('wc-load-workspace-dialog-select-area').update(Resource.FILE_NOT_FOUND);
//					return;
//				}
//
//				files.each(function(file) {
//					var option = document.createElement('option');
//					option.setAttribute('value', file.id);
//					option.appendChild(document.createTextNode(file.name));
//					select.appendChild(option);
//				});
//
//				$('wc-load-workspace-dialog-select-area').update(select);
//			}.bind(this),
//			onException: function(t, e) {
//				Logger.info('LoadWorkspaceAction.loadList::onException');
//				Logger.error(e);
//			}.bind(this),
//			onFailure: function(e) {
//				Logger.info('LoadWorkspaceAction.loadList::onFailure');
//				Logger.error(e);
//			}.bind(this),
//			onComplete: function(transport) {
//				Logger.info('LoadWorkspaceAction.loadList::onComplete');
//			}.bind(this)
//		});
//	},

	getParameters: function() {
		Logger.info('LoadWorkspaceAction.getParameters');

		return {
//			id: $$('#wc-load-workspace-dialog-select-area select')[0].value
			id : this.fileItem.id
		};
	},

	send: function() {
		new Ajax.Request(Resource.Url.READ_WORKSPACE, {
			asynchronous: false,
			postBody: Object.toQueryString(this.getParameters()),
			onSuccess: this.onSuccess.bind(this),
			onException: this.onException.bind(this),
			onFailure: this.onFailure.bind(this),
			onComplete: this.onComplete.bind(this)
		});
	},

	onSuccess: function(transport) {
		Logger.info('LoadWorkspaceAction.onSuccess');

		var response = transport.responseText.evalJSON();

		if (response.status.toUpperCase() == 'ERROR') {
			throw new Error(response.message);
		}

		Model.Language.setSourceLanguage(response.contents.sourceLanguage);
		Model.Language.setTargetLanguage(response.contents.targetLanguage);
		Model.Language.setChanged();
		Model.Language.notifyObservers();

		Model.Translation.setResult(response.contents.result);
		Model.Translation.setChanged();
		Model.Translation.notifyObservers();

		Model.ApplyTemplate.set(response.contents.appliedTemplates);
		Model.ApplyTemplate.setChanged();
		Model.ApplyTemplate.notifyObservers();

		Model.Template.set(response.contents.templates);
		Model.Template.setChanged();
		Model.Template.notifyObservers();

		Model.EditState.setChange(true);

//		var select = $$('#wc-load-workspace-dialog-select-area select')[0];
//		var fileName = select.options[select.selectedIndex].text;
		var fileName = this.fileItem.name;
		Model.FileName.setWorkspace(fileName);
		this.dialog.hide();
	},

	onException: function(t, e) {
		Logger.error(e);
//		var span = document.createElement('span');
//		span.className = 'wc-red';
//		span.appendChild(document.createTextNode(e.message));
//
////		$('wc-load-workspace-dialog-status-area').update(span);
//		$('wc-html-load-dialog').update(span);
		alert(e.message);
	},

	onFailure: function(e) {
		Logger.error(e);
//		$('wc-load-workspace-dialog-status-area').update();
	},

	onComplete: function() {
		Logger.info('LoadWorkspaceAction.onComplete');
	}
});