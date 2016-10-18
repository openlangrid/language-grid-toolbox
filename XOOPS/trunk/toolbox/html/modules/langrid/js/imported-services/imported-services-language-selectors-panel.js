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
var LanguageSelectorsPanel = Class.create();
Object.extend(LanguageSelectorsPanel.prototype, Panel.prototype);
Object.extend(LanguageSelectorsPanel.prototype, {

	addButtonId : null,
	removeButtonId : null,

	languagePrefixId : null,

	languages : null,

	MIN_SELECT_LANGUAGES : 2,

	selectedLanguages : new Array(),

	errorMessageId : null,

	errorMessage : '',

	init : function() {
		this.selectedLanguages = new Array();
		while (this.selectedLanguages.length < this.MIN_SELECT_LANGUAGES) {
			this.selectedLanguages.push(this.getNullLanguage());
		}
//		this.initEventListeners();
	},

	initEventListeners : function() {
		this.addEventCache(this.addButtonId, 'click', 'onClickAddButtonEvent');
		this.addEventCache(this.removeButtonId, 'click', 'onClickRemoveButtonEvent');

		this.eventCaches.set('onChangeLanguageSelectorEvent', this.onChangeLanguageSelectorEvent.bindAsEventListener(this));
		this.selectedLanguages.each(function(language, i){
			try {
				$(this.languagePrefixId + i).observe('change', this.eventCaches.get('onChangeLanguageSelectorEvent'));
			} catch (e) {
				;
			}
		}.bind(this));
	},

	onClickAddButtonEvent : function(event) {
		if (this.selectedLanguages.length >= this.languages.length) {

			return;
		}
		this.add();
	},

	add : function() {
		this.selectedLanguages.push(this.getNullLanguage());
		this.draw();
	},

	onClickRemoveButtonEvent : function(event) {
		if (this.selectedLanguages.length <= this.MIN_SELECT_LANGUAGES) {

			return;
		}
		this.remove();
	},

	remove : function() {
		this.selectedLanguages.pop();
		this.draw();
	},

	onChangeLanguageSelectorEvent : function(event) {
		var element = Event.element(event);
		var index = element.id.replace(this.languagePrefixId, '');
		this.selectedLanguages[index] = {
			code : element.value,
			name : element.options[element.selectedIndex].text
		};
		this.draw();
	},

	getNullLanguage : function() {
		return {
			code : '',
			value : ''
		}
	},

	draw : function() {
		try {
			this.stopEventObserving();
		} catch (e) {
			;
		}

		var html = new Array();


		this.selectedLanguages.each(function(language, i) {
			html.push(this.createLanguageComboBox(this.languagePrefixId + i, language));
			html.push(Config.Html.Element.BR);
		}.bind(this));

		html.push(this.createOperations());

		this.getElement().update(html.join(''));

		if (this.selectedLanguages.length <= this.MIN_SELECT_LANGUAGES) {
			this.setRemoveButtonDisabled(true);
		} else {
			this.setRemoveButtonDisabled(false);
		}

		this.initEventListeners();
		this.startEventObserving();
	},

	setRemoveButtonDisabled : function(disabled) {
		if (disabled) {
			$(this.removeButtonId).removeClassName(Config.ClassName.SPAN_LINK);
			$(this.removeButtonId).addClassName(Config.ClassName.SPAN_NOT_LINK);
		} else {
			$(this.removeButtonId).addClassName(Config.ClassName.SPAN_LINK);
			$(this.removeButtonId).removeClassName(Config.ClassName.SPAN_NOT_LINK);
		}
	},

	setErrorMessage : function(message) {
		$(this.errorMessageId).update(message);
	},

	createLanguageComboBox : function(id, selectedLanguage) {
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
			var skip = false;
			if (selectedLanguage.code != language.code) {

				this.selectedLanguages.each(function(thisSelectedLanguage){
					if (thisSelectedLanguage.code == language.code) {
						skip = true;
						throw $break;
					}
				}.bind(this));
				if (skip) {
					return;
				}
			}
			html.push(new Template(Templates.ComboBox.body).evaluate({
				value : language.code,
				name : language.name,
				selected : (selectedLanguage.code == language.code) ? Config.Html.Attribute.SELECTED : ''
			}));
		}.bind(this));
		html.push(Templates.ComboBox.footer);
		return html.join('');
	},

	createOperations : function() {
		var html = new Array();

		html.push('<div class="clearfix">');

		// Remove Language
		html.push(new Template(Templates.span).evaluate({
			id : this.removeButtonId,
			className : Config.ClassName.SPAN_LINK,
			value : Config.Text.REMOVE_LANGUAGE
		}));

		// Add Language
		html.push(new Template(Templates.span).evaluate({
			id : this.addButtonId,
			className : Config.ClassName.SPAN_LINK,
			value : Config.Text.ADD
		}));

		html.push('</div>');

		return html.join('');
	},

	setLanguages : function(languages) {
		this.languages = languages;
	},

	getSelectedLanguageCount : function() {
		var count = 0;
		this.selectedLanguages.each(function(selectedLanguage){
			if (!!selectedLanguage.code) {
				count++;
			}
		}.bind(this));

		return count;
	},

	isLessThanMinSelectedLanguages : function() {
		return (this.getSelectedLanguageCount() < this.MIN_SELECT_LANGUAGES);
	},

	validate : function() {

		this.errorMessage = '';

		if (this.isLessThanMinSelectedLanguages()) {

			this.errorMessage = Config.Text.AT_LEAST_TWO_LANGUAGES_ARE_REQUIRED;
		}

		return !this.errorMessage;
	},

	getErrorMessage : function() {
		return this.errorMessage;
	},


	getSeriarizedLanguages : function() {
		var languages = new Array();
		var length = this.selectedLanguages.length;
		this.selectedLanguages.each(function(selectedLanguage, i) {
			if (!selectedLanguage.code) {
				return;
			}
			for (j = 0; j < length; j++) {
				if (j == i) {
					continue;
				}
				if (!this.selectedLanguages[j].code) {
					continue;
				}
				languages.push(selectedLanguage.code + '2' + this.selectedLanguages[j].code);
			}
		}.bind(this));
		return languages;
	},

	// getter/setter
	getSelectedLanguages : function() {
		return this.selectedLanguages;
	},

	setSelectedLanguages : function(selectedLanguages) {
		this.selectedLanguages = selectedLanguages;
		while (this.selectedLanguages.length < this.MIN_SELECT_LANGUAGES) {
			this.selectedLanguages.push(this.getNullLanguage());
		}
	}
});