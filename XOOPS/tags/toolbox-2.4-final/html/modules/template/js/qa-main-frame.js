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
var QaMainFrame = Class.create();
QaMainFrame.prototype = {

	id : 'qa-main-frame',
	states : null,
	qaEditState : null,
	qaResourcesState : null,
	qaSearchState : null,
	
	/**
	 * コンストラクタ
	 */
	initialize : function() {
		this.init();
		this.initEventListener();
	},

	/**
	 * 
	 */
	init : function() {
		this.qaEditState = new QaEditState();
		this.qaResourcesState = new QaResourcesState();
		this.qaSearchState = new QaSearchState();

		this.states = [];
		this.states.push(this.qaEditState);
		this.states.push(this.qaResourcesState);
		this.states.push(this.qaSearchState);
		
		this.setState(this.qaResourcesState);
	},

	/**
	 * 
	 */
	initEventListener : function() {
		$('qa-to-top').observe('click', this.topcClicked.bindAsEventListener(this));
		Event.observe(document, 'state:edit', this.stateEdit.bindAsEventListener(this));
		Event.observe(document, 'state:resources', this.stateResources.bindAsEventListener(this));
		Event.observe(document, 'state:search', this.stateSearch.bindAsEventListener(this));
	},
	
	topcClicked : function(event) {

		if (Global.recordPanel) {
			if (!confirm(Global.Text.CONFIRM_DISCARD_CHANGES)) {
				return;
			}
			Global.recordPanel.discardChange();
			Global.recordPanel = null;
		}
		
		Global.location = null;
		document.fire('state:resources');
	},
	
	stateEdit : function() {
		this.setState(this.qaEditState);
	},
	
	stateResources : function() {
		this.setState(this.qaResourcesState);
	},
	
	stateSearch : function() {
		this.setState(this.qaSearchState);
	},

	/**
	 * すべてのStateを隠す
	 */
	hideAll : function() {
		this.states.each(function(state) {
			state.hide();
		});
	},

	/**
	 * Stateを変える
	 * @param {Panel} state
	 */
	setState : function(state) {
		if (this.state == state) {
			return;
		}
		this.hideAll();
		this.state = state;
		this.state.show();
//		window.location.hash = this.state.id + '_' + (Global.location || 'all');
		this.state.draw();
	}
};