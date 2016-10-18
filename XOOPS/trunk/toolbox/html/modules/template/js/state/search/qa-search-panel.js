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
var QaSearchPanel = Class.create();
Object.extend(QaSearchPanel.prototype, Panel.prototype);
Object.extend(QaSearchPanel.prototype, {

	id : 'qa-search-panel',
	
	resources : null,
	recordsPanel : null,

	conditionsPanel : null,
	isSearching : false,

	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.conditionsPanel = new QaSearchConditionsPanel();
		this.recordsPanel = new QaSearchRecordsPanel();
		this.initEventListeners();
	},

	initEventListeners : function() {
		this.addEvent('topClicked', this.SEARCH_TOP_ID, 'click', this.topClicked.bindAsEventListener(this));
		this.addEvent('searchClicked', this.SEARCH_BUTTON_ID, 'click', this.searchClicked.bindAsEventListener(this));
		this.addEvent('clearClicked', this.CONDITION_CLEAR_BUTTON_ID, 'click', this.clearClicked.bindAsEventListener(this));
	},

//	searchButtonClicked : function(event) {
//		if (this.isSearching) {
//			return;
//		}
//		if (!this.conditionsPanel.isValid()) {
//			alert(this.conditionsPanel.errorMessages.join(''));
//			return;
//		}
//		this.doSearch();
//	},

//	getSearchParameters : function() {
//		return this.conditionsPanel.serialize();
//	},

	search : function() {
		this.isSearching = true;
//		this.searchResultsPanel.languages = this.model.languages;
//		this.searchResultsPanel.sourceLanguage = this.model.keywordLanguage;
//		if (this.model.languages[0] == this.model.keywordLanguage) {
//			this.searchResultsPanel.targetLanguage = this.model.languages[1];
//		} else {
//			this.searchResultsPanel.targetLanguage = this.model.languages[0];
//		}
		$(this.STATUS_AREA).update(Global.Image.LOADING + ' ' + Global.Text.NOW_SEARCHING);
		var parameters = this.conditionsPanel.serialized();
		var resources = this.conditionsPanel.getSelectedResources();
		var selectedLanguages = this.conditionsPanel.getSelectedLanguages();
		new Ajax.Request(Global.Url.SEARCH, {
			postBody : Object.toQueryString(parameters),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				var languages = [];
				resources.each(function(resource){
					resource.records = response.contents.resources.find(function(r){
						return (r.name == resource.name);
					}).records;
					if (resource.records.length > 0) {
						languages = languages.concat(resource.languages);
					}
				});
				languages = languages.uniq();
				for (var i = 0, length = languages.length; i < length; i++) {
					if (selectedLanguages.indexOf(languages[i]) == -1) {
						languages[i] = null;
					}
				}
				
				this.recordsPanel.set(resources, languages.compact(), parameters.keywordLanguage);
				this.recordsPanel.results = response.contents.results;
				this.recordsPanel.buildRecordPanels();
				this.recordsPanel.draw();
				this.draw();
				$(this.RESULT_NUMBER_ID).update(new Template(Global.Text.S_FOR_RESULTS_FOUND).evaluate({
					0 : response.contents.results
				}));
				$(this.CONDITION_CLEAR_AREA_ID).show();
			}.bind(this),
			onException : function() {
			}.bind(this),
			onFailure : function() {
			}.bind(this),
			onComplete : function() {
				this.isSearching = false;
				$(this.STATUS_AREA).update('');
			}.bind(this)
		});
	},

	load : function() {
		$(this.STATUS_AREA).update('');
		$(this.CONDITION_CLEAR_AREA_ID).hide();
		this.recordsPanel.clear();
		$(this.WRAPPER_ID).hide();
		$(this.LOADING_AREA_ID).update(Global.Image.LOADING + ' ' + Global.Text.NOW_LOADING);
		new Ajax.Request(Global.Url.LOAD_RESOURCES, {
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				for (var id in response.contents.categories) {
					Global.Categories.set(id, response.contents.categories[id]);
				}
				for (var id in response.contents.wordSets) {
					Global.WordSets.set(id, response.contents.wordSets[id]);
				}
				
				
				for (var id in response.contents.words) {
					Global.Words.set(id, response.contents.words[id]);
				}

				this.resources = [];
				response.contents.resources.each(function(resource){
					if (resource.meta.permission >= 1) {
						this.resources.push(resource);
					}
				}.bind(this));
				this.conditionsPanel.resources = this.resources;
				this.conditionsPanel.draw();
				this.conditionsPanel.reset();
				this.draw();
			}.bind(this),
			onException : function() {
			}.bind(this),
			onFailure : function() {
			}.bind(this),
			onComplete : function() {
			}.bind(this)
		});
	},

	setError : function(message) {
		$(this.STATUS_AREA).update('<span style="color: red;">' + message + '</span>');
	},
	
	clearSearchResults : function() {
		$(this.CONDITION_CLEAR_AREA_ID).hide();
		this.recordsPanel.clear();
	},

	draw : function() {
		$(this.LOADING_AREA_ID).update('');
		this.stopEventObserving();
		this.initEventListeners();
		if (this.resources.length > 0) {
			$(this.WRAPPER_ID).show();
		} else {
			$(this.LOADING_AREA_ID).update('<span style="color: red;">' + Global.Text.WARNING_NO_RESOURCES_YOU_CAN_SEARCH + '</span>');
		}
		this.startEventObserving();
	},
	
	clear : function() {
		this.recordsPanel.clear();
	}
});

// ID
Object.extend(QaSearchPanel.prototype, {
	SEARCH_TOP_ID : 'qa-search-top',
	LOADING_AREA_ID : 'qa-search-loading-area',
	WRAPPER_ID : 'qa-search-wrapper',
	CONDITION_AREA_ID : 'qa-search-condition',
	SEARCH_BUTTON_ID : 'qa-search-button',
	RESULT_NUMBER_ID : 'qa-search-result-number',
	CONDITION_CLEAR_AREA_ID : 'qa-search-condition-clear-area',
	CONDITION_CLEAR_BUTTON_ID : 'qa-search-condition-clear-button',
	SEARCH_RESULT_AREA_ID : 'qa-search-result-area',
	STATUS_AREA : 'qa-search-status-area'
});

// Event
Object.extend(QaSearchPanel.prototype, {
	topClicked : function(event) {
		if (Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		$(this.WRAPPER_ID).hide();
		document.fire('state:resources');
		Global.location = null;
	},
	searchClicked : function(event) {
		if (Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		if (this.isSearching) {
			return;
		}
		$(this.STATUS_AREA).update('');
		if (!this.conditionsPanel.valid()) {
			this.setError(this.conditionsPanel.errorMessage);
			return;
		}
		this.search();
	},
	clearClicked : function(event) {
		if (Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		this.conditionsPanel.reset();
		this.clearSearchResults();
	}
});