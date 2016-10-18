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
/* $Id: text-resources-body-panel.js 3926 2010-08-12 05:38:46Z yoshimura $ */

var TextResourcesBodyPanel = Class.create();
Object.extend(TextResourcesBodyPanel.prototype, Panel.prototype);
Object.extend(TextResourcesBodyPanel.prototype, {

	id : 'text-resources-body-panel',

	DEFAULT_CURRENT_PAGE : 0,
	DEFAULT_PER_PAGE : 10,

	currentLanguageCode: null,

	modules : null,
	pager : null,
	perPage : null,
	tableSorter : null,

	popup : null,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.init();
	},

	init : function() {
		Event.observe(document, 'dom:ChangeLanguage', this.onChangeLanguage.bindAsEventListener(this));
		this.tableSorter = new TextResourcesBodyPanelTableSorter();
		this.buildPager();
		this.buildPerPage();
	},

	onChangeLanguage : function(event) {
//		alert(event.memo);
		this.load(event.memo);
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

		this.addEvent('sortByMidClicked', this.Config.Id.TABLE_HEADER_MID, 'click', this.Event.sortByMidClicked.bindAsEventListener(this));
		this.addEvent('sortByNameClicked', this.Config.Id.TABLE_HEADER_MODULE_NAME, 'click', this.Event.sortByNameClicked.bindAsEventListener(this));
		this.addEvent('sortByFileClicked', this.Config.Id.TABLE_HEADER_FILE_NAME, 'click', this.Event.sortByFileClicked.bindAsEventListener(this));
		this.addEvent('sortByPersonClicked', this.Config.Id.TABLE_HEADER_PERSON, 'click', this.Event.sortByPersonClicked.bindAsEventListener(this));
		this.addEvent('sortByDateClicked', this.Config.Id.TABLE_HEADER_LAST_UPDATE, 'click', this.Event.sortByDateClicked.bindAsEventListener(this));
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
	load : function(languagecode) {
		this.setLoading();
		this.currentLanguageCode = languagecode;
		var param = {'lang':languagecode};
		new Ajax.Request(Global.Url.TEXT_RESOURCE_MODULES_LOAD, {
			postBody : $H(param).toQueryString(),
			onSuccess : function(transport) {
				try {
					var response = transport.responseText.evalJSON();
					this.modules = response.contents.modules;
					this.draw();
				} catch (e) {
					alert(e.toSource());
				}
			}.bind(this),
			onFailure : function() {
			},
			onException : function() {
			}
		});
	},

	modify: function(options) {
		this.setRefresing();

		options.lang = this.currentLanguageCode;
		new Ajax.Request(Global.Url.TEXT_RESOURCE_MODIFY, {
			postBody : $H(options).toQueryString(),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				if (response.status.toUpperCase() == 'ERROR') {
					throw new Error(response.message);
				}
				this.load(this.currentLanguageCode);
			}.bind(this),
			onFailure : function() {
			},
			onException : function(t, e) {
				alert(e.message);
				this.load(this.currentLanguageCode);
			}.bind(this),
			onComplete : function() {
			}.bind(this)
		});
	},

	/**
	 *
	 * @param {Object} loading
	 */
	setLoading : function() {
		this.update(Global.Image.LOADING + ' ' + Global.Text.NOW_LOADING);
	},

	setRefresing : function() {
		this.update(Global.Image.LOADING + ' ' + Global.Text.NOW_MODIFY);
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

	transferJson2TemplateObject: function(module, odd) {
		return {
			rowClassName : (!!odd) ? this.Config.ClassName.ODD_ROW: this.Config.ClassName.EVEN_ROW,
			mid : (module.mid > 0 ? module.mid : Global.Text.NODATA),
			name : module.name,
			fileName : (module.file != '') ? module.file : Global.Text.DEFAULT,
			person : (module.user != '') ? module.user : Global.Text.NODATA,
			lastUpdate : (module.lastUpdate != '') ? module.lastUpdate : Global.Text.NODATA,
			contextMenuId : this.getContextMenuId(module)
		};
	},

	createPagerHtml : function() {
		this.pager.totalItems = this.modules.length;
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
		this.tableSorter.resources = this.modules;
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
			mid : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_MID,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Label.TextResource.MID
			}),
			name : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_MODULE_NAME,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Label.TextResource.MODULE_NAME
			}),
			fileName : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_FILE_NAME,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Label.TextResource.FILE_NAME
			}),
			person : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_PERSON,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Label.TextResource.PERSON
			}),
			lastUpdate : new Template(Global.Templates.SPAN).evaluate({
				id : this.Config.Id.TABLE_HEADER_LAST_UPDATE,
				className :Global.ClassName.CLICKABLE_TEXT,
				contents :Global.Label.TextResource.LAST_UPDATE
			})
		}));

		var odd = 0;
		for (var i = this.pager.getStart(), length = this.pager.getLength(); i < length; i++) {
			html.push(new Template(this.Templates.Table.body).evaluate(
				this.transferJson2TemplateObject(this.modules[i], odd)
			));
			odd = 1 - odd;
		}
		html.push(new Template(this.Templates.Table.footer).evaluate({
			results : this.createResultsHtml(),
			items : this.perPage.createHtml(),
			pager : this.createPagerHtml()
		}));

//		html.push('<div style="margin-top: 8px;"><a class="qa-common-return2top" href="#">' + Global.Text.RETURN_TO_TOP + '</a></div>');
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
		case 'Mid':
			element = this.Config.Id.TABLE_HEADER_MID;
			contents = Global.Label.TextResource.MID;
			break;
		case 'Name':
			element = this.Config.Id.TABLE_HEADER_MODULE_NAME;
			contents = Global.Label.TextResource.MODULE_NAME;
			break;
		case 'File':
			element = this.Config.Id.TABLE_HEADER_FILE_NAME;
			contents = Global.Label.TextResource.FILE_NAME;
			break;
		case 'Person':
			element = this.Config.Id.TABLE_HEADER_PERSON;
			contents = Global.Label.TextResource.PERSON;
			break;
		case 'Date':
			element = this.Config.Id.TABLE_HEADER_LAST_UPDATE;
			contents = Global.Label.TextResource.LAST_UPDATE;
			break;
		}
		$(element).update(contents + allow);
	},

	/**
	 * テーブルを描く
	 */
	draw : function() {
		this.stopEventObserving();
		if (!this.modules.length) {
			this.setError(Global.Text.THERE_ARE_NO_RESOURCES);
			return;
		}
		this.pager.perPage = this.perPage.currentPerPage;
		this.pager.totalItems = this.modules.length;
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
	getContextMenuId : function(module) {
		return this.Config.Id.CONTEXT_MENU_ID_PREFIX + module.mid;
	},
	getLoadedModuleData : function(mid) {
		for(i=0; i<this.modules.length; i++) {
			if (this.modules[i].mid == mid) {
				return this.modules[i];
			}
		}
	}
});

TextResourcesBodyPanel.prototype.Config = {
	Id : {
		TABLE : 'text-resources-table',
		TABLE_HEADER_MID : 'text-resources-table-header-mid',
		TABLE_HEADER_MODULE_NAME : 'text-resources-table-header-module-name',
		TABLE_HEADER_FILE_NAME : 'text-resources-table-header-file-name',
		TABLE_HEADER_PERSON : 'text-resources-table-header-person',
		TABLE_HEADER_LAST_UPDATE : 'text-resources-table-header-last-update',
		PAGER_PREVIEW : 'text-resources-pager-preview',
		PAGER_ELEMENT_PREFIX : 'text-resources-pager-element-',
		PAGER_NEXT : 'text-resources-pager-next',
		PER_PAGE_PREFIX : 'text-resources-per-page-',
		CONTEXT_MENU_ID_PREFIX : 'context-menu-button-'
	},
	ClassName : {
		ODD_ROW : 'odd-row',
		EVEN_ROW : 'even-row',
		READBLE_RESOURCE_NAME : 'common-readable',
		MENU_BUTTON : 'context-menu-button',
		PER_PAGE_COMMON : 'common-per-page-element',
		PAGER_COMMON : 'common-pager-element'
	}
};

TextResourcesBodyPanel.prototype.Event = {
	resourceClicked : function(event) {
		var element = Event.element(event);
		this.hidePopup();
		Global.location = this.getResourceNameByButtonId(element.id);
		document.fire('state:edit');
	},

	sortByMidClicked: function(event) {
		this.sort('Mid');
	},
	sortByNameClicked : function(event) {
		this.sort('Name');
	},
	sortByFileClicked : function(event) {
		this.sort('File');
	},
	sortByPersonClicked : function(event) {
		this.sort('Person');
	},
	sortByDateClicked : function(event) {
		this.sort('Date');
	},

	openMenuButtonClicked : function(event) {
		Event.stop(event);
		var mid = (Event.element(event).id.replace(this.Config.Id.CONTEXT_MENU_ID_PREFIX, ''));

		var m = this.getLoadedModuleData(mid);
		var dimensions = Event.element(event).getDimensions();
		var offset = Event.element(event).cumulativeOffset();
		this.popup = new ContextMenuPopupPanel();
		this.popup.setEventHandlers({fileselected: this.Event.onSelectedFileHandler.bind(this)});
		this.popup.mid = mid;
		this.popup.module = m;
		this.popup.bodyPanel = this;
		this.popup.show(offset[0] + dimensions.width - this.popup.WIDTH, offset[1] + dimensions.height);
	},

	onSelectedFileHandler: function(fileId, module) {
		this.modify({mid:module.mid, fileId:fileId});
	}
};

TextResourcesBodyPanel.prototype.Templates = {
	Table : {
		header : '<table id="#{tableId}">'
				+ '<thead><tr><th class="text-resource-table-mid-column">#{mid}</th>'
				+ '<th class="text-resource-table-name-column">#{name}</th>'
				+ '<th class="text-resource-table-file-name-column">#{fileName}</th>'
				+ '<th class="text-resource-table-person-column">#{person}</th>'
				+ '<th class="text-resource-table-last-update-column">#{lastUpdate}</th>'
				+ '<th class="text-resource-table-context-menu-colum">&nbsp;</th>'
				+ '</tr></thead>',
		body : '<tbody><tr class="#{rowClassName}">'
				+ '<td class="text-resource-table-mid-column">#{mid}</td>'
				+ '<td class="text-resource-table-name-column">#{name}</td>'
				+ '<td class="text-resource-table-file-name-column">#{fileName}</td>'
				+ '<td class="text-resource-table-person-column">#{person}</td>'
				+ '<td class="text-resource-table-last-update-column">#{lastUpdate}</td>'
				+ '<td class="text-resource-table-context-menu-column"><button class="context-menu-button" id="#{contextMenuId}"><span>▼</span></button></td>'
				+ '</tr></tbody>',
		footer : '<tbody>'
				+ '<tr class="common-table-footer">'
				+ '<td colspan="6">'
				+ '<div class="common-table-footer-result">'
				+ '<div class="float-left common-pager-results-area">#{results}</div>'
				+ '<div class="float-left common-pager-items-area">#{items}</div>'
				+ '</div>'
				+ '<div class="common-table-footer-pager common-pager-area"><div class="float-right">#{pager}</div></div>'
				+ '</td></tr>'
				+ '</tbody></table>'
	}
};