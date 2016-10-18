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
var QaResourcesHeaderPanel = Class.create();
Object.extend(QaResourcesHeaderPanel.prototype, Panel.prototype);
Object.extend(QaResourcesHeaderPanel.prototype, {
	
	id : 'qa-resources-header-panel',

	searchQaButton : null,
	addQaButton : null,
	importQaButton : null,

	/**
	 * 
	 */
	initialize : function() {
		this.searchQaButton = $('qa-resources-search-qa-button');
		this.addQaButton = $('qa-resources-add-qa-button');
		this.importQaButton = $('qa-resources-import-qa-button');
		this.initEventListeners();
	},

	/**
	 * 
	 */
	initEventListeners : function() {
		this.searchQaButton.observe('click', this.searchQaButtonClicked.bindAsEventListener(this));
		this.addQaButton.observe('click', this.addQaButtonClicked.bindAsEventListener(this));
		this.importQaButton.observe('click', this.importQaButtonClicked.bindAsEventListener(this));
	},
	
	/**
	 * 
	 */
	searchQaButtonClicked : function(event) {
		document.fire('menuPopup:hide');
		document.fire('state:search');
	},
	
	/**
	 * 
	 */
	addQaButtonClicked : function(event) {
		var popup = new QaAddQaPopupPanel();
		popup.show();
		document.fire('menuPopup:hide');
	},
	
	/**
	 * 
	 */
	importQaButtonClicked : function(event) {
		var popup = new QaImportQaPopupPanel();
		popup.show();
		document.fire('menuPopup:hide');
	}
});