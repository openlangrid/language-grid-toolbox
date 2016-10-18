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
var QaEditRecordsWithMultiResourcesPanel = Class.create();
Object.extend(QaEditRecordsWithMultiResourcesPanel.prototype, Panel.prototype);
Object.extend(QaEditRecordsWithMultiResourcesPanel.prototype, {
	
	id : 'qa-edit-records-panel',

	resource : null,
//	resources : null,
	recordPanels : null,

	pager : null,
	perPage : null,

	results : null,

	sourceLanguage : null,
	targetLanguage : null,

	languages : null,

	state : null, // READY, EDIT, UNSAVED

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.state = this.State.READY;
		this.init();
	},

	init : function() {
//		this.resources = [];
		this.recordPanels = [];
		this.buildPager();
		this.buildPerPage();
	},
	
	set : function(resource, languages, sourceLanguage, targetLanguage) {
		this.resource = resource;
		this.languages = languages;
		this.sourceLanguage = sourceLanguage;
		this.targetLanguage = targetLanguage;
		this.buildRecordPanels();
	},

	initEventListeners : function() {
//		var resourceName = null;
//		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
//			if (resourceName != this.recordPanels[i].resource.name) {
//				resourceName = this.recordPanels[i].resource.name;
//				this.addEvent('toggleEvent' + resourceName, (this.getResourceToggleButtonId(resourceName)), 'click', this.Event.resourceToggleButtonClicked.bindAsEventListener(this));
//			}
//		}
//		this.addEvent('resourceAllOpenEvent',this.Config.Id.OPEN, 'click', this.Event.allResourcesOpenButtonClicked.bindAsEventListener(this));
//		this.addEvent('resourceAllCloseEvent',this.Config.Id.CLOSE, 'click', this.Event.allResourcesCloseButtonClicked.bindAsEventListener(this));

		this.addEvent('sourceLanguageChangeEvent', this.Config.Id.SOURCE_LANGUAGE_SELECTOR, 'change', this.Event.sourceLanguageChanged.bindAsEventListener(this));
		this.addEvent('targetLanguageChangeEvent', this.Config.Id.TARGET_LANGUAGE_SELECTOR, 'change', this.Event.targetLanguageChanged.bindAsEventListener(this));

		// ページャーのイベントセット
		$$('.' + this.pager.commonClassName).each(function(pagerElement){
			this.addEvent(pagerElement.id, pagerElement, 'click', this.Event.pagerClicked.bindAsEventListener(this));
		}.bind(this));

		// itemのイベント
		$$('.' + this.perPage.className).each(function(perPageElement){
			this.addEvent(perPageElement.id, perPageElement, 'click', this.Event.perPageClicked.bindAsEventListener(this));
		}.bind(this));
	},
	
	buildPager : function() {
		this.pager = new Pager();
		this.pager.pagerIdPrefix = this.Config.Id.PAGER_ELEMENT_PREFIX;
		this.pager.commonClassName = this.Config.ClassName.PAGER_COMMON;
		this.pager.clickableClassName =Global.ClassName.CLICKABLE_TEXT;
		this.pager.disableClassName =Global.ClassName.DISABLE_TEXT;
		this.pager.previewId = this.Config.Id.PAGER_PREVIEW;
		this.pager.nextId = this.Config.Id.PAGER_NEXT;
		this.pager.currentPage = this.Config.DEFAULT_CURRENT_PAGE;
		this.pager.perPage = this.Config.DEFAULT_ITEMS_PER_PAGE;
	},

	buildPerPage : function() {
		this.perPage = new PerPage();
		this.perPage.prefix = this.Config.Id.PER_PAGE_PREFIX;
		this.perPage.currentPerPage = this.Config.DEFAULT_ITEMS_PER_PAGE;
		this.perPage.className = this.Config.ClassName.PER_PAGE_COMMON;
	},
	
	buildRecordPanels : function() {
		this.recordPanels = [];
		var i = 0;
//		this.resources.each(function(resource) {
			this.resource.records.each(function(record) {
				var recordPanel = new QaEditRecordPanel();
				recordPanel.set(this.Config.Id.EDIT_RECORD_PANEL_PREFIX + i, record, this.sourceLanguage, this.targetLanguage);
				recordPanel.resource = this.resource;
				this.recordPanels.push(recordPanel);
				i++;
			}.bind(this));
//		}.bind(this));
		this.pager.totalItems = i;
	},
//	createResourceControllerHtml : function() {
//		return new Template(this.Templates.resourcesController).evaluate({
//			all : Global.Text.ALL_RESOURCES,
//			openId : this.Config.Id.OPEN,
//			openClassName : Global.ClassName.CLICKABLE_TEXT,
//			open : Global.Text.OPEN,
//			closeId : this.Config.Id.CLOSE,
//			closeClassName : Global.ClassName.CLICKABLE_TEXT,
//			close : Global.Text.CLOSE,
//		});
//	},
	createLanguageSelectorHtml : function(id, selectedLanguage) {
		var html = [];
		html.push(new Template(Global.Templates.LIST_BOX.HEADER).evaluate({
			id : id
		}));
		var attribute;
		LanguageUtils.sort(this.languages);
		this.languages.each(function(language){
			attribute = (language == selectedLanguage)
						? ' selected="selected" ' : '';
			html.push(new Template(Global.Templates.LIST_BOX.BODY).evaluate({
				value : language,
				attribute : attribute,
				name : Global.Language[language]
			}));
		}.bind(this));
		html.push(Global.Templates.LIST_BOX.FOOTER);
		
		return html.join('');
	},

	createHeaderRowHtml : function() {
		return new Template(this.Templates.headerRow).evaluate({
			source : this.createLanguageSelectorHtml(this.Config.Id.SOURCE_LANGUAGE_SELECTOR, this.sourceLanguage),
			target : this.createLanguageSelectorHtml(this.Config.Id.TARGET_LANGUAGE_SELECTOR, this.targetLanguage),
			category : Global.Text.CATEGORY
		});
	},
	
	createResultsHtml : function() {
		return '';
	},
	
	createPagerHtml : function() {
		this.pager.totalItems = this.recordPanels.length;
		this.pager.perPage = this.perPage.currentPerPage;
		return this.pager.getHtml();
	},
	
	createFooterHtml : function() {
		var html = [];
		html.push(this.Templates.Footer.header);
		html.push(new Template(this.Templates.Footer.body).evaluate({
			results : this.createResultsHtml(),
			items : this.perPage.createHtml(),
			pager : this.createPagerHtml()
		}));
		html.push(this.Templates.Footer.footer);
		return html.join('');
	},

	createRecordsHtml : function() {
		var html = [];
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			html.push(this.recordPanels[i].createHtml());
		}
		return html.join('');
	},

//	createRecordsHtml : function() {
//		var html = [];
//		var resourceName = null;
//		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
//			if (resourceName != this.recordPanels[i].resource.name) {
//				html.push('</tbody>');
//				resourceName = this.recordPanels[i].resource.name;
//				var contents = Global.Text.RESOURCE_NAME_WIDTH_RESULTS.replace('#{0}', resourceName);
//				contents = contents.replace('#{1}', this.recordPanels[i].resource.records.length);
//				html.push(new Template(this.Templates.resourceRow).evaluate({
//					id : this.getResourceToggleButtonId(resourceName),
//					className : Global.ClassName.CLICKABLE_TEXT,
//					contents : contents,
//					recordsWrapperId : this.getRecordsWrapperId(resourceName)
//				}));
//			}
//			html.push(this.recordPanels[i].createHtml());
//		}
//		return html.join('');
//	},

	createHtml : function() {
		var html = [];
//		html.push(this.createResourceControllerHtml());
		html.push('<table border="1">');
		html.push(this.createHeaderRowHtml());
		html.push(this.createRecordsHtml());
		html.push('</table>');
		html.push(this.createFooterHtml());
		return html.join('');
	},

	draw : function() {
		this.stopEventObserving();
		this.update(this.createHtml());
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			this.recordPanels[i].stopEventObserving();
			this.recordPanels[i].initEventListeners();
			this.recordPanels[i].startEventObserving();
		}
		this.initEventListeners();
		this.startEventObserving();
	},
	
	getDisplayedResourceNames : function() {
		var resourceNames = [];
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			if (resourceNames.last() != this.recordPanels[i].resource.name) {
				resourceNames.push(this.recordPanels[i].resource.name);
			}
		}
		return resourceNames;
	},

//	showAllResources : function() {
//		this.getDisplayedResourceNames().each(function(resourceName){
//			this.showRecords(resourceName);
//		}.bind(this));
//	},
//	
//	hideAllResources : function() {
//		this.getDisplayedResourceNames().each(function(resourceName){
//			this.hideRecords(resourceName);
//		}.bind(this));
//	},
	
//	showRecords : function(resourceName) {
//		$(this.getRecordsWrapperId(resourceName)).show();
//	},
//
//	hideRecords : function(resourceName) {
//		$(this.getRecordsWrapperId(resourceName)).hide();
//	},

//	getRecordsWrapperId : function(id) {
//		return this.Config.Id.RECORDS_WRAPPER_PREFIX + id;
//	},
	
//	getResourceToggleButtonId : function(id) {
//		return this.Config.Id.RESOURCE_TOGGLE_BUTTON_PREFIX + id;
//	},
	getTotalItems : function() {
		return this.recordPanels.length || 0;
	}
//	isReady : function() {
//		return (this.state == this.State.READY);
//	},
//	isFinish : function() {
//		return (this.state == this.State.FINISH);
//	}
});

QaEditRecordsWithMultiResourcesPanel.prototype.Config = {
	DEFAULT_ITEMS_PER_PAGE : 10,
	DEFAULT_CURRENT_PAGE : 0,
	Id : {
		OPEN : 'qa-search-open',
		CLOSE : 'qa-search-close',
		EDIT_RECORD_PANEL_PREFIX : 'qa-search-edit-record-panel-',
		PAGER_ELEMENT_PREFIX : 'qa-search-pager-element-',
		PAGER_PREVIEW : 'qa-search-pager-preview',
		PAGER_NEXT : 'qa-search-pager-next',
		PER_PAGE_PREFIX : 'qa-search-items-per-page-',
		RESOURCE_TOGGLE_BUTTON_PREFIX : 'qa-search-resource-toggle-button-',
		RECORDS_WRAPPER_PREFIX : 'qa-search-records-wrapper-',
		SOURCE_LANGUAGE_SELECTOR : 'qa-search-records-source-language-selector',
		TARGET_LANGUAGE_SELECTOR : 'qa-search-records-target-language-selector'
	},
	ClassName : {
		PAGER_COMMON : 'qa-search-pager-common',
		PER_PAGE_COMMON : 'qa-search-items-per-page-common',
		RESOURCE_HIDE : 'qa-search-resource-hide'
	}
};

QaEditRecordsWithMultiResourcesPanel.prototype.Event = {
//	resourceToggleButtonClicked : function(event) {
//		var element = Event.element(event);
//		var resourceName = element.id.replace(this.Config.Id.RESOURCE_TOGGLE_BUTTON_PREFIX, '');
//		if (!$(this.getRecordsWrapperId(resourceName)).visible()) {
//			this.showRecords(resourceName);
//		} else {
//			this.hideRecords(resourceName);
//		}
//	},
//	allResourcesOpenButtonClicked : function(event) {
//		this.showAllResources();
//	},
//	allResourcesCloseButtonClicked : function(event) {
//		this.hideAllResources();
//	},
	sourceLanguageChanged : function(event) {
		this.sourceLanguage = $(this.Config.Id.SOURCE_LANGUAGE_SELECTOR).value;
		if (this.targetLanguage == this.sourceLanguage && this.languages[0] != this.sourceLanguage) {
			this.targetLanguage = this.languages[0];
		} else {
			this.targetLanguage = this.languages[1];
		}
		this.buildRecordPanels();
		this.draw();
	},
	targetLanguageChanged : function(event) {
		this.targetLanguage = $(this.Config.Id.TARGET_LANGUAGE_SELECTOR).value;
		if (this.targetLanguage == this.sourceLanguage && this.languages[0] != this.targetLanguage) {
			this.sourceLanguage = this.languages[0];
		} else {
			this.sourceLanguage = this.languages[1];
		}
		this.buildRecordPanels();
		this.draw();
	},
	pagerClicked : function(event) {
		var pagerElement = Event.element(event);

		// disabled
		if (pagerElement.hasClassName(Global.ClassName.DISABLE_TEXT)) {
			return;
		}

		switch (pagerElement.id) {
		case this.Config.Id.PAGER_PREVIEW:
			this.pager.currentPage--;
			break;
		case this.Config.Id.PAGER_NEXT:
			this.pager.currentPage++;
			break;
		default:
			this.pager.currentPage = pagerElement.id.replace(this.Config.Id.PAGER_ELEMENT_PREFIX, '');
			break;
		}
		this.draw();
	},
	
	/**
	 * 5 | 10 | 25とかが押されたとき
	 */
	perPageClicked : function(event) {
		var element = Event.element(event);
		this.perPage.currentPerPage = this.perPage.getPerPageById(element.id);
		this.pager.currentPage = this.Config.DEFAULT_CURRENT_PAGE;
		this.pager.perPage = this.perPage.currentPerPage;
		this.draw();
	}
};

QaEditRecordsWithMultiResourcesPanel.prototype.State = {
	READY : 0,
	EDIT : 1,
	UNSAVED : 2
};

QaEditRecordsWithMultiResourcesPanel.prototype.Templates = {
	resourcesController : '#{all} '
		+ '<span id="#{openId}" class="#{openClassName}">#{open}</span>'
		+ ' | <span id="#{closeId}" class="#{closeClassName}">#{close}</span>',
	headerRow : '<tr><td> </td><td colspan="2">in #{source}</td><td>in #{target}</td><td colspan="2">#{category}</td></tr>',
	resourceRow : '<tr><td colspan="6"><span id="#{id}" class="#{className}">'
				+ '#{contents}</span></td></tr>'
				+ '<tbody id="#{recordsWrapperId}">',
	Footer : {
		header : '<table>',
		body : '<tr><td>#{results}</td>'
				+ '<td>#{items}</td>'
				+ '<td>#{pager}</td></tr>',
		footer : '</table>'
	}
};