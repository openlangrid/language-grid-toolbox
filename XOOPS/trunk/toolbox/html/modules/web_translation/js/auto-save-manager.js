//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
// Copyright (C) 2009-2010 Department of Social Informatics, Kyoto University
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

var AutoSaveManager = Class.create({
	
	manager: null,
	moduleId: null,
	screenId: null,
	
	/**
	 * Constructor
	 */
	initialize: function() {
		this.init();
		this.initEventListeners();
		this.load();
	},
	
	init: function() {
		this.manager = new InfoManager('action');
		this.moduleId = $F('moduleId');
		this.screenId = $F('screenId');
	},

	initEventListeners: function() {
		Event.observe(window, 'unload', this.save.bind(this));
	},
	
	load: function() {
		var items = this.manager.loadItems(this.moduleId, this.screenId);

		$('wc-url-input').value = items.get('url') || 'http://';
		
		var sourceLanguage = items.get('sourceLanguage');
		if (sourceLanguage) {
			Model.Language.setSourceLanguage(sourceLanguage);
		}

		var targetLanguage = items.get('targetLanguage');
		if (targetLanguage) {
			Model.Language.setTargetLanguage(targetLanguage);
		}

		Model.Language.setChanged();
		Model.Language.notifyObservers();
	},

	save: function() {
		var data = {};
		
		try {
			data['url'] = $F('wc-url-input');
			data['sourceLanguage'] = Model.Language.getSourceLanguage();
			data['targetLanguage'] = Model.Language.getTargetLanguage();
			
			this.manager.saveItems(this.moduleId, this.screenId, Object.toJSON(data));
		} catch (e) {
			;
		}
	}
});