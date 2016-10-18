//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate a Web page.
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

var LanguageSelectors = Class.create(Observer, {
	
	/**
	 * Constructor
	 * @param String sourceId
	 * @param String targetId
	 * 
	 */
	initialize: function(sourceLanguageSelector, targetLanguageSelector) {
		Logger.info('LanguageSelectors.initialize');

		this.sourceLanguageSelector = sourceLanguageSelector;
		this.targetLanguageSelector = targetLanguageSelector;
	},

	/**
	 * @override
	 */
	update: function(o, arg) {
		Logger.info('LanguageSelectors.update');
		this.sourceLanguageSelector.setValue(o.getSourceLanguage());

		this.targetLanguageSelector.setContents(LanguageUtil.toObject(o.getTargetLanguages()));
		this.targetLanguageSelector.setValue(o.getTargetLanguage());
		
		$('wc-source-language').update(LanguageUtil.toValue(o.getSourceLanguage()));
		$('wc-target-language').update(LanguageUtil.toValue(o.getTargetLanguage()));
	}
});