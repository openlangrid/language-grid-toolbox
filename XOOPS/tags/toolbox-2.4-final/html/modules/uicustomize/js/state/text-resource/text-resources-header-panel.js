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
/* $Id: text-resources-header-panel.js 3662 2010-06-16 02:22:17Z yoshimura $ */

var TextResourcesHeaderPanel = Class.create();
Object.extend(TextResourcesHeaderPanel.prototype, Panel.prototype);
Object.extend(TextResourcesHeaderPanel.prototype, {

	id : 'text-resources-header-panel',

	languageSelector: null,
	languageEdit: null,
	selectedLanguage : null,

	/**
	 *
	 */
	initialize : function() {
		this.selectedLanguage = Global.displayLanguage;
		this.languageSelector = $('language-selector');
		this.languageEdit = $('language-edit');
		this.initEventListeners();
		this.preload();
	},

	/**
	 *
	 */
	initEventListeners : function() {
		this.languageSelector.observe('change', this.languageSelectorChanged.bindAsEventListener(this));
		this.languageEdit.observe('click', this.languageEditClicked.bindAsEventListener(this));
	},

	/**
	 *
	 */
	languageSelectorChanged : function(event) {
		this.selectedLanguage = this.languageSelector.value;
		document.fire('dom:ChangeLanguage', this.languageSelector.value);
	},

	/**
	 *
	 */
	languageEditClicked : function(event) {
		var popup = new TextEditLanguagePopupPanel();
		popup.show();
	},

	/**
	 *
	 */
	preload: function() {
		new Ajax.Request(Global.Url.TEXT_RESOURCE_LANGUAGE_LOAD, {
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				Global.supportedLanguages = response.contents.languages;
				response.contents.languages.each(function(tag, i) {
					this.appendLanguageOption(tag);
				}.bind(this));
			}.bind(this),
			onFailure : function() {
			},
			onException : function() {
			}
		});
	},

	/**
	 *
	 */
	appendLanguageOption: function(languageTag) {
		var op = document.createElement('option');
		if (languageTag == this.selectedLanguage) {
			op.setAttribute('selected', 'yes');
		}
		op.setAttribute('value', languageTag);
		op.innerHTML = Global.Language[languageTag];
		this.languageSelector.appendChild(op);
	}
});
