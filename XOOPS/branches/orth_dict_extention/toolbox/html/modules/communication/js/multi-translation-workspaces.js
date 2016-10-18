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
var MultiTranslationWorkspaces = Class.create();
var AJAX_PATH = './?page=ajax';
MultiTranslationWorkspaces.prototype = {
	/**
	 * @params {Array} workspaces
	 */
	initialize : function(workspaces) {
		this.workspaces = workspaces;
		this.initEvent();
	},

	initEvent : function() {
//		var done = new Array();
		this.workspaces.each(function(workspace) {
//			if (done.indexOf(workspace.translationButton.id) == -1) {
				Event.observe(workspace.translationButton, 'click', function(event) {
					Event.stop(event);
//					this.translate();
					this.translate(workspace);
				}.bind(this));
//				done.push(workspace.translationButton.id);
//			}
		}.bind(this));
	},

//	translate : function() {
	translate : function(workspace) {
//		this.workspaces.each(function(workspace){
//			var sourceText = workspace.sourceArea.value || workspace.sourceArea.innerHTML;
			var sourceText = workspace.sourceArea.value;
			var callObj = {
				sourceText : sourceText,
				targetLang : workspace.targetLang.value
			};
			if (sourceText == '') {
				workspace.resultArea.innerHTML = '';
				return false;
			}
			try {
				$$('#bbs-splash span')[0].show();
			} catch (e) {
				workspace.resultArea.innerHTML = Const.Images.loading + ' Now Translating...';
			}
			new Ajax.Request(AJAX_PATH, {
				postBody : $H(callObj).toQueryString(),
				onSuccess : function(transport) {
					var response = transport.responseText.evalJSON();
					var html = new Array();
					$H(response.contents).each(function(pair) {
						html.push('<h3 class="bbs-preview-translation-header">' + Const.Label.translationResult + ': ' + pair.value.translation.targetLanguage + '</h3>');
						html.push('<p class="bbs-translation-result-area-box">' + pair.value.translation.contents.replace(/\n/g, "<br />") + '</p>');
						html.push('<input type="hidden" name="' + workspace.name);
						html.push('[' + pair.key + ']" value="');
						html.push(pair.value.translation.contents + '" />');
						html.push('<h3 class="bbs-preview-translation-header">' + Const.Label.backTranslationResult + ': ' + pair.value.backTranslation.targetLanguage + '</h3>');
						html.push('<p class="bbs-translation-result-area-box">' + pair.value.backTranslation.contents.replace(/\n/g, "<br />") + '</p>');
					}.bind(this));
					workspace.resultArea.innerHTML = html.join('');
				}.bind(this),
				onFailure : function() {
					alert('Internal Server Error!');
				},
				onException : function(transport, exception) {
//					workspace.resultArea.innerHTML = exception.message;
				},
				onComplete : function() {
					try {
						$$('#bbs-splash span')[0].hide();
					} catch(e) {
						;
					}
				}
			});
//		}.bind(this));
	}
};