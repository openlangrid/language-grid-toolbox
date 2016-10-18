//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
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
var QaResourceConrollerPopupPanel = Class.create();
Object.extend(QaResourceConrollerPopupPanel.prototype, PopupPanel.prototype);
Object.extend(QaResourceConrollerPopupPanel.prototype, {
	
	WIDTH : '150',
	
	resource : null,
	bodyPanel : null,
	
	initialize : function() {
		PopupPanel.prototype.initialize.apply(this, arguments);
	},
	
	initEventListeners : function() {
		this.addEvent('viewQuestionsClicked', this.Config.Id.VIEW_QUESTIONS, 'click', this.Event.viewQuestionsClicked.bindAsEventListener(this));
		this.addEvent('editClicked', this.Config.Id.EDIT, 'click', this.Event.editClicked.bindAsEventListener(this));
		this.addEvent('deleteClicked', this.Config.Id.DELETE, 'click', this.Event.deleteClicked.bindAsEventListener(this));
		this.addEvent('deployClicked', this.Config.Id.DEPLOY, 'click', this.Event.deployClicked.bindAsEventListener(this));
		this.addEvent('undeployClicked', this.Config.Id.UNDEPLOY, 'click', this.Event.undeployClicked.bindAsEventListener(this));
		this.addEvent('exportClicked', this.Config.Id.EXPORT, 'click', this.Event.exportClicked.bindAsEventListener(this));
	},

	getBody : function() {
		return new Template(this.Templates.base).evaluate({
			divClassName : this.Config.ClassName.WRAPPER,
			ulClassName : this.Config.ClassName.LIST,
			className : Global.ClassName.CLICKABLE_TEXT,
			viewQuestionsId : this.Config.Id.VIEW_QUESTIONS,
			viewQuestions : Global.Text.VIEW_QUESTIONS,
			editAttribute : (this.resource.meta.permission >= Global.Permission.EDIT) ? '' : 'style="display: none;"',
			editId : this.Config.Id.EDIT,
			edit : Global.Text.EDIT,
			suAttribute : (this.resource.meta.permission >= Global.Permission.SU) ? '' : 'style="display: none;"',
			deleteId : this.Config.Id.DELETE,
			'delete' : Global.Text.DELETE,
			deployId : this.Config.Id.DEPLOY,
			deploy : Global.Text.DEPLOY,
			undeployId : this.Config.Id.UNDEPLOY,
			undeploy : Global.Text.UNDEPLOY,
			exportId : this.Config.Id.EXPORT,
			'export' : Global.Text.EXPORT
		});
	}
});

QaResourceConrollerPopupPanel.prototype.Config = {
	Id : {
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

QaResourceConrollerPopupPanel.prototype.Event = {
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

QaResourceConrollerPopupPanel.prototype.Templates = {
	base : '<div class="#{divClassName}">'
		 + '<ul class="#{ulClassName}">'
		 + '<li><span id="#{viewQuestionsId}" class="#{className}">#{viewQuestions}</span></li>'
		 + '<li #{editAttribute}><span id="#{editId}" class="#{className}">#{edit}</span></li>'
		 + '<li #{suAttribute}><span id="#{deleteId}" class="#{className}">#{delete}</span></li>'
		 + '<li style="display: none;"><span id="#{deployId}" class="#{className}">#{deploy}</span></li>'
		 + '<li style="display: none;"><span id="#{undeployId}" class="#{className}">#{undeploy}</span></li>'
		 + '<li><span id="#{exportId}" class="#{className}">#{export}</span></li>'
		 + '</ul>'
		 + '</div>'
};