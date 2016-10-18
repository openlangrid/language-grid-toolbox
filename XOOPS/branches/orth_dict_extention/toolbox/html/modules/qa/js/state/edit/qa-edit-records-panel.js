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
var QaEditRecordsPanel = Class.create();
Object.extend(QaEditRecordsPanel.prototype, Panel.prototype);
Object.extend(QaEditRecordsPanel.prototype, {
	
	id : 'qa-edit-records-panel',

	resource : null,
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
	
	openAllAnswers : function() {
		this.recordPanels.each(function(panel){
			panel.showAnswers();
		});
	},
	
	closeAllAnswers : function() {
		this.recordPanels.each(function(panel){
			panel.hideAnswers();
		});
	},

	init : function() {
		this.recordPanels = [];
		this.buildPager();
		this.buildPerPage();
	},

	set : function(resource, languages, sourceLanguage) {
		this.resource = resource;
		this.languages = languages;
		this.sourceLanguage = sourceLanguage;
		this.targetLanguage = this.languages[0];
		if (this.sourceLanguage == this.targetLanguage) {
			this.targetLanguage = this.languages[1];
		}
		this.buildRecordPanels();
	},

	initEventListeners : function() {

		// 言語変わったとき
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
		var readOnly = (this.resource.meta.permission <= 1);
		this.resource.records.each(function(record, i) {
			var recordPanel = new QaEditRecordPanel();
			recordPanel.set(this.Config.Id.EDIT_RECORD_PANEL_PREFIX + i, record, this.sourceLanguage, this.targetLanguage, this.languages);
			recordPanel.resource = this.resource;
			recordPanel.readOnlyFlag = readOnly;
			recordPanel.parent = this;
			recordPanel.index = i;
			this.recordPanels.push(recordPanel);
		}.bind(this));
		this.pager.totalItems = this.recordPanels.length;
	},
	
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
		return new Template(Global.Text.PAGE_RESULTS).evaluate({
			0 : this.pager.totalItems,
			1 : (this.pager.getLength() != 0) ? this.pager.getStart() + 1 : 0,
			2 : this.pager.getLength()
		});
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
			html.push('<tbody id="' + this.recordPanels[i].id + '">');
			html.push(this.recordPanels[i].createHtml());
			html.push('</tbody>');
		}
		return html.join('');
	},

	createHtml : function() {
		var html = [];
		html.push('<table id="qa-edit-records-table">');
		html.push(this.createHeaderRowHtml());
		html.push(this.createRecordsHtml());
		html.push(this.createFooterHtml());
		html.push('</table>');
		return html.join('');
	},
	
	addRecord : function() {
		if (!!Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		var record = {
			questionId : 0,
			categoryIds : [],
			expressions : {},
			answers : []
		};
		this.resource.records.splice(this.pager.getStart(), 0, record);
		this.buildRecordPanels();
		this.recordPanels[this.pager.getStart()].isNew = true;
		this.recordPanels[this.pager.getStart()].stateManager.setNew();
		Global.recordPanel = this.recordPanels[this.pager.getStart()];
		this.recordPanels[this.pager.getStart()].newFlag = true;
		this.draw();
	},

	deleteRecord : function(id) {
		var index = id.replace(this.Config.Id.EDIT_RECORD_PANEL_PREFIX, '');
		Global.recordPanel = null;
		this.resource.records.splice(index, 1);
		this.buildRecordPanels();
		this.draw();
	},

	deleteNewRecord : function() {
		Global.recordPanel = null;
		this.resource.records.splice(this.pager.getStart(), 1);
		this.buildRecordPanels();
		this.draw();
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
		
//		if (this.recordPanels.length == 0) {
//			this.addRecord();
//		}
	},

	getTotalItems : function() {
		return this.recordPanels.length || 0;
	},

	clear : function() {
		if ($(this.id)) {
			this.update('');
		}
	}
});

QaEditRecordsPanel.prototype.Config = {
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

QaEditRecordsPanel.prototype.Event = {
	sourceLanguageChanged : function(event) {
		this.sourceLanguage = $(this.Config.Id.SOURCE_LANGUAGE_SELECTOR).value;
		if (this.targetLanguage == this.sourceLanguage) {
			this.targetLanguage = this.languages[0];
		}
		if (this.targetLanguage == this.sourceLanguage) {
			this.targetLanguage = this.languages[1];
		}
		this.recordPanels.each(function(recordPanel){
			recordPanel.sourceLanguage = this.sourceLanguage;
			recordPanel.targetLanguage = this.targetLanguage;
		}.bind(this));
		document.fire('source:changed');
		this.draw();
	},
	targetLanguageChanged : function(event) {
		this.targetLanguage = $(this.Config.Id.TARGET_LANGUAGE_SELECTOR).value;
		if (this.targetLanguage == this.sourceLanguage) {
			this.sourceLanguage = this.languages[0];
			document.fire('source:changed');
		}
		if (this.targetLanguage == this.sourceLanguage) {
			this.sourceLanguage = this.languages[1];
			document.fire('source:changed');
		}
		this.recordPanels.each(function(recordPanel){
			recordPanel.sourceLanguage = this.sourceLanguage;
			recordPanel.targetLanguage = this.targetLanguage;
		}.bind(this));
		this.draw();
	},
	pagerClicked : function(event) {
		if (!!Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}

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
		if (!!Global.recordPanel && (Global.recordPanel.index >= this.perPage.getPerPageById(element.id))) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		this.perPage.currentPerPage = this.perPage.getPerPageById(element.id);
		this.pager.currentPage = this.Config.DEFAULT_CURRENT_PAGE;
		this.pager.perPage = this.perPage.currentPerPage;
		this.draw();
	}
};

QaEditRecordsPanel.prototype.State = {
	READY : 0,
	EDIT : 1,
	UNSAVED : 2
};

QaEditRecordsPanel.prototype.Templates = {
	headerRow : '<thead><tr><td> </td><td colspan="2">in #{source}</td><td class="qa-edit-records-target-language-column">in #{target}</td><td class="qa-edit-records-categories-column">#{category}</td><td class="qa-edit-records-delete-column"> </td></tr></thead>'
		+ '<tr class="qa-edit-record-header-line"><td></td><td></td><td class="qa-edit-records-source-language-column"></td><td></td><td></td><td></td>',
//	resourceRow : '<tr><td colspan="6"><span id="#{id}" class="#{className}">'
//				+ '#{contents}</span></td></tr>'
//				+ '<tbody id="#{recordsWrapperId}">',
	Footer : {
		body : '<tr class="qa-resource-table-footer">'
			+ '<td colspan="6">'
			+ '<div class="qa-resource-table-footer-result">'
			+ '<div class="float-left qa-common-pager-results-area">#{results}</div>'
			+ '<div class="float-left qa-common-pager-items-area">#{items}</div>'
			+ '</div>'
			+ '<div class="qa-resource-table-footer-pager qa-common-pager-area"><div class="float-right">#{pager}</div></div>'
			+ '</td></tr>',
		header : '',
		footer : ''
	}
};