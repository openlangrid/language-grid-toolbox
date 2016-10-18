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
/* $Id: main-frame.js 3662 2010-06-16 02:22:17Z yoshimura $ */

var MainFrame = Class.create();
MainFrame.prototype = {

	id : 'uicustomize-main-frame',
	states : null,
	textState : null,

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
		this.textState = new TextResourcesState();

		this.states = [];
		this.states.push(this.textState);

		this.setState(this.textState);
	},

	/**
	 *
	 */
	initEventListener : function() {
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
		this.state.draw();
	}
};