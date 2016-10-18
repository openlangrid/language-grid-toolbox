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
var QaResourcesBodyPanel = Class.create();
Object.extend(QaResourcesBodyPanel.prototype, Panel.prototype);
Object.extend(QaResourcesBodyPanel.prototype, {

	id : 'qa-resources-body-panel',
	
	DEFAULT_CURRENT_PAGE : 0,
	DEFAULT_PER_PAGE : 10,

	resources : null,
	pager : null,
	perPage : null,
	tableSorter : null,

	popup : null,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.init();
	},
	
	init : function() {
		this.tableSorter = new QaResourcesBodyPanelTableSorter();
		this.buildPager();
		this.buildPerPage();
	},

	buildPager : function() {
		this.pager = new Pager();
		this.pager.pagerIdPrefix = this.Config.Id.PAGER_ELEMENT_PREFIX;
		this.pager.commonClassName = this.Config.ClassName.PAGER_COMMON;
		this.pager.clickableClassName =Global.ClassName.CLICKABLE_TEXT;
		this.pager.disableClassName =Global.ClassName.DISABLE_TEXT;
		this.pager.previewId = this.Config.Id.PAGER_PREVIEW;
		this.pager.nextId = this.Config.Id.PAGER_NEXT;
		this.pager.currentPage = this.DEFAULT_CURRENT_PAGE;
		this.pager.perPage = this.DEFAULT_PER_PAGE;
	},

	buildPerPage : function() {
		this.perPage = new PerPage();
		this.perPage.prefix = this.Config.Id.PER_PAGE_PREFIX;
		this.perPage.currentPerPage = this.DEFAULT_PER_PAGE;
		this.perPage.className = this.Config.ClassName.PER_PAGE_COMMON;
	},
		
	initEventListeners : function() {

		// リソース名
		$$('.' + this.Config.ClassName.READBLE_RESOURCE_NAME).each(function(element, i){
			this.addEvent('resourceClickEvent' + i, element, 'click', this.Event.resourceClicked.bindAsEventListener(this));
		}.bind(this));
		
		// メニューボタンのイベントセット
		$$('.' + this.Config.ClassName.MENU_BUTTON).each(function(buttonElement){
			this.addEvent(buttonElement.id, buttonElement, 'click', this.Event.openMenuButtonClicked.bindAsEventListener(this));
		}.bind(this));
		
		// ページャーのイベントセット
		$$('.' + this.pager.commonClassName).each(function(pagerElement){
			this.addEvent(pagerElement.id, pagerElement, 'click', this.pagerClicked.bindAsEventListener(this));
		}.bind(this));

		// itemのイベント
		$$('.' + this.perPage.className).each(function(perPageElement){
			this.addEvent(perPageElement.id, perPageElement, 'click', this.perPageClicked.bindAsEventListener(this));
		}.bind(this));

		// テーブルヘッダのイベント
//		$$('#' + this.Config.Id.TABLE + ' thead th span').each(function(headerElement){
//			this.addEvent(headerElement.id, headerElement, 'click', this.tableHeaderClicked.bindAsEventListener(this));
//		}.bind(this));

		this.addEvent('sortByNameClicked', this.Config.Id.TABLE_HEADER_NAME, 'click', this.Event.sortByNameClicked.bindAsEventListener(this));
		this.addEvent('sortByLanguageClicked', this.Config.Id.TABLE_HEADER_LANGUAGE, 'click', this.Event.sortByLanguageClicked.bindAsEventListener(this));
		this.addEvent('sortByReadPermissionClicked', this.Config.Id.TABLE_HEADER_READ_PERMISSION, 'click', this.Event.sortByReadPermissionClicked.bindAsEventListener(this));
		this.addEvent('sortByEditPermissionClicked', this.Config.Id.TABLE_HEADER_EDIT_PERMISSION, 'click', this.Event.sortByEditPermissionClicked.bindAsEventListener(this));
//		this.addEvent('sortByServiceClicked', this.Config.Id.TABLE_HEADER_SERVICE, 'click', this.Event.sortByServiceClicked.bindAsEventListener(this));
		this.addEvent('sortByCreatorClicked', this.Config.Id.TABLE_HEADER_CREATOR, 'click', this.Event.sortByCreatorClicked.bindAsEventListener(this));
		this.addEvent('sortByLastUpdateClicked', this.Config.Id.TABLE_HEADER_LAST_UPDATE, 'click', this.Event.sortByLastUpdateClicked.bindAsEventListener(this));
		this.addEvent('sortByEntriesClicked', this.Config.Id.TABLE_HEADER_ENTRIES, 'click', this.Event.sortByEntriesClicked.bindAsEventListener(this));
	},
	
	hidePopup : function() {
		if (!!this.popup) {
			this.popup.hide();
			this.popup = null;
		}
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
		this.pager.currentPage = this.DEFAULT_CURRENT_PAGE;
		this.draw();
	},

	/**
	 * リソース一覧を取得してくる
	 */
	load : function() {
		this.setLoading();
		new Ajax.Request(Global.Url.LOAD_RESOURCES, {
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				this.resources = response.contents.resources;
				this.resources.sort(function(a, b) {
					if (a.meta.updateTime == b.meta.updateTime) {
						return 0;
					}
					return (a.meta.updateTime > b.meta.updateTime) ? -1 : 1;
				});
				this.draw();
			}.bind(this),
			onFailure : function() {
			},
			onException : function() {
			}
		});
	},

	/**
	 * 
	 * @param {Object} loading
	 */
	setLoading : function() {
		this.update(Global.Image.LOADING + ' ' + Global.Text.NOW_LOADING);
	},
	
	/**
	 * 
	 * @param {Object} message
	 */
	setError : function(message) {
		this.update('<span style="color: red;">' + message + '</span>');
	},
	
	/**
	 * 
	 */
	translateResource2TemplateObject : function(resource, odd) {

		var languages = [];
		LanguageUtils.sort(resource.languages);
		resource.languages.each(function(language) {
			languages.push(Global.Language[language]);
		});

		return {
			rowClassName : (!!odd) ? this.Config.ClassName.ODD_ROW: this.Config.ClassName.EVEN_ROW,
			resourceId : this.getResourceButtonId(resource.name),
			resourceClassName : (resource.meta.permission >= Global.Permission.READ)
					?Global.ClassName.CLICKABLE_TEXT + ' ' + this.Config.ClassName.READBLE_RESOURCE_NAME : '',
			name : resource.name,
			language : languages.join(', '),
			readPermission : (resource.meta.permission >= Global.Permission.READ)
							 ?Global.Image.CHECK :Global.Image.BLANK,
			editPermission : (resource.meta.permission >= Global.Permission.EDIT)
							 ?Global.Image.CHECK :Global.Image.BLANK,
			service : (!!resource.deploy)
							 ?Global.Image.CHECK :Global.Image.BLANK,
			creator : resource.creator.name,
			lastUpdate : resource.meta.updateDate,
			entries : resource.meta.entries,
			menuButtonAttribute : (resource.meta.permission >= Global.Permission.READ) ? '' : ' style="display: none;" ',
			menuButtonClassName : this.Config.ClassName.MENU_BUTTON,
			menuButtonId : this.getMenuButtonId(resource.name)
		};
	},
	
	createPagerHtml : function() {
		this.pager.totalItems = this.resources.length;
		this.pager.perPage = this.perPage.currentPerPage;
		return this.pager.getHtml();
	},

	createResultsHtml : function() {
		return new Template(Global.Text.PAGE_RESULTS).evaluate({
			0 : this.pager.totalItems,
			1 : (this.pager.getLength() != 0) ? this.pager.getStart() + 1 : 0,
			2 : this.pager.getLength()
		});
	},
	
	sort : function(key) {
		if (this.tableSorter.key == key) {
			this.tableSorter.changeOrder();
		} else {
			this.tableSorter.order = 'Asc';
		}
		this.tableSorter.resources = this.resources;
		this.tableSorter.key = key;
		this.tableSorter.sort();
		this.pager.currentPage = this.DEFAULT_CURRENT_PAGE;
		this.draw();
	},
	
	/**
	 * @return {String} HTML
	 */
	createHtml : function() {

		var html = [];
		// TODO これ、動的に作る必要ない
		html.push(new Template(this.Templates.Table.header).evaluate({
			tableId : this.Config.Id.TABLE,
			name : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_NAME,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.NAME
			}),
			language : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_LANGUAGE,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.LANGUAGE
			}),
			readPermission : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_READ_PERMISSION,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.READ_PERMISSION_SHORT
			}),
			editPermission : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_EDIT_PERMISSION,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.EDIT_PERMISSION_SHORT
			}),
			service : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_SERVICE,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.SERVICE
			}),
			creator : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_CREATOR,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.CREATOR
			}),
			lastUpdate : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_LAST_UPDATE,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.LAST_UPDATE
			}),
			entries : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_ENTRIES,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Text.ENTRIES
			})
		}));

		var odd = 0;
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			html.push(new Template(this.Templates.Table.body).evaluate(
				this.translateResource2TemplateObject(this.resources[i], odd)
			));
			odd = 1 - odd;
		}
		html.push(new Template(this.Templates.Table.footer).evaluate({
			results : this.createResultsHtml(),
			items : this.perPage.createHtml(),
			pager : this.createPagerHtml()
		}));
		html.push('<div style="margin-top: 8px;"><a class="qa-common-return2top" href="#">' + Global.Text.RETURN_TO_TOP + '</a></div>');
		return html.join('');
	},
	
	drawSortAllow : function() {
		if (!this.tableSorter.key || !this.tableSorter.order) {
			return;
		}
		var allow = (this.tableSorter.order == 'Desc') ? '↓' : '↑';
		var element = null;
		var contents = null;
		switch (this.tableSorter.key) {
		case 'Name':
			element = this.Config.Id.TABLE_HEADER_NAME;
			contents = Global.Text.NAME;
			break;
		case 'Language':
			element = this.Config.Id.TABLE_HEADER_LANGUAGE;
			contents = Global.Text.LANGUAGE;
			break;
		case 'ReadPermission':
			element = this.Config.Id.TABLE_HEADER_READ_PERMISSION;
			contents = Global.Text.READ_PERMISSION_SHORT;
			break;
		case 'EditPermission':
			element = this.Config.Id.TABLE_HEADER_EDIT_PERMISSION;
			contents = Global.Text.EDIT_PERMISSION_SHORT;
			break;
		case 'Service':
			element = this.Config.Id.TABLE_HEADER_SERVICE;
			contents = Global.Text.SERVICE;
			break;
		case 'Creator':
			element = this.Config.Id.TABLE_HEADER_CREATOR;
			contents = Global.Text.CREATOR;
			break;
		case 'LastUpdate':
			element = this.Config.Id.TABLE_HEADER_LAST_UPDATE;
			contents = Global.Text.LAST_UPDATE;
			break;
		case 'Entries':
			element = this.Config.Id.TABLE_HEADER_ENTRIES;
			contents = Global.Text.ENTRIES;
			break;
		}
		$(element).update(contents + allow);
	},
	
	/**
	 * テーブルを描く
	 */
	draw : function() {
		this.stopEventObserving();
		if (!this.resources.length) {
			this.setError(Global.Text.THERE_ARE_NO_RESOURCES);
			return;
		}
		this.pager.perPage = this.perPage.currentPerPage;
		this.pager.totalItems = this.resources.length;
		this.hidePopup();
		this.update(this.createHtml());
		this.initEventListeners();
		this.startEventObserving();
		this.drawSortAllow();
	},

	/**
	 * 実際に表示？
	 */
//	show : function() {
//		
//	},
	
	/**
	 * 実際に隠す？
	 */
	hide : function() {
		this.stopEventObserving();
		this.hidePopup();
	},
	
	getResourceByName : function(resourceName) {
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			if (this.resources[i].name == resourceName) {
				return this.resources[i];
			}
		}
	},
	
	getResourceButtonId : function(resourceName) {
		return this.Config.Id.RESOURCE_BUTTON_PREFIX + resourceName;
	},
	
	getResourceNameByButtonId : function(id) {
		return id.replace(this.Config.Id.RESOURCE_BUTTON_PREFIX, '');
	},

	getMenuButtonId : function(resourceName) {
		return this.Config.Id.RESOURCE_MENU_BUTTON_PREFIX + resourceName
	}
});

QaResourcesBodyPanel.prototype.Config = {
	Id : {
		TABLE : 'qa-resources-table',
		TABLE_HEADER_NAME : 'qa-resources-table-header-name',
		TABLE_HEADER_LANGUAGE : 'qa-resources-table-header-language',
		TABLE_HEADER_READ_PERMISSION : 'qa-resources-table-header-read-permission',
		TABLE_HEADER_EDIT_PERMISSION : 'qa-resources-table-header-edit-permission',
		TABLE_HEADER_SERVICE : 'qa-resources-table-header-service',
		TABLE_HEADER_CREATOR : 'qa-resources-table-header-creator',
		TABLE_HEADER_LAST_UPDATE : 'qa-resources-table-header-last-update',
		TABLE_HEADER_ENTRIES : 'qa-resources-table-header-entries',
		RESOURCE_BUTTON_PREFIX : 'qa-resource-button-',
		RESOURCE_MENU_BUTTON_PREFIX : 'qa-resource-menu-button-',
		PAGER_PREVIEW : 'qa-resources-pager-preview',
		PAGER_ELEMENT_PREFIX : 'qa-resources-pager-element-',
		PAGER_NEXT : 'qa-resources-pager-next',
		PER_PAGE_PREFIX : 'qa-resources-per-page-'
	},
	ClassName : {
		ODD_ROW : 'qa-resource-table-odd-row',
		EVEN_ROW : 'qa-resource-table-even-row',
		READBLE_RESOURCE_NAME : 'qa-resource-readable',
		MENU_BUTTON : 'qa-resource-menu-button',
		PER_PAGE_COMMON : 'qa-resources-per-page-element',
		PAGER_COMMON : 'qa-resources-pager-element'
	}
};

QaResourcesBodyPanel.prototype.Event = {
	resourceClicked : function(event) {
		var element = Event.element(event);
		this.hidePopup();
		Global.location = this.getResourceNameByButtonId(element.id);
		document.fire('state:edit');
	},
	sortByNameClicked : function(event) {
		this.sort('Name');
	},
	sortByLanguageClicked : function(event) {
		this.sort('Language');
	},
	sortByReadPermissionClicked : function(event) {
		this.sort('ReadPermission');
	},
	sortByEditPermissionClicked : function(event) {
		this.sort('EditPermission');
	},
	sortByServiceClicked : function(event) {
		this.sort('Service');
	},
	sortByCreatorClicked : function(event) {
		this.sort('Creator');
	},
	sortByLastUpdateClicked : function(event) {
		this.sort('LastUpdate');
	},
	sortByEntriesClicked : function(event) {
		this.sort('Entries');
	},
	openMenuButtonClicked : function(event) {
		var resourceName = (Event.element(event).id.replace(this.Config.Id.RESOURCE_MENU_BUTTON_PREFIX, ''));

		if (!!this.popup) {
			if (this.popup.resource.name == resourceName) {
				this.hidePopup();
				return;
			}
		}
		var dimensions = Event.element(event).getDimensions();
		var offset = Event.element(event).cumulativeOffset();
		this.popup = new QaResourceConrollerPopupPanel();
		this.popup.resource = this.getResourceByName(resourceName);
		this.popup.bodyPanel = this;
		this.popup.show(offset[0] + dimensions.width - this.popup.WIDTH, offset[1] + dimensions.height);
	}
};

QaResourcesBodyPanel.prototype.Templates = {
	Table : {
		header : '<table id="#{tableId}">'
				+ '<thead><tr><th class="qa-resource-table-name-column">#{name}</th>'
				+ '<th class="qa-resource-table-language-column">#{language}</th>'
				+ '<th class="qa-resource-table-read-permission-column">#{readPermission}</th>'
				+ '<th class="qa-resource-table-edit-permission-column">#{editPermission}</th>'
//				+ '<th class="qa-resource-table-service-column">#{service}</th>'
				+ '<th class="qa-resource-table-creator-column">#{creator}</th>'
				+ '<th class="qa-resource-table-last-update-column">#{lastUpdate}</th>'
				+ '<th class="qa-resource-table-entries-column">#{entries}</th>'
				+ '</tr></thead><tbody>',
		body : '<tr class="#{rowClassName}"><td class="qa-resource-table-name-column"><span id="#{resourceId}" class="#{resourceClassName}">#{name}</span></td><td class="qa-resource-table-language-column">#{language}</td><td class="qa-resource-table-read-permission-column">#{readPermission}</td>'
				+ '<td class="qa-resource-table-edit-permission-column">#{editPermission}</td>'/*<td class="qa-resource-table-service-column">#{service}</td>*/+'<td class="qa-resource-table-creator-column">#{creator}</td>'
				+ '<td class="qa-resource-table-last-update-column">#{lastUpdate}</td><td class="qa-resource-table-entries-column"><div class="float-right">#{entries} <button #{menuButtonAttribute} class="#{menuButtonClassName}" id="#{menuButtonId}"><span>▼</span></button></div></td></tr>',
		footer : '</tbody>'
				+ '<tr class="qa-resource-table-footer">'
				+ '<td colspan="7">'
				+ '<div class="qa-resource-table-footer-result">'
				+ '<div class="float-left qa-common-pager-results-area">#{results}</div>'
				+ '<div class="float-left qa-common-pager-items-area">#{items}</div>'
				+ '</div>'
				+ '<div class="qa-resource-table-footer-pager qa-common-pager-area"><div class="float-right">#{pager}</div></div>'
				+ '</td></tr>'
				+ '</table>'
	}
};