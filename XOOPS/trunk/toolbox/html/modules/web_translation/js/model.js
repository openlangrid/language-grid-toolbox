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

var Model = {};

Model.Translation = new (Class.create(Observable, {

	/**
	 * @private
	 */
	_result: null,

	_showTagLine: false,

	initialize: function($super) {
		$super();
		this._result = [];
	},

	isEmpty: function() {
		return ((!this._result) || (!this._result.length));
	},

	/**
	 * @return result
	 */
	getResult: function() {
		return this._result || [];
	},

	setResult: function(result) {
		this._result = result;
	},

	/**
	 * @return String
	 */
	getSourceAsString: function() {
		return this.getAsString('source');
	},

	/**
	 * @return String
	 */
	getTargetAsString: function() {
		return this.getAsString('target');
	},

	/**
	 * @return String
	 */
	getAsString: function(type) {
		if (!this._result) {
			return '';
		}

		var str = [];

		this._result.each(function(line) {
			str.push(line[type] || '');
		});

		return str.join('');
	},

	setShowTagLine : function(isShow) {
		this._showTagLine = isShow;
	},

	isShowTagLine : function() {
		return this._showTagLine;
	}

}))();

var TemplateModel = Class.create(Observable, {

	_templates: null,

	initialize: function($super) {
		$super();
		this._templates = [];
	},

	get: function() {
		return this._templates;
	},

	getCheckedTemplates: function() {
		var templates = [];

		this.get().each(function(t) {
			if (t.checked) {
				templates.push(t);
			}
		});

		return templates;
	},

	set: function(templates) {
		this._templates = templates;
	},

	add: function(template) {
		this._templates.push(template);
	},

	remove: function(template) {
		this._templates.each(function(t, i){
			if (t == template) {
				this.removeByIndex(i);
				throw $break;
			}
		}.bind(this));
	},

	removeByIndex: function(index) {
		this._templates.splice(index, 1);
	}
});

var ApplyTemplateModel = Class.create(TemplateModel, {
	hasId: function(id) {
		var found = false;

		this._templates.each(function(t){
			if (t.id == id) {
				found = true;
				throw $break;
			}
		});

		return found;
	}
});

Model.Template = new TemplateModel();
Model.ApplyTemplate = new ApplyTemplateModel();

Model.Language = new (Class.create(Observable, {

	/**
	 * @private
	 */
	_sourceLanguage: null,

	/**
	 * @private
	 */
	_targetLanguage: null,

	/**
	 * @private
	 */
	_pairs: null,

	/**
	 * @return Object Language Pairs
	 */
	getPairs: function() {
		return this._pairs;
	},

	/**
	 * @param Object pairs
	 */
	setPairs: function(pairs) {
		this._pairs = pairs;
	},

	/**
	 * @return String Source Language
	 */
	getSourceLanguage: function() {
		return this._sourceLanguage;
	},

	/**
	 * @param Object lang
	 */
	setSourceLanguage: function(lang) {
		this._sourceLanguage = lang;

		var targetLangs = this.getTargetLanguages();

		if (!targetLangs.include(this._targetLanguage)) {
			this._targetLanguage = targetLangs.first();
		}
	},

	/**
	 * @return String Target Language
	 */
	getTargetLanguage: function() {
		return this._targetLanguage;
	},

	/**
	 * @return String Target Language
	 */
	setTargetLanguage: function(targetLanguage) {
		var targetLangs = this.getTargetLanguages();
		this._targetLanguage = targetLanguage;

		if (!targetLangs.include(this._targetLanguage)) {
			this._targetLanguage = targetLangs.first();
		}
	},

	/**
	 * @return String[] Source Languages
	 */
	getSourceLanguages: function() {
		return Object.keys(this._pairs);
	},

	/**
	 * @return String[] Target Languages
	 */
	getTargetLanguages: function() {
		return this._pairs[this.getSourceLanguage()] || [];
	}
}))();

Model.License = new (Class.create(Observable, {
	_licenses: null,

	intialize: function($super) {
		$super();
		this._licenses = new Hash();
	},

	hasGoogle: function() {
		if (this._licenses.keys().indexOf('Google Translate') != -1) {
			return true;
		}

		return false;
	},

	get: function() {
		return this._licenses;
	},

	set: function(licenses) {
		this._licenses = licenses;
	},

	/**
	 * @param Object licenses
	 */
	merge: function(licenses) {
		this._licenses = this._licenses.merge(new Hash(licenses));
	},

	clear: function() {
		this._licenses = new Hash();
	}
}));

Model.FileName = new (Class.create({

	_workspace: null,
	_template: null,
	_html: null,

	/**
	 * @return String Workspace File Name
	 */
	getWorkspace: function(workspace) {
		return this._workspace || '';
	},

	/**
	 * @param String Workspace File Name
	 * @return void
	 */
	setWorkspace: function(workspace) {
		this._workspace = workspace;
	},

	/**
	 * @return String Template File Name
	 */
	getTemplate: function(template) {
		return this._template || '';
	},

	/**
	 * @param String Template File Name
	 * @return void
	 */
	setTemplate: function(template) {
		this._template = template;
	},

	/**
	 * @return String HTML File Name
	 */
	getHtml: function(html) {
		return this._html || '';
	},

	/**
	 * @param String HTML File Name
	 * @return void
	 */
	setHtml: function(html) {
		this._html = html;
	}
}));

/**
 * UI state change observer
 */
Model.EditState = new (Class.create({

	_changed : null,

	intialize : function() {
		this._changed = false;
	},

	/**
	 * @return ui user edit state.
	 */
	isEdited : function() {
		return this._changed;
	},

	/**
	 * set change state.
	 * @param flg
	 */
	setChange : function(flg) {
		this._changed = flg;
	}

}))();