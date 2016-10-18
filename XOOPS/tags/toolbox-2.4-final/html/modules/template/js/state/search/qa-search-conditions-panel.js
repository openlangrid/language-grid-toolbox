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
var QaSearchConditionsPanel = Class.create();
Object.extend(QaSearchConditionsPanel.prototype, Panel.prototype);
Object.extend(QaSearchConditionsPanel.prototype, {

	resources : null,
	errorMessage : null,
	tempLanguages : [],
	
	// state
	scope : null,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {

		this.addEvent('keywordLanguageChanged', this.KEYWORD_LANGUAGE_ID, 'change', this.keywordLanguageChanged.bindAsEventListener(this));
		
		// scope
		$$('.' + this.SCOPE_LABEL_CLASS).each(function(element, i){
			this.addEvent('scopeChanged' + i, element, 'click', this.scopeChanged.bindAsEventListener(this));
		}.bind(this));
		this.addEvent('scopeOthersClicked', this.SCOPE_OTHERS_ID, 'click', this.scopeOthersClicked.bindAsEventListener(this));

		$$('.' + this.SCOPE_OTHER_LABEL_CLASS).each(function(element, i) {
					this.addEvent('scopeOtherChanged' + i, element, 'click', this.scopeOtherChanged.bindAsEventListener(this));
				}.bind(this));

		// category
		$$('.' + this.CATEGORY_LABEL_CLASS).each(function(element, i){
			this.addEvent('categoryChanged' + i, element, 'click', this.categoryChanged.bindAsEventListener(this));
		}.bind(this));
		this.addEvent('categoryOthersClicked', this.CATEGORY_OTHERS_ID, 'click', this.categoryOthersClicked.bindAsEventListener(this));

		// language
		$$('.' + this.LANGUAGE_LABEL_CLASS).each(function(element, i){
			this.addEvent('languageChanged' + i, element, 'click', this.languageChanged.bindAsEventListener(this));
		}.bind(this));
		this.addEvent('languageOthersClicked', this.LANGUAGE_OTHERS_ID, 'click', this.languageOthersClicked.bindAsEventListener(this));

		// order
		$$('.' + this.ORDER_LABEL_CLASS).each(function(element, i){
			this.addEvent('orderChanged' + i, element, 'click', this.orderChanged.bindAsEventListener(this));
		}.bind(this));
	},

	valid : function() {
		this.errorMessages = [];
		if ($F(this.KEYWORD_AREA_ID) == '') {
			this.errorMessages.push(Global.Text.ERROR_KEYWORD_IS_EMPTY);
		}
		if (this.getSelectedResources() == 0) {
			this.errorMessages.push(Global.Text.ERROR_SELECT_A_RESOURCE);
		}
		if (this.getSelectedCategory() == 'others' && this.getSelectedCategories().length == 0) {
			this.errorMessages.push(Global.Text.ERROR_SELECT_A_CATEGORY);
		}
		if (this.getSelectedLanguages().length < 2) {
			this.errorMessages.push(Global.Text.ERROR_SELECT_AT_LEAST_TWO_LANGUAGES);
		}
		this.errorMessage = this.errorMessages[0] || null;
		return (this.errorMessages.length == 0);
	},

	serialized : function() {
		var parameters = {
			keyword : $F(this.KEYWORD_AREA_ID),
			keywordLanguage : $F(this.KEYWORD_LANGUAGE_ID),
			matchingMethod : $F(this.MATCHING_METHOD_ID),
			category : this.getSelectedCategory(),
			order : this.getSelectedOrder(),
			orderLanguage : $F(this.ORDER_LANGUAGE_ID)
		};

		this.getSelectedResources().each(function(resource, i){
			parameters['resources[' + i + ']'] = resource.name;
		});

		this.getSelectedCategories().each(function(categoryId, i){
			parameters['categories[' + i + ']'] = categoryId;
		});

		return parameters;
	},

	reset : function() {
		var index = ((!Global.location) ? 0 : 1);
		$$('.' + this.SCOPE_CLASS)[index].checked = true;
		$(this.KEYWORD_AREA_ID).value = '';
		$(this.MATCHING_METHOD_ID).selectedIndex = 0;
		$$('.' + this.ORDER_CLASS)[0].checked = true;
		this.changeScope();
	},

	changeCategories : function() {
		this.setClickable(this.CATEGORY_OTHERS_ID, (this.getSelectedCategory() == 'others'));
	},

	changeLanguages : function() {
		this.setClickable(this.LANGUAGE_OTHERS_ID, (this.getSelectedLanguage() == 'others'));
	},

	changeOrder : function() {
		this.setSelectable(this.ORDER_LANGUAGE_ID, (this.getSelectedOrder() == 'alphabet'));
	},
	
	resetOtherScopeCheck : function() {
		$$('.' + this.SCOPE_OTHER_CLASS)
			.each(function(element){
				element.checked = false;
			}.bind(this));
	},

	changeScope : function() {
		this.scope = this.getSelectedResource();
		this.setClickable(this.SCOPE_OTHERS_ID, (this.scope == 'others'));
		this.resetOtherScopeCheck();
		this.changeOtherScope();
		this.changeCategories();
		this.changeLanguages();
		this.changeOrder();
		this.setSelectedLanguages();
	},
	
	changeOtherScope : function() {
		this.updateLanguageSelectors();
		$(this.KEYWORD_LANGUAGE_ID).selectedIndex = 0;
		$$('.' + this.CATEGORY_CLASS)[0].checked = true;
		this.updateOtherCategories();
		$$('.' + this.LANGUAGE_CLASS)[0].checked = true;
		this.updateOtherLanguages();
		$(this.KEYWORD_LANGUAGE_ID).selectedIndex = 0;
		$$('.' + this.ORDER_CLASS)[0].checked = true;
		this.close(this.CATEGORY_OTHERS_ID, this.CATEGORY_OTHERS_AREA_ID);
		this.close(this.LANGUAGE_OTHERS_ID, this.LANGUAGE_OTHERS_AREA_ID);
	},
	
	createScope : function() {
		var html = [];
		this.resources.each(function(resource){
			html.push(new Template('<span class="#{double}"><label class="#{otherLabelClass}"><input class="#{otherClass}" type="checkbox" value="#{value}" /> #{contents}</label></span>').evaluate({
				otherLabelClass : this.SCOPE_OTHER_LABEL_CLASS,
				otherClass : this.SCOPE_OTHER_CLASS,
				value : resource.name,
				contents : resource.name.truncate(20)
			}));
		}.bind(this));
		return html.join('');
	},

	updateOtherScope : function() {
		$(this.SCOPE_OTHERS_AREA_ID).update(this.createScope());
	},

	createCategories : function() {
		var html = [];
		var language = $F(this.KEYWORD_LANGUAGE_ID);
		this.getCategories().each(function(categoryId){
			html.push(new Template('<span class="#{double}"><label><input type="checkbox" value="#{value}" /> #{contents}</label></span>').evaluate({
				value : categoryId,
				contents : Global.Categories.getName(categoryId, language)
			}));
		}.bind(this));
		if (html.length == 0) {
			html.push('<div style="color:red;">' + Global.Text.WARNING_NO_CATEGORIES_YOU_CAN_SELECT + '</div>');
		}
		return html.join('');
	},

	updateOtherCategories : function() {
		$(this.CATEGORY_OTHERS_AREA_ID).update(this.createCategories());
	},

	createLanguages : function() {
		var html = [];
		var doubleWidth;
		var langs = this.getLanguages();
		LanguageUtils.sort(langs);
		langs.each(function(language){
			if (Global.WideLanguages.indexOf(language) != -1) {
				doubleWidth = 'double-width';
			} else {
				doubleWidth = '';
			}
			html.push(new Template('<span class="#{double}"><label><input type="checkbox" value="#{value}" /> #{contents}</label></span>').evaluate({
				value : language,
				double : doubleWidth,
				contents : Global.Language[language]
			}));
		}.bind(this));
		if (html.length == 0) {
			html.push('<div style="color:red;">' + Global.Text.WARNING_NO_LANGUAGES_YOU_CAN_SELECT + '</div>');
		}
		return html.join('');
	},

	updateOtherLanguages : function() {
		$(this.LANGUAGE_OTHERS_AREA_ID).update(this.createLanguages());
	},

	createLanguageSelector : function() {
		var html = [];
		var lang = this.getLanguages();
		LanguageUtils.sort(lang);
		lang.each(function(language){
			html.push(new Template('<option value="#{value}">#{contents}</option>').evaluate({
				value : language,
				contents : Global.Language[language]
			}));
		}.bind(this));
		return html.join('');
	},

	updateLanguageSelectors : function() {
		$(this.KEYWORD_LANGUAGE_WRAPPER_ID).update(
			'<select id="'+this.KEYWORD_LANGUAGE_ID+'">'
			+ this.createLanguageSelector()
			+ '</select>'
		);
		$(this.ORDER_LANGUAGE_WRAPPER_ID).update(
				'<select id="'+this.ORDER_LANGUAGE_ID+'">'
				+ this.createLanguageSelector()
				+ '</select>'
			);

		this.stopEventObserving();
		this.initEventListeners();
		this.startEventObserving();
	},

	getSelectedResource : function() {
		return $F(
			$$('.' + this.SCOPE_CLASS).find(function(element){
				return element.checked;
			}.bind(this))
		);
	},
	
	getResourceByName : function(name) {
		return this.resources.find(function(resource){
			return (resource.name == name);
		});
	},

	getSelectedResources : function() {
		var resources;
		switch (this.getSelectedResource()) {
		case 'qa':
			resources = [this.getResourceByName(Global.location)];
			break;
		case 'others':
			resources = [];
				$$('.' + this.SCOPE_OTHER_CLASS)
					.each(function(element, i) {
						if (element.checked) {
							resources.push(this.getResourceByName(element.value));
						}
					}.bind(this));
			break;
		default:
			resources = this.resources;
			break;
		}
		return resources;
	},

	getCategories : function() {
		var categories = [];
		this.getSelectedResources().each(function(resource){
			resource.categoryIds.each(function(categoryId){
				categories.push(categoryId);
			}.bind(this));
		}.bind(this));
		return categories;
	},

	getSelectedCategory : function() {
		return $F(
			$$('.' + this.CATEGORY_CLASS).find(function(element){
					return element.checked;
			}.bind(this))
		);
	},

	getSelectedCategories : function() {
		var categories = [];
		switch (this.getSelectedCategory()) {
		case 'others':
			$(this.CATEGORY_OTHERS_AREA_ID).getElementsBySelector('input').each(function(element){
				if (element.checked) {
					categories.push(element.value);
				}
			});
			break;
		default:
			categories = this.getCategories();
			break;
		}
		return categories;
	},

	getLanguages : function() {
		var languages = [];
		this.getSelectedResources().each(function(resource){
			resource.languages.each(function(language){
				languages.push(language);
			}.bind(this));
		}.bind(this));
		return languages.uniq().sort();
	},

	getSelectedLanguage : function() {
		return $F(
			$$('.' + this.LANGUAGE_CLASS).find(function(element){
					return element.checked;
			}.bind(this))
		);
	},
	
	getSelectedLanguages : function() {
		var languages = [];
		switch (this.getSelectedLanguage()) {
			case 'others':
				$(this.LANGUAGE_OTHERS_AREA_ID).getElementsBySelector('input').each(function(element){
					if (element.checked) {
						languages.push(element.value);
					}
				});
				languages.push($F(this.KEYWORD_LANGUAGE_ID));
				languages = languages.uniq();
				break;
			default:
				languages = this.getLanguages();
				break;
		}
		return languages;
	},
	
	getCheckedLanguages : function() {
		var languages = [];
		switch (this.getSelectedLanguage()) {
			case 'others':
				$(this.LANGUAGE_OTHERS_AREA_ID).getElementsBySelector('input').each(function(element){
					if (element.checked) {
						languages.push(element.value);
					}
				});
				languages = languages.uniq();
				break;
			default:
				languages = [];
				break;
		}
		return languages;
	},

	setSelectedLanguages : function() {
		if($(this.LANGUAGE_OTHERS_AREA_ID)){
			$(this.LANGUAGE_OTHERS_AREA_ID).getElementsBySelector('input').each(function(element){
				if(this.tempLanguages.indexOf(element.value) > -1){
					element.checked = true;
				}
			}.bind(this));
		}
	},
	
	draw : function() {
		this.stopEventObserving();
		if (!!Global.location) {
			$(this.SCOPE_SPECIFIED_QA_ID).show();
			$(this.SCOPE_QA_ID).update(Global.location);
		} else {
			$(this.SCOPE_SPECIFIED_QA_ID).hide();
		}
		this.updateOtherScope();
		this.initEventListeners();
		this.startEventObserving();
	},

	getSelectedOrder : function() {
		return $F(
			$$('.' + this.ORDER_CLASS).find(function(element){
				return (element.checked);
			}.bind(this))
		);
	},
	
	isOpen : function(areaId) {
		return $(areaId).visible();
	},
	
	toggle : function(id, areaId) {
		var operation = (this.isOpen(areaId)) ? 'close' : 'open';
		this[operation](id, areaId);
	},
	
	setSelectable : function(id, flag) {
		$(id).disabled = !flag;
	},
	
	setClickable : function(id, flag) {
		if (flag) {
			$(id).addClassName(Global.ClassName.CLICKABLE_TEXT);
			$(id).removeClassName(Global.ClassName.DISABLE_TEXT);
		} else {
			$(id).removeClassName(Global.ClassName.CLICKABLE_TEXT);
			$(id).addClassName(Global.ClassName.DISABLE_TEXT);
		}
	},
	
	open : function(id, areaId) {
		$(areaId).show();
		$(id).addClassName(Global.ClassName.CLOSABLE);
		$(id).removeClassName(Global.ClassName.OPENABLE);
	},

	close : function(id, areaId) {
		$(areaId).hide();
		$(id).removeClassName(Global.ClassName.CLOSABLE);
		$(id).addClassName(Global.ClassName.OPENABLE);
	}
});

// ID, CLASS NAME
Object.extend(QaSearchConditionsPanel.prototype, {
	KEYWORD_AREA_ID : 'qa-search-condition-keyword',
	KEYWORD_LANGUAGE_WRAPPER_ID : 'qa-search-condition-keyword-language-wrapper',
	KEYWORD_LANGUAGE_ID : 'qa-search-condition-keyword-language',
	MATCHING_METHOD_ID : 'qa-search-condition-matching-method',
	SCOPE_CLASS : 'qa-search-condition-scope',
	SCOPE_LABEL_CLASS : 'qa-search-condition-scope-label',
	SCOPE_SPECIFIED_QA_ID : 'qa-search-condition-specified-qa',
	SCOPE_QA_ID : 'qa-search-condition-qa',
	SCOPE_OTHERS_ID : 'qa-search-condition-scope',
	SCOPE_OTHERS_AREA_ID : 'qa-search-condition-scope-others',
	SCOPE_OTHER_LABEL_CLASS : 'qa-search-scope-other-label',
	SCOPE_OTHER_CLASS : 'qa-search-scope-other',
	CATEGORY_CLASS : 'qa-search-condition-category',
	CATEGORY_LABEL_CLASS : 'qa-search-condition-category-label',
	CATEGORY_OTHERS_ID : 'qa-search-condition-category',
	CATEGORY_OTHERS_AREA_ID : 'qa-search-condition-category-others',
	LANGUAGE_CLASS : 'qa-search-condition-language',
	LANGUAGE_LABEL_CLASS : 'qa-search-condition-language-label',
	LANGUAGE_OTHERS_ID : 'qa-search-condition-language',
	LANGUAGE_OTHERS_AREA_ID : 'qa-search-condition-language-others',
	ORDER_CLASS : 'qa-search-condition-order',
	ORDER_LABEL_CLASS : 'qa-search-condition-order-label',
	ORDER_LANGUAGE_WRAPPER_ID : 'qa-search-condition-order-language-wrapper',
	ORDER_LANGUAGE_ID : 'qa-search-condition-order-language'
});

// Event
Object.extend(QaSearchConditionsPanel.prototype, {
	
	keywordLanguageChanged : function(event) {
		this.updateOtherCategories();
	},
	
	scopeChanged : function(event) {
		this.tempLanguages = this.getCheckedLanguages();
		var newScope = this.getSelectedResource();
		if (this.scope == newScope) {
			return;
		}
		if (this.getSelectedResource() != 'others') {
			this.close(this.SCOPE_OTHERS_ID, this.SCOPE_OTHERS_AREA_ID);
		}
		this.changeScope();
	},

	scopeOthersClicked : function(event) {
		if (!$(this.SCOPE_OTHERS_ID).hasClassName(Global.ClassName.CLICKABLE_TEXT)) {
			return;
		}
		this.toggle(this.SCOPE_OTHERS_ID, this.SCOPE_OTHERS_AREA_ID);
	},

	scopeOtherChanged : function(event) {
		this.changeOtherScope();
	},
	
	categoryChanged : function(event) {
		this.changeCategories();
		if (this.getSelectedCategory() != 'others') {
			this.close(this.CATEGORY_OTHERS_ID, this.CATEGORY_OTHERS_AREA_ID);
		}
	},

	categoryOthersClicked : function(event) {
		if (!$(this.CATEGORY_OTHERS_ID).hasClassName(Global.ClassName.CLICKABLE_TEXT)) {
			return;
		}
		this.toggle(this.CATEGORY_OTHERS_ID, this.CATEGORY_OTHERS_AREA_ID);
	},

	languageChanged : function(event) {
		var newLanguage = this.getSelectedLanguage();
		if (this.language == newLanguage) {
			return;
		}
		if (this.getSelectedLanguage() != 'others') {
			this.close(this.LANGUAGE_OTHERS_ID, this.LANGUAGE_OTHERS_AREA_ID);
		}
		this.changeLanguages();
	},

	languageOthersClicked : function(event) {
		if (!$(this.LANGUAGE_OTHERS_ID).hasClassName(Global.ClassName.CLICKABLE_TEXT)) {
			return;
		}
		this.toggle(this.LANGUAGE_OTHERS_ID, this.LANGUAGE_OTHERS_AREA_ID);
	},

	orderChanged : function(event) {
		this.changeOrder();
	}
});