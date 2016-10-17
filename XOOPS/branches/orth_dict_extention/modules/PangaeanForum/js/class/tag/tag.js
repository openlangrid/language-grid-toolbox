//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
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
var TagBox = Class.create();
TagBox.prototype = {

	tagSelectors : {},

	initialize : function() {
		if ($('tag_box')) {
			this.initialize_main();
		}
	},

	initialize_main : function() {
		TagBoxConst.Resource.each(function(data) {
			this.tagSelectors[data.id] = new TagSelector(data);
			$('tag_box_conteiner').appendChild(this.tagSelectors[data.id].getElement());
		}.bind(this));
	}
};

var TagSelector = Class.create();
TagSelector.prototype = {

	data : null,

	selector : null,

	wrapper : null,

	initialize : function(resourceData) {
		this.data = resourceData;
		this._createWrapper();
		this._createSelector();
		this.wrapper.appendChild(this.selector);
	},

	getElement : function() {
		return this.wrapper;
	},

	_createSelector : function() {
		this.selector = document.createElement('select');
		this.selector.id = 'message_tag_' + this.data.id;
		this.selector.setAttribute('name', 'message_tag['+this.data.id+']');
		this.selector.setAttribute('class', 'message-tag');
		this.selector.appendChild(this._makeOpt('0', '-'));
		this.data.words.each(function(word) {
			this.selector.appendChild(this._makeOpt(word.id, word.word, TagBoxConst.Selected.include(word.id)));
		}.bind(this));
	},

	_createWrapper : function() {
		this.wrapper = document.createElement('li');
		this.wrapper.innerHTML = new Template('<span>#{LABEL}</span>').evaluate({LABEL : this.data.name});
	},

	_makeOpt : function(value, label, selected) {
		var opt = document.createElement('option');
		opt.setAttribute('value', value);
		if (selected) {
			opt.setAttribute('selected', 'selected');
		}
		opt.innerHTML = label;
		return opt;
	}
};