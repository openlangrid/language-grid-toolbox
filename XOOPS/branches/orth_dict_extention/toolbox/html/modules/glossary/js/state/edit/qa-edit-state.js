//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to create
// glossaries.
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
var QaEditState = Class.create();
Object.extend(QaEditState.prototype, Panel.prototype);
Object.extend(QaEditState.prototype, {
	id : 'qa-edit-state',
	panel : null,
	
	initialize : function() {
		Panel.prototype.initialize.apply(this, arguments);
		this.panel = new QaEditPanel();
		this.initEventListeners();
	},
	
	initEventListeners : function() {
		this.addEvent('topClicked', 'qa-edit-top', 'click', this.Event.topClicked.bindAsEventListener(this));
		this.addEvent('searchClicked', 'qa-edit-search-qa-button', 'click', this.Event.searchClicked.bindAsEventListener(this));
	},

	draw : function() {
		this.stopEventObserving();
		$('qa-edit-title').update(Global.location);
		this.panel.load();
		this.startEventObserving();
	},
	
	hide : function() {
		Panel.prototype.hide.apply(this, arguments);
		this.panel.clear();
	}
});

QaEditState.prototype.Event = {
	topClicked : function(event) {
		if (!!Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		Global.location = null;
		document.fire('state:resources');
	},
	searchClicked : function(event) {
		if (!!Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		document.fire('state:search');
	}
};