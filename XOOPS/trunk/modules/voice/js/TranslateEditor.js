//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
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
var TranslateEditor = Class.create({
	publisher : null,
	translator : null,
	
	setPublisher : function(publisher) {
		this.publisher = publisher;
	},
	
	setTranslator : function(translator) {
		this.translator = translator;
	},
	
	parseSourceText : function(textLang) {
		try{
			this.publisher.displayParsingInfo(true);
			var text = this.translator.isIntermediate()
				? this.publisher.getIntermediateText() : this.publisher.getSourceText();
			var params = $H({
				sourceLang : textLang
				, source : text
			}).toQueryString();
			new Ajax.Request(
				"./php/ajax/parse-text.php"
				, {
					method : 'post'
					, parameters : params
					, onSuccess : function(response) {
						try {
							var dSentences = response.responseText.evalJSON()[0];
							var sentences = this.cleanupSentences(dSentences);
							if(this.translator.isIntermediate()) {
								this.publisher.setIntermediateText(sentences);
							} else {
								this.publisher.setSourceText(sentences);
							}
							this.publisher.displayParsingInfo(false);
							this.translator.callbackTranslate(sentences);
						}catch(e){
							callRaiseError(e);
						}
					}.bind(this)
				});
		}catch(e){
			callRaiseError(e);
		}
	},
	
	cleanupSentences : function(dirtySentences) {
		cleanSentences = [];
		for(var i = 0; i < dirtySentences.length; i++){
			var value = dirtySentences[i].replace(/&#039;/gi, "'");
			value = value.unescapeHTML();
			value = value.replace(/<\/?[^>]+>/gi, " ");
			value = value.replace(/ {2,}/gi, " ");
			var sentence = value.strip();
			if(sentence == ''){
				sentence = '[[#ret]]';
			}
			cleanSentences[i] = sentence;
		}
		return cleanSentences;
	},
	
	callRaiseError : function(error) {
		this.publisher.callRaiseError(error);
	}
});