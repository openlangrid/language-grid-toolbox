//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// translation templates.
// Copyright (C) 2010  Department of Social Informatics, Kyoto University
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
var QaSearchRecordsPanel = Class.create();
Object.extend(QaSearchRecordsPanel.prototype, QaEditRecordsPanel.prototype);
Object.extend(QaSearchRecordsPanel.prototype, {
	
	id : 'qa-search-records-panel',

	resources : null,

	initEventListeners : function() {
		QaEditRecordsPanel.prototype.initEventListeners.apply(this, arguments);
		this.getVisibleResources().each(function(resource){
			this.addEvent(resource.name + 'Clicked', this.getToggleId(resource.name), 'click', this.resourceNameClicked.bindAsEventListener(this, resource));
		}.bind(this));
	},
	
	getVisibleResources : function() {
		var resources = [];
		var resource = null;
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			if (resource != this.recordPanels[i].resource) {
				resource = this.recordPanels[i].resource;
				resources.push(resource);
			}
		}
		return resources;
	},

	set : function(resources, languages, sourceLanguage) {
		this.resources = resources;
		this.languages = languages;
		this.sourceLanguage = sourceLanguage;
		this.targetLanguage = this.languages[0];
		if (this.sourceLanguage == this.targetLanguage) {
			this.targetLanguage = this.languages[1];
		}
		this.buildRecordPanels();
	},

	buildRecordPanels : function() {
		this.recordPanels = [];
		var count = 0;
		this.resources.each(function(resource){
			var readOnly = true;
			resource.records.each(function(record) {
				var recordPanel = new QaSearchRecordPanel();
				recordPanel.set(this.Config.Id.EDIT_RECORD_PANEL_PREFIX + count, record, this.sourceLanguage, this.targetLanguage, this.languages);
				recordPanel.resource = resource;
				recordPanel.readOnlyFlag = readOnly;
				recordPanel.parent = this;
				recordPanel.index = count;
				this.recordPanels.push(recordPanel);
				count++;
			}.bind(this));
		}.bind(this));
		this.pager.totalItems = this.recordPanels.length;
	},
	
	createRecordsHtml : function() {
		var html = [];
		var resource = null;
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			if (resource != this.recordPanels[i].resource) {
				resource = this.recordPanels[i].resource;
				html.push(new Template('<tr><td class="resource-name" colspan="6"><span id="#{id}" class="#{className}">#{resourceName}</span></td></tr>').evaluate({
					id : this.getToggleId(resource.name),
					className : Global.ClassName.CLICKABLE_TEXT + ' ' + Global.ClassName.CLOSABLE,
					resourceName : new Template(Global.Text.RESOURCE_NAME_WITH_RESULTS).evaluate({
						0 : resource.name,
						1 : this.recordPanels[i].resource.records.length
					})
				}));
			}
			html.push('<tbody class="qa-resource-'+resource.name+'" id="' + this.recordPanels[i].id + '">');
			html.push(this.recordPanels[i].createHtml());
			html.push('</tbody>');
		}
		return html.join('');
	},

	createHtml : function() {
		var html = [];
		if (this.results > 0) {
			html.push('<table id="qa-search-records-table">');
			html.push(this.createHeaderRowHtml());
			html.push(this.createRecordsHtml());
			html.push(this.createFooterHtml());
			html.push('</table>');
		}
		return html.join('');
	},

	deleteRecord : function(id) {
		var index = id.replace(this.Config.Id.EDIT_RECORD_PANEL_PREFIX, '');
		Global.recordPanel = null;
		var count = 0;
		this.resources.each(function(resource){
			if (count <= index && index < count + resource.records.length) {
				resource.records.splice(index - count, 1);
				throw $break;
			}
			count += resource.records.length;
		});
		this.buildRecordPanels();
		this.draw();
	},

	TOGGLE_ID_ : 'qa-resource-toggle-aaa-',

	getToggleId : function(id) {
		return this.TOGGLE_ID_ + id;
	},

	getNameByToggleId : function(id) {
		return id.replace(this.TOGGLE_ID_, '');
	},
	
	toggleRecords : function(resource, operation) {
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			if (resource == this.recordPanels[i].resource) {
				this.recordPanels[i][operation]();
			}
		}
	},

	draw : function() {
		this.stopEventObserving();
		this.update(this.createHtml());
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			this.recordPanels[i].stopEventObserving();
			if (this.recordPanels[i].isAnswersOpen()) {
				this.recordPanels[i].showAnswers();
			}
			if (this.recordPanels[i].stateManager.isChanged()) {
				this.recordPanels[i].setUnsaved();
			}
			this.recordPanels[i].store();
			this.recordPanels[i].initEventListeners();
			this.recordPanels[i].startEventObserving();
		}
		this.initEventListeners();
		this.startEventObserving();
	},

	openRecords : function(resource) {
		$(this.getToggleId(resource.name)).removeClassName(Global.ClassName.OPENABLE);
		$(this.getToggleId(resource.name)).addClassName(Global.ClassName.CLOSABLE);
		this.toggleRecords(resource, 'show');
	},
	
	closeRecords : function(resource) {
		if (Global.recordPanel && Global.recordPanel.resource == resource) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		$(this.getToggleId(resource.name)).addClassName(Global.ClassName.OPENABLE);
		$(this.getToggleId(resource.name)).removeClassName(Global.ClassName.CLOSABLE);
		this.toggleRecords(resource, 'hide');
	},
	
	resourceNameClicked : function(event, resource) {
		var operation = 'closeRecords';
		if ($(this.getToggleId(resource.name)).hasClassName(Global.ClassName.OPENABLE)) {
			operation = 'openRecords';
		}
		this[operation](resource);
	}
});