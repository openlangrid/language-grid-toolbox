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
/* $Id: text-resources-body-panel-table-sorter.js 3727 2010-07-13 05:30:48Z yoshimura $ */

var TextResourcesBodyPanelTableSorter = Class.create();
TextResourcesBodyPanelTableSorter.prototype = {

	resources : null,
	key : null,
	order : null,

	initialize : function() {

	},

	sort : function() {
		var sorter = 'sortBy' + this.key + this.order;
		this.resources.sort(this[sorter]);
	},

	changeOrder : function() {
		this.order = (this.order == 'Desc') ? 'Asc' : 'Desc';
	},

	sortByMidAsc : function(a, b) {
		var a = a.mid;
		var b = b.mid;
		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	},
	sortByMidDesc : function(a, b) {
		var a = a.mid;
		var b = b.mid;
		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByNameAsc : function(a, b) {
		var a = a.name.toString();
		var b = b.name.toString();
		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByNameDesc : function(a, b) {
		var a = a.name.toString();
		var b = b.name.toString();
		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByFileAsc : function(a, b) {
		var a = a.file.toString();
		var b = b.file.toString();
		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByFileDesc : function(a, b) {
		var a = a.file.toString();
		var b = b.file.toString();
		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByPersonAsc : function(a, b) {
		var a = a.user.toString();
		var b = b.user.toString();
		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByPersonDesc : function(a, b) {
		var a = a.user.toString();
		var b = b.user.toString();
		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByDateAsc : function(a, b) {
		var a = a.lastUpdate;
		var b = b.lastUpdate;
		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByDateDesc : function(a, b) {
		var a = a.lastUpdate;
		var b = b.lastUpdate;
		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	}
};