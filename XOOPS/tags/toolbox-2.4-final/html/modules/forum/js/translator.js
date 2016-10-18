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
var Translator = Class.create();
var AJAX_PATH = './?page=ajax';
Translator.prototype = {
	initialize : function(sourceTextId) {
		this.sourceTextAreaElement = $(sourceTextId);
		this.targetTextAreaElementPrefix = sourceTextId + '-';
		this.backTranslationTextAreaElementPrefix = 'back-' + sourceTextId + '-';
		
		Event.observe($(sourceTextId + '-translation-button'), 'click', function(event) {
			this.translate();
		}.bind(this));
	},

	translate : function() {
		var sourceText = this.sourceTextAreaElement.value || this.sourceTextAreaElement.innerHTML;
		var callObj = {
			sourceText : sourceText
		};
		new Ajax.Request(AJAX_PATH, {
			postBody : $H(callObj).toQueryString(),
			onSuccess : function(transport) {
				var response = transport.responseText.evalJSON();

				$H(response.contents).each(function(pair) {
					$(this.targetTextAreaElementPrefix + pair.key).value = pair.value.translation.contents;
					$(this.backTranslationTextAreaElementPrefix + pair.key).innerHTML = pair.value.backTranslation.contents;
				}.bind(this));
				
			}.bind(this),
			onFailure : function() {
				
			},
			onException : function(transport, exception) {
				alert(exception.message);
			}
		});
	}
};