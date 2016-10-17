//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
var PreviewMainPanel = Class.create();
PreviewMainPanel.prototype = {
	_TOGGLE_BUTTON_ID_PREFIX : 'toggle-button-',
	_TOGGLE_PANEL_ID_PREFIX : 'toggle-panel-',
	_languages : new Object(),
	_groups : new Object(),

	initialize : function(languages, groups) {
		this.setLanguages(languages);
		this.setGroups(groups);
		this.initEventListener();
	},
	initEventListener : function() {
		$$('.bbs-preview-toggle-button').each(function(toggleButton) {
			Event.observe(toggleButton, 'click', function(event){
				this._togglePanelEvent(event);
			}.bind(this));
		}.bind(this));

		Event.observe('bbs-preview-form-post-button', 'click', this.submit.bind(this));
		Event.observe('bbs-preview-form-cancel-button', 'click', this.cancel.bind(this));
	},

	/**
	 * "+" Or "-" event when the user presses the button
	 */
	_togglePanelEvent : function(event) {
		var toggleId = Event.element(event).id.replace(this._TOGGLE_BUTTON_ID_PREFIX, '');
		this._togglePanel(toggleId);
	},

	/**
	 * toggleId the panel to toggle
	 */
	_togglePanel : function(toggleId) {
		if ($(this._TOGGLE_PANEL_ID_PREFIX + toggleId).visible()) {
			$(this._TOGGLE_PANEL_ID_PREFIX + toggleId).hide();
			$(this._TOGGLE_BUTTON_ID_PREFIX + toggleId).innerHTML = '+';
		} else {
			$(this._TOGGLE_PANEL_ID_PREFIX + toggleId).show();
			$(this._TOGGLE_BUTTON_ID_PREFIX + toggleId).innerHTML = '-';
		}
	},
	submit : function(event) {
		Event.stop(event);
		if (!this.getTranslationPanel().validate()) {
			var messages = new Array();
			this.getTranslationPanel().getBlankSourceTextareaPairs().each(function(sourcePair){
				messages.push(
					Const.Message.previewSourceError
					.replace('%s', this.getGroups()[sourcePair.groupCode])
				);
			}.bind(this));
			this.getTranslationPanel().getBlankTargetTextareaPairs().each(function(targetPair){
				messages.push(
					Const.Message.previewTargetError
					.replace('{0}', this.getLanguages()[targetPair.targetLanguageCode])
					.replace('{1}', this.getGroups()[targetPair.groupCode])
				);
			}.bind(this));
			alert(messages.join("\n"));
			return;
		}
		this._doSubmit();
	},
	_doSubmit : function() {
		document.previewForm.submit();
		return;
	},
	cancel : function(event) {
		Event.stop(event);
		history.back();
	},

	/**
	 * getter/setter
	 */
	getTranslationPanel : function() {
		return this._translationPanel;
	},
	setTranslationPanel : function(translationPanel) {
		this._translationPanel = translationPanel;
		return this;
	},
	getLanguages : function() {
		return this._languages;
	},
	setLanguages : function(languages) {
		this._languages = languages;
		return this;
	},
	getGroups : function() {
		return this._groups;
	},
	setGroups : function(groups) {
		this._groups = groups;
		return this;
	}
};