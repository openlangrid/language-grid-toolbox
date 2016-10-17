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
var SearchInput = Class.create();
SearchInput.prototype = {

	initialize : function() {
		$(this._ID_.button).observe('click', this.onSubmitButton_Clicked.bindAsEventListener(this));
		$(this._ID_.form).observe('submit', this.onSubmitButton_Clicked.bindAsEventListener(this));
	},

	onSubmitButton_Clicked : function(event) {
		Event.stop(event);

		var url = './?search_result';

		url += this.getQueryByWord();
		url += this.getQueryByScope();
		url += this.getQueryByTag();

		document.location.href = url;
	},

	getQueryByWord : function() {
		if ($F(this._ID_.wordText)) {
			return '&word='+$F(this._ID_.wordText);
		} else {
			return '';
		}
	},

	getQueryByScope : function() {
		var query = '&';
		if ($F(this._ID_.topicSelector) > 0) {
			query += 'topicId='+$F(this._ID_.topicSelector);
		} else if ($F(this._ID_.forumSelector) > 0) {
			query += 'forumId='+$F(this._ID_.forumSelector);
		} else if ($F(this._ID_.categorySelector) > 0) {
			query += 'categoryId='+$F(this._ID_.categorySelector);
		} else {
			query = '';
		}
		return query;
	},

	getQueryByTag : function() {
		var query = '';
		$$('select.message-tag').each(function(elem, index) {
			if ($F(elem) > 0) {
				query += '&tag['+elem.id.replace(this._ID_.messageTagPrefix, '')+']='+$F(elem);
			}
		}.bind(this));
		return query;
	},

	_ID_ : {
		wordText : 'word_text',
		categorySelector : 'jumpbox_category',
		forumSelector : 'jumpbox_forum',
		topicSelector : 'jumpbox_topic',
		messageTagPrefix : 'message_tag_',
		button : 'search_submit',
		form : 'searchForm'
	}
};
