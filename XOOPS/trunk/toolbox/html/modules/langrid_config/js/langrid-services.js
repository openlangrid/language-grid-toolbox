//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This provides access methods
// to the Language Grid.
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

var LangridServices = {
	
	getSourceLanguages: function() {
	
	},
	
	getTargetLanguages: function() {
		
	},
	
	isEBMT: function(t) {
		return (t == 'kyotou.langrid:KyotoEBMT-nlparser_KNP_EDICT');
	},
	
	getParallelTexts: function(sourceLang, targetLang) {
		if (!this.parallelTexts) {
			this.parallelTexts = [];
		}
		
		return this.parallelTexts;
	},
	
	getTranslationTemplates: function(sourceLang, targetLang) {
		if (!this.translationTemplates) {
			this.translationTemplates = [];
		}
		
		return this.translationTemplates;
	
	},
	
	hasSimilarityCalculations: function(sourceLang, targetLang, both) {
		return (this.getSimilarityCalculations(sourceLang, targetLang).length > 0);
	},
	
	getSimilarityCalculations: function(sourceLang, targetLang, both) {
		if (!this.similarityCalculations) {
			this.similarityCalculations = [];
		}
		
		if (!sourceLang || !targetLang) {
			return this.similarityCalculations;
		}
		
		//var path = sourceLang + "2" + targetLang; 
		//var rPath = targetLang + "2" + sourceLang;
		var path = sourceLang;
		var rPath = targetLang;
		
		var ret = [];
		this.similarityCalculations.each(function(sc) {
			if (sc.supported_languages_paths.indexOf(path) != -1) {
				if (!both || sc.supported_languages_paths.indexOf(rPath) != -1) {
					ret.push(sc);
				}
			}
		});
		
		return ret;
	}
};