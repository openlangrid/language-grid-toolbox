//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// Q&As.
// Copyright (C) 2010  CITY OF KYOTO
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
/**
 * @author kitajima
 */
var QaEditRecordDeleteRecordPopupPanel = Class.create();
Object.extend(QaEditRecordDeleteRecordPopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(QaEditRecordDeleteRecordPopupPanel.prototype, {

	question : null,
	questionId : null,
	errorMessage : null,
	WIDTH : '480',

	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEvent('okClicked', this.Config.Id.OK, 'click', this.Event.okClicked.bindAsEventListener(this));
		this.addEvent('cancelClicked', this.Config.Id.CANCEL, 'click', this.Event.cancelClicked.bindAsEventListener(this));
	},

	getBody : function() {
		return 	new Template(this.Templates.base).evaluate({
			title : Global.Text.DELETE_RECORD,
			type : Global.Text.TYPE,
			question : this.question,
			qa : Global.Text.QA,
			confirmDeleteResource : Global.Text.SURE_DELETE,
			statusAreaId : this.Config.Id.STATUS_AREA,
			okId : this.Config.Id.OK,
			ok : Global.Text.OK,
			cancelId : this.Config.Id.CANCEL,
			cancel : Global.Text.CANCEL
		});
	},

	getParameters : function() {
		var parameters = {
			questionId : this.questionId
		};
		return parameters;
	},

	submit : function() {
		this.setOkCancelButtonDisabled();
		this.setStatus(Global.Image.LOADING + ' ' + Global.Text.NOW_DELETING);
		new Ajax.Request(Global.Url.DELETE_RECORD, {
			postBody : Object.toQueryString(this.getParameters()),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.hide();
			}.bind(this),
			onException : function() {
				
			},
			onFailure : function() {
				
			}
		});
	},
	onShowPane : function() {
		
	},
	
	onHidePane : function() {
		
	},
	
	setOkCancelButtonDisabled : function() {
		$(this.Config.Id.OK).addClassName(this.Config.ClassName.BUTTON_DISABLED);
		$(this.Config.Id.CANCEL).addClassName(this.Config.ClassName.BUTTON_DISABLED);
	},
	setStatus : function(message) {
		$(this.Config.Id.STATUS_AREA).update(message);
	}
});
QaEditRecordDeleteRecordPopupPanel.prototype.Config = {
	Id : {
		RESOURCE_NAME_INPUT : 'qa-resource-name-input',
		EDIT_PERMISSION : 'qa-resource-edit-permission',
		READ_PERMISSION : 'qa-resource-read-permission',
		STATUS_AREA : 'qa-popup-common-status-area',
		OK : 'qa-resource-submit',
		CANCEL : 'qa-resource-cancel'
	},
	ClassName : {
		BUTTON_DISABLED : 'qa-common-popup-gray-button-disabled',
		LANGUAGE_CHECK_BOX : 'qa-resource-langauge-checkbox'
	}
};
QaEditRecordDeleteRecordPopupPanel.prototype.Event = {
	editPermissionChanged : function(event) {
		$(this.Config.Id.READ_PERMISSION).update(this.createReadPermissionSelectorHtml());
	},
	okClicked : function(event) {
		if ($(this.Config.Id.OK).hasClassName(this.Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		this.submit();
	},
	cancelClicked : function(event) {
		if ($(this.Config.Id.CANCEL).hasClassName(this.Config.ClassName.BUTTON_DISABLED)) {
			return;
		}
		this.hide();
	}
};
QaEditRecordDeleteRecordPopupPanel.prototype.Templates = {
	base : '<div class="qa-common-popup-wrapper">'
		 + '<div class="qa-common-popup-title">#{title}</div>'
		 + '<table class="qa-common-popup-table">'
		 + '<tr><td colspan="2"><b>Q: #{question}</b></td></tr>'
		 + '<tr><td colspan="2"><p>#{confirmDeleteResource}</p></td></tr>'
		 + '</table>'
		 + '<div id="#{statusAreaId}"></div>'
		 + '<div>'
		 + '<button id="#{okId}" class="qa-common-popup-gray-button">#{ok}</button>'
		 + '<button id="#{cancelId}" class="qa-common-popup-gray-button">#{cancel}</button>'
		 + '</div>'
		 + '</div>'
};