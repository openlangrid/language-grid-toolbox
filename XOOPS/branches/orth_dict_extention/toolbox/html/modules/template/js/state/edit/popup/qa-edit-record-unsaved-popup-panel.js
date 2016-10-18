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
var QaEditRecordUnsavedPopupPanel = Class.create();
Object.extend(QaEditRecordUnsavedPopupPanel.prototype, LightPopupPanel.prototype);
Object.extend(QaEditRecordUnsavedPopupPanel.prototype, {
	
	WIDTH : 200,
	stateManager : null,
	
	initialize : function() {
		LightPopupPanel.prototype.initialize.apply(this, arguments);
	},

	initEventListeners : function() {
		LightPopupPanel.prototype.initEventListeners.apply(this, arguments);
		this.addEvent('xClicked', this.X_ID, 'click', this.closeClicked.bindAsEventListener(this));
		this.addEvent('closeClicked', this.CLOSE_ID, 'click', this.closeClicked.bindAsEventListener(this));
	},

	createChangedHtml : function() {
		var questions = [];
		var answers = [];
		this.stateManager.changedParameters.each(function(pair){
			switch (pair.value.type) {
			case this.stateManager.Type.NEW:
				this.stateManager.languages.each(function(language){
					questions.push(language);
				});
				break;
			case this.stateManager.Type.QUESTION:
				questions.push(pair.value.language);
				break;
			case this.stateManager.Type.ANSWER:
				switch (pair.value.operation) {
				case this.stateManager.Operation.ADD:
				case this.stateManager.Operation.DELETE:
					this.stateManager.languages.each(function(language){
						answers.push(language);
					});
					break;
				case this.stateManager.Operation.CHANGE:
					answers.push(pair.value.language);
					break;
				}
				break;
			}
		}.bind(this));
		var html = [];
		var qLangs = questions.uniq();
		LanguageUtils.sort(qLangs);
		qLangs.each(function(language){
			html.push(Global.Text.Q + ': ' + Global.Language[language] + '<br />');
		});
		var aLangs = answers.uniq();
		LanguageUtils.sort(aLangs);
		aLangs.each(function(language){
			html.push(Global.Text.A + ': ' + Global.Language[language] + '<br />');
		});
		return html.join('');
	},

	getBody : function() {
		return new Template(this.BASE_TPL).evaluate({
			xId : this.X_ID,
			unsavedParameters : Global.Text.UNSAVED_PARAMETERS,
			unsaved : this.createChangedHtml(),
			closeId : this.CLOSE_ID,
			close : Global.Text.CLOSE
		});
	},

	onShowPanel : function() {
		
	},
	
	onHidePanel : function() {
		
	},

	closeClicked : function(event) {
		this.hide();
	}
});

// Id
Object.extend(QaEditRecordUnsavedPopupPanel.prototype, {
	X_ID : 'qa-popup-unsaved-xid',
	CLOSE_ID : 'qa-popup-unsaved-closeid'
});

// Template
Object.extend(QaEditRecordUnsavedPopupPanel.prototype, {
	BASE_TPL : '<table class="qa-mastar-category-wrapper" id="qa-select-category-table">'
		+ '<tr><td><div class="float-right"><button id="#{xId}" class="qa-common-close">×</button></div></td></tr>'
		+ '<tr><td><b class="qa-common-popup-new-title">#{unsavedParameters}</b>'
		+ '<tr><td><div style="margin: 15px 0;">#{unsaved}</div></td></tr>'
		+ '<tr><td align="center">'
		+ '<button class="qa-common-cancel-button" id="#{closeId}"><span>#{close}</span></button>'
		+ '</td></tr>'
		+ '</table>'
});