//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
// Copyright (C) 2009  NICT Language Grid Project
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
var LanguagePathsPanel = Class.create();
Object.extend(LanguagePathsPanel.prototype, LanguageSelectorsPanel.prototype);
Object.extend(LanguagePathsPanel.prototype, {

	addButtonId : null,
	removeButtonId : null,

	fromLanguagesPrefixId : null,
	languagesLinkedPrefixId : null,
	toLanguagesPrefixId : null,

	MIN_SELECT_LANGUAGE_PATHS : 1,
	errorMessageId : null,
	selectedLanguagePaths : new Array(),

	init : function() {
		this.selectedLanguagePaths = new Array();
		while (this.selectedLanguagePaths.length < this.MIN_SELECT_LANGUAGE_PATHS) {
			this.selectedLanguagePaths.push(this.getNullLanguagePath());
		}
//		this.initEventListeners();
	},

	initEventListeners : function() {
		this.addEventCache(this.addButtonId, 'click', 'onClickAddButtonEvent');
		this.addEventCache(this.removeButtonId, 'click', 'onClickRemoveButtonEvent');

		this.eventCaches.set('onChangeFromLanguageEvent', this.onChangeFromLanguageEvent.bindAsEventListener(this));
		this.eventCaches.set('onChangeLanguageLinkEvent', this.onChangeLanguageLinkEvent.bindAsEventListener(this));
		this.eventCaches.set('onChangeToLanguageEvent', this.onChangeToLanguageEvent.bindAsEventListener(this));
		this.selectedLanguagePaths.each(function(languagePath, i){
			try {
				$(this.fromLanguagesPrefixId + i).observe('change', this.eventCaches.get('onChangeFromLanguageEvent'));
				$(this.languagesLinkedPrefixId + i).observe('change', this.eventCaches.get('onChangeLanguageLinkEvent'));
				$(this.toLanguagesPrefixId + i).observe('change', this.eventCaches.get('onChangeToLanguageEvent'));
			} catch (e) {
				;
			}
		}.bind(this));
	},

	onChangeFromLanguageEvent : function(event) {
		var element = Event.element(event);
		var index = element.id.replace(this.fromLanguagesPrefixId, '');
		this.selectedLanguagePaths[index].from = {
			code : element.value,
			name : element.options[element.selectedIndex].text
		};
		this.draw();
	},

	onChangeLanguageLinkEvent : function(event) {
		var element = Event.element(event);
		var index = element.id.replace(this.languagesLinkedPrefixId, '');
		this.selectedLanguagePaths[index].bidirectional
			= (element.value == Config.Text.BIDIRECTIONAL);
		this.draw();
	},

	onChangeToLanguageEvent : function(event) {
		var element = Event.element(event);
		var index = element.id.replace(this.toLanguagesPrefixId, '');
		this.selectedLanguagePaths[index].to = {
			code : element.value,
			name : element.options[element.selectedIndex].text
		};
		this.draw();
	},

	onClickAddButtonEvent : function(event) {
//		if (this.selectedLanguagePaths.length >= this.languages.length) {
//
//			return;
//		}
		this.add();
	},

	add : function() {
		this.selectedLanguagePaths.push(this.getNullLanguagePath());
		this.draw();
	},

	onClickRemoveButtonEvent : function(event) {
		if (this.selectedLanguagePaths.length <= this.MIN_SELECT_LANGUAGE_PATHS) {

			return;
		}
		this.remove();
	},

	remove : function() {
		this.selectedLanguagePaths.pop();
		this.draw();
	},

	getNullLanguagePath : function() {
		return {
			from : {
				code : '',
				value : ''
			},
			bidirectional : false,
			to : {
				code : '',
				value : ''
			}
		}
	},

	getSelectedLanguageCount : function() {
		var count = 0;
		this.selectedLanguagePaths.each(function(selectedLanguagePath){
			if (!!selectedLanguagePath.from.code &&!!selectedLanguagePath.to.code ) {
				count++;
			}
		}.bind(this));

		return count;
	},

	isLessThanMinSelectedLanguages : function() {
		return (this.getSelectedLanguageCount() < this.MIN_SELECT_LANGUAGE_PATHS);
	},

	validate : function() {

		this.errorMessage = '';

		if (this.isLessThanMinSelectedLanguages()) {

			this.errorMessage = Config.Text.AT_LEAST_ONE_LANGUAGE_PATH_IS_REQUIRED;
		} else if (this.hasSamePath()) {
			this.errorMessage = Config.Text.HAS_SAME_PATHS;
		} else if (this.hasDuplicatedPaths()) {
			this.errorMessage = Config.Text.HAS_DUPLICATED_PATHS;
		}

		return !this.errorMessage;
	},

	hasSamePath : function() {
		var samePaths = new Array();
		this.selectedLanguagePaths.each(function(path) {
			if (path.from.code == path.to.code) {
				samePaths.push(path);
			}
		});
		return samePaths.length > 0
	},

	hasDuplicatedPaths : function() {
		var paths = new Array();
		var duplicatedPaths = new Array();
		this.selectedLanguagePaths.each(function(path) {
			var twoPath = path.from.code + '2' + path.to.code;
			if (paths.indexOf(twoPath) != -1) {
				duplicatedPaths.push(twoPath);
			} else {
				paths.push(twoPath);
			}
			if (path.bidirectional) {
				twoPath = path.to.code + '2' + path.from.code;
				if (paths.indexOf(twoPath) != -1) {
					duplicatedPaths.push(twoPath);
				} else {
					paths.push(twoPath);
				}
			}
		});
		return duplicatedPaths.length > 0
	},

	draw : function() {
		try {
			this.stopEventObserving();
		} catch (e) {
			;
		}

		var html = new Array();

		var i = 0;

		this.selectedLanguagePaths.each(function(languagePath) {

			var fromSelected;
			html.push('<div class="langrid-path-select-area">');
			try {
				fromSelected = languagePath.from;
			} catch (e) {
				fromSelected = null;
			}
			html.push(this.createLanguageComboBox(this.fromLanguagesPrefixId + i, fromSelected, toSelected));

			html.push(' <div class="langrid-path-select-area-link">');
			html.push(this.createPathLinkedComboBox(this.languagesLinkedPrefixId + i, languagePath.bidirectional));
			html.push('</div> ');
			var toSelected;
			try {
				toSelected = languagePath.to;
			} catch (e) {
				toSelected = null;
			}
			html.push(this.createLanguageComboBox(this.toLanguagesPrefixId + i, toSelected, fromSelected));
			html.push(Config.Html.Element.BR);
			html.push('</div>');
			i++;
		}.bind(this));

		// Add opration
		html.push(this.createOperations());
		this.getElement().update(html.join(''));


		if (this.selectedLanguagePaths.length <= this.MIN_SELECT_LANGUAGE_PATHS) {
			this.setRemoveButtonDisabled(true);
		} else {
			this.setRemoveButtonDisabled(false);
		}

		this.initEventListeners();
		this.startEventObserving();
	},

	createLanguagePath : function(id, fromSelected, bidirectional, toSelected) {
		html.push(new Template(Templates.ComboBox.body).evaluate({
			value : language.code,
			name : language.name,
			selected : (selectedLanguage.code == language.code) ? Config.Html.Attribute.SELECTED : ''
		}));
	},

	createLanguageComboBox : function(id, selectedLanguage, pairLanguage) {
		var html = new Array();
		html.push(new Template(Templates.ComboBox.header).evaluate({
			id : id
		}));
		html.push(new Template(Templates.ComboBox.body).evaluate({
			value : '',
			name : Config.Text.NULL_LANGUAGE_NAME,
			selected : ''
		}));
		this.languages.each(function(language) {
//			var skip = false;
//			if (selectedLanguage.code != language.code) {
//
//				this.selectedLanguages.each(function(thisSelectedLanguage){
//					if (thisSelectedLanguage.code == language.code) {
//						skip = true;
//						throw $break;
//					}
//				}.bind(this));
//				if (skip) {
//					return;
//				}
//			}
//			try {
//				if (pairLanguage.code == language.code) {
//					return;
//				}
//			} catch (e) {
//				;
//			}
			html.push(new Template(Templates.ComboBox.body).evaluate({
				value : language.code,
				name : language.name,
				selected : (selectedLanguage.code == language.code) ? Config.Html.Attribute.SELECTED : ''
			}));
		}.bind(this));
		html.push(Templates.ComboBox.footer);
		return html.join('');
	},

	createPathLinkedComboBox : function(id, bidirectional) {
		var html = new Array();
		html.push(new Template(Templates.ComboBox.header).evaluate({
			id : id
		}));
		html.push(new Template(Templates.ComboBox.body).evaluate({
			value : Config.Text.MONODIRECTIONAL,
			name : Config.Text.MONODIRECTIONAL,
			selected : (!bidirectional) ? Config.Html.Attribute.SELECTED : ''
		}));
		html.push(new Template(Templates.ComboBox.body).evaluate({
			value : Config.Text.BIDIRECTIONAL,
			name : Config.Text.BIDIRECTIONAL,
			selected : (bidirectional) ? Config.Html.Attribute.SELECTED : ''
		}));
		html.push(Templates.ComboBox.footer);
		return html.join('');
	},

	getSeriarizedLanguages : function() {
		var languages = new Array();
//		var length = this.selectedLanguages.length;
		this.selectedLanguagePaths.each(function(selectedLanguagePath, i) {
			if (!selectedLanguagePath.from.code || !selectedLanguagePath.to.code) {
				return;
			}
			languages.push(selectedLanguagePath.from.code + '2' + selectedLanguagePath.to.code);
			if (selectedLanguagePath.bidirectional) {
				languages.push(selectedLanguagePath.to.code + '2' + selectedLanguagePath.from.code);
			}
		}.bind(this));

		return languages;
	},

	getSelectedLanguages : function() {
		return this.selectedLanguagePaths;
	},

	setSelectedLanguagePaths : function(selectedLanguagePaths) {
		this.selectedLanguagePaths = selectedLanguagePaths;
		while (this.selectedLanguagePaths.length < this.MIN_SELECT_LANGUAGE_PATHS) {
			this.selectedLanguagePaths.push(this.getNullLanguagePath());
		}
	}
});