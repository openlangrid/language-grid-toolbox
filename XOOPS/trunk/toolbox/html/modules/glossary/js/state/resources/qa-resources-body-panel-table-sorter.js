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
var QaResourcesBodyPanelTableSorter = Class.create();
QaResourcesBodyPanelTableSorter.prototype = {

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
	
	sortByLanguageAsc : function(a, b) {
		var aLanguages = [], aLanguage;
		LanguageUtils.sort(a.languages, 'asc');
		a.languages.each(function(language){
			aLanguages.push(Global.Language[language]);
		});
		aLanguage = aLanguages.join(', ');
		var bLanguages = [], bLanguage;
		LanguageUtils.sort(b.languages, 'asc');
		b.languages.each(function(language){
			bLanguages.push(Global.Language[language]);
		});
		bLanguage = bLanguages.join(', ');

		if (aLanguage == bLanguage) {
			return 0;
		}
		return (aLanguage > bLanguage) ? 1 : -1;
	},

	sortByLanguageDesc : function(a, b) {
		var aLanguages = [], aLanguage = '';
		LanguageUtils.sort(a.languages, 'asc');
		a.languages.each(function(language){
			aLanguages.push(Global.Language[language]);
		});
		aLanguage = aLanguages.join(', ');
		var bLanguages = [], bLanguage = '';
		LanguageUtils.sort(b.languages, 'asc');
		b.languages.each(function(language){
			bLanguages.push(Global.Language[language]);
		});
		bLanguage = bLanguages.join(', ');
		
		if (aLanguage == bLanguage) {
			return 0;
		}

		return (aLanguage > bLanguage) ? -1 : 1;
	},

	sortByReadPermissionAsc : function(a, b) {
		var a = (a.meta.permission >= Global.Permission.READ) ? 1 : 0;
		var b = (b.meta.permission >= Global.Permission.READ) ? 1 : 0;

		return (a - b);
	},

	sortByReadPermissionDesc : function(a, b) {
		var a = (a.meta.permission >= Global.Permission.READ) ? 1 : 0;
		var b = (b.meta.permission >= Global.Permission.READ) ? 1 : 0;

		return (b - a);
	},
	
	sortByEditPermissionAsc : function(a, b) {
		var a = (a.meta.permission >= Global.Permission.EDIT) ? 1 : 0;
		var b = (b.meta.permission >= Global.Permission.EDIT) ? 1 : 0;

		return (a - b);
	},

	sortByEditPermissionDesc : function(a, b) {
		var a = (a.meta.permission >= Global.Permission.EDIT) ? 1 : 0;
		var b = (b.meta.permission >= Global.Permission.EDIT) ? 1 : 0;

		return (b - a);
	},

	sortByCreatorAsc : function(a, b) {
		var a = a.creator.name.toString();
		var b = b.creator.name.toString();
		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByCreatorDesc : function(a, b) {
		var a = a.creator.name.toString();
		var b = b.creator.name.toString();
		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByLastUpdateAsc : function(a, b) {
		var a = a.meta.updateTime;
		var b = b.meta.updateTime;
		
		if (a == b) {
			return 0;
		}
		return (a > b) ? 1 : -1;
	},

	sortByLastUpdateDesc : function(a, b) {
		var a = a.meta.updateTime;
		var b = b.meta.updateTime;
		
		if (a == b) {
			return 0;
		}
		return (a < b) ? 1 : -1;
	},

	sortByEntriesAsc : function(a, b) {
		var a = parseInt(a.meta.entries);
		var b = parseInt(b.meta.entries);

		if (a < b) {
			return 1;
		} else if (a > b) {
			return -1;
		} else {
			return 0;
		}
	},

	sortByEntriesDesc : function(a, b) {
		var a = parseInt(a.meta.entries);
		var b = parseInt(b.meta.entries);

		if (a > b) {
			return 1;
		} else if (a < b) {
			return -1;
		} else {
			return 0;
		}
	}
};