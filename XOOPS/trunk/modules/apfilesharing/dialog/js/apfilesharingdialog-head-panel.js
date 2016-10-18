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
/* $Id: apfilesharingdialog-head-panel.js 4583 2010-10-13 09:53:46Z yoshimura $ */

var apfilesharingDialogHeadPanel = Class.create();
Object.extend(apfilesharingDialogHeadPanel.prototype, HeadPanel.prototype);
Object.extend(apfilesharingDialogHeadPanel.prototype, {

	initialize: function(options) {
		HeadPanel.prototype.initialize.apply(this, arguments);
	}
});

var apfilesharingDialogHead_SavePanel = Class.create();
Object.extend(apfilesharingDialogHead_SavePanel.prototype, apfilesharingDialogHeadPanel.prototype);
Object.extend(apfilesharingDialogHead_SavePanel.prototype, {

	initialize: function(options) {
		apfilesharingDialogHeadPanel.prototype.initialize.apply(this, arguments);
	},

	_getDialogTitle : function() {
		return this.options.Text.SaveDialogTitle;
	}
});
