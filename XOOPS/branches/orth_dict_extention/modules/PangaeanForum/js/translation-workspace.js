//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides a multilingual
// BBS, where messages can be displayed in multiple languages.
// Copyright (C) 2009  Department of Social Informatics, Kyoto University
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
var TranslationWorkspace = Class.create();
var AJAX_PATH = './?page=ajax';
TranslationWorkspace.prototype = {
	initialize : function(params) {
		this.sourceAreaElement = $(params.sourceArea);
		this.translationButton = $(params.translationButton);
		this.resultAreaElement = $(params.resultArea);
		this.name = params.name;

		Event.observe(this.translationButton, 'click', function(event) {
			this.translate();
		}.bind(this));
	},

	translate : function() {
		var sourceText = this.sourceAreaElement.value || this.sourceAreaElement.innerHTML;
		var callObj = {
			sourceText : sourceText
		};
		new Ajax.Request(AJAX_PATH, {
			postBody : $H(callObj).toQueryString(),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();
				var html = new Array();
				$H(response.contents).each(function(pair) {
					html.push('<textarea disabled="disabled" name="' + this.name);
					html.push('[' + pair.key + ']" cols="60" rows="6">');
					html.push(pair.value.translation.contents + '</textarea>');
					html.push('<p>' + pair.value.backTranslation.contents + '</p>');
				}.bind(this));
				this.resultAreaElement.innerHTML = html.join('');
			}.bind(this),
			onFailure : function() {
				alert('Server Error!');
			},
			onException : function(transport, exception) {
//				this.resultAreaElement.innerHTML = exception.message;
			}
		});
	}
};