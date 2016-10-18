//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user
// to open or save files on the File Sharing function.
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
/* $Id: body-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */
/* ダイアログのコンテンツ部基底 */

var BodyPanel = Class.create();
Object.extend(BodyPanel.prototype, Panel.prototype);
Object.extend(BodyPanel.prototype, {

	idPrefix: 'dialog-container-',
	optons: null,

	initialize: function(options) {
		Panel.prototype.initialize.apply(this, arguments);

		this.options = options;

		this.element = document.createElement('div');
		this.getElement().addClassName('dialog-body');
		this.getElement().addClassName('clearfix');

		Panel.prototype.hide.apply(this, arguments);
	},

	draw: function() {
		return this.element;
	},

	show: function() {
		Panel.prototype.show.apply(this, arguments);
	}
});

BodyPanel.prototype.Templates = {
	init: '<p>Now loading...</p>'
}