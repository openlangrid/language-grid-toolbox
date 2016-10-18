//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox.
//
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
/* $Id: context-menu-popup-panel.js 3727 2010-07-13 05:30:48Z yoshimura $ */

var ContextMenuPopupPanel = Class.create();
Object.extend(ContextMenuPopupPanel.prototype, PopupPanel.prototype);
Object.extend(ContextMenuPopupPanel.prototype, {

	WIDTH : '150',

	mid : null,
	module : null,
	bodyPanel : null,

	fileDialog : null,

	initialize : function() {
		PopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
		this.addEvent('uicTextResourceFileopen', this.Config.Id.FILE_OPEN, 'click', this.Event.uicTextResourceFileopen.bindAsEventListener(this));
	},

	setEventHandlers: function(handlers) {
		this.handlers = handlers;
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({
			downloadurl : new Template(Global.Url.TEXT_RESOURCE_DOWNLOAD).evaluate({lid : this.module.shared_file_id}),
			downloadStyle : this.module.file == '' ? 'display:none;' : '',
			SELECT_LAB : Global.Label.TextResource.FILE_SELECT,
			DOWNLOAD_LAB : Global.Label.TextResource.FILE_DOWNLOAD,
			resourceTemplate : Global.Label.TextResource.CREATE_TEMPLATE,
			resourceTemplateUrl : new Template(Global.Url.TEXT_RESOURCE_TEMPLATE_URL).evaluate({mid: this.module.mid})
		});
	},

	onShowPanel : function() {
		$(document.body).observe('click', this.hideCtrl.bindAsEventListener(this));
	},

	onHidePanel : function() {
		$(document.body).stopObserving('click', this.hideCtrl.bindAsEventListener(this));
	},

	hideCtrl : function(event) {
//		Event.stop(event);
		this.hide();
	}
});

ContextMenuPopupPanel.prototype.Config = {
	Id : {
		FILE_OPEN : 'uic-text-resource-fileopen',

		VIEW_QUESTIONS : 'qa-resource-view-questions',
		EDIT : 'qa-resource-edit',
		DELETE : 'qa-resource-delete',
		DEPLOY : 'qa-resource-deploy',
		UNDEPLOY : 'qa-resource-undeploy',
		EXPORT : 'qa-resource-export'
	},
	ClassName : {
		WRAPPER : 'qa-resource-controller-wrapper',
		LIST : 'qa-resource-controller-list'
	}
};

ContextMenuPopupPanel.prototype.Event = {

	uicTextResourceFileopen : function(event) {
		this.hide();
//		var popup = new FileListPopupPanel();
//		popup.module = this.module;
//		popup.showCenter();
//		popup.loadRootDirectory();
//		window.openFileDialog();

		var options = {
//			onOk: this.Event.onFileSelectedHandler.bind(this),
			onOk: this.Event.onDialogOk.bind(this)
		};
		this.fileDialog = new FileSharingDialog('dialogtarget', options);
		this.fileDialog.show();
	},

	onDialogOk: function(item) {
		this.handlers.fileselected(item.id, this.module);
	},

//	onFileSelectedHandler: function(event, fileId) {
//		this.fileDialog.hide();
//	},


	viewQuestionsClicked : function(event) {
		Global.location = this.resource.name;
		this.hide();
		document.fire('state:edit');
	},
	editClicked : function(event) {
		this.hide();
		var popup = new QaEditQaPopupPanel();
		popup.resource = this.resource;
		popup.show();
		popup.onSavePanel = function(languages) {
			popup.resource.languages = languages;
			this.bodyPanel.draw();
		}.bind(this);
	},
	deleteClicked : function(event) {
		this.hide();
		if (!confirm(Global.Text.SURE_DELETE)) {
			return;
		}
		new Ajax.Request(Global.Url.DELETE_RESOURCE, {
			postBody : Object.toQueryString({
				name : this.resource.name
			})
		});
		// リソースを削除しておく
		var index = null;
		this.bodyPanel.resources.each(function(resource, i){
			if (this.resource == resource) {
				index = i;
			}
		}.bind(this));
		this.bodyPanel.resources.splice(index, 1);
		this.bodyPanel.draw();
	},
	deployClicked : function(event) {

	},
	undeployClicked : function(event) {

	},
	exportClicked : function(event) {
		this.hide();
		location.href = Global.Url.EXPORT_RESOURCE + '&name=' + this.resource.name;
	}
};

ContextMenuPopupPanel.prototype.Templates = {
	base : ''
		+ '<div class="qa-resource-controller-wrapper">'
		+ ' <ul class="qa-resource-controller-list">'
		+ '  <li><a href="javascript:void(0);"><span class="qa-common-clickable" id="uic-text-resource-fileopen">#{SELECT_LAB}</span></a></li>'
		+ '  <li style="#{downloadStyle}"><a href="#{downloadurl}" target="_brank"><span class="qa-common-clickable" id="qa-resource-delete">#{DOWNLOAD_LAB}</span></a></li>'
		+ '  <li style="#{resourceTemplateStyle}"><a href="#{resourceTemplateUrl}" target="_brank"><span="qa-common-clickable">#{resourceTemplate}</span></a></li>'
		+ ' </ul>'
		+ '</div>'
};