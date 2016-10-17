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
var Publisher = Class.create( {
		// external objects
		translator : null,
		sa : null,
		ta : null,
		bta : null,
		la : null,
		// parameters
		publishIndex : 0,
		invalidHilightSentenceCount : 0,
		publishingTimerId : null,
		translateTimerId : null,
		backTranslateTimerId : null,
		isLicensed : false,
		isFreeTime : true,
		isCancel : false,
		initDisplay : true,
		
		initialize : function(sourceArea, translateArea, backTranslateArea, licenseArea) {
			this.sa = sourceArea;
			this.ta = translateArea;
			this.bta = backTranslateArea;
			this.la = licenseArea;
		},
		
		init : function() {
			this.publishIndex = 0;
			this.invalidHilightSentenceCount = 0;
			if( ! this.translator.isIntermediate()) {
				this.ta.clearText();
			}
			this.bta.clearText();
			this.la.clear();
			$('time').innerHTML = 0;
			$('back-translate-time').innerHTML = 0;
		},
		
		setTranslator : function() {
			this.translator = translator;
		},
		
		publish : function() {
			this.isFreeTime = false;
			this.publishingTimerId = setInterval(function(){
				try {
					if(this.initDisplay) {
						this.initDisplay = ! this.displayTranslatingSentence();
					}
					if(this.translator.isTranslationFinished(this.publishIndex)) {
						var last = this.translator.getFinishedLastIndex(this.publishIndex);
						var results = this.translator.getTranslationSlicedResults(this.publishIndex, last);
						for(var i = 0; i < results.length; i++, this.publishIndex++) {
							var tr = results[i]['translation'];

							if(tr == "" || tr == "[[#ret]]") {
								this.invalidHilightSentenceCount++;
							}
							if(this.translator.isIntermediate()) {
								this.bta.setEditorText(this.publishIndex - this.invalidHilightSentenceCount, tr);
							} else {
								this.ta.setEditorText(this.publishIndex - this.invalidHilightSentenceCount, tr);
							}
							if(this.translator.isBackTranslation()) {
								var btr = results[i]['backTranslation'];
								this.bta.setEditorText(this.publishIndex - this.invalidHilightSentenceCount, btr);
							}
						}
						if(this.isComplete(this.publishIndex - 1)) {
							this.endProcess("process is completed");
						}
						this.displayTranslatingSentence();
						if(!this.isLicensed) {
							this.la.addLicenses(this.translator.getLicenseInformation());
							this.isLicensed = true;
						}
					}
					if(this.isCancel) {
						this.translator.setCancel();
						if(this.translator.wasCanceled()) {
							this.endProcess("process is canceled");
						}
						return;
					}
				}catch(e) {
					this.callRaiseError(e);
				}
			}.bind(this), 50);
		},
		
		getSourceText : function() {
			return this.sa.getEditorText();
		},
		
		getIntermediateText : function() {
			return this.ta.getEditorText();
		},
		
		setSourceText : function(sentences) {
			this.sa.clearText();
			this.setText(this.sa, sentences);
		},
		
		setIntermediateText : function(sentences) {
			this.ta.clearText();
			this.setText(this.ta, sentences);
		},
		
		setBacktranslationText : function(sentences) {
			this.bta.clearText();
			this.setText(this.bta, sentences);
		},
		
		setText : function(area, sentences) {
			var invalidHilightSentenceCount = 0;
			area.clearText();
			for(var i = 0; i < sentences.length; i++) {
				if(sentences[i] == "" || sentences[i] == "[[#ret]]") {
					invalidHilightSentenceCount++;
				}
				area.setEditorText(i - invalidHilightSentenceCount, sentences[i]);
			}
		},
		
		isComplete : function(nowIndex) {
			if(this.translator.isTranslationComplete()) {
				this.translator.callVoiceGenerator();
				return nowIndex + 1 == this.translator.getSentenceCount();
			}
		},
		
		callRaiseError : function(error) {
			this.endProcess(error);
			alert(Const.Message.Error.TranslateError);
/** *
			alert(Const.Message.Error.TranslateError
				+ "\n\n-------\nMessage:\n  " + error.message
				+ "\nContents:\n  " + (error.contents == undefined ? "" : error.contents));
** */
		},
		
		startTimer : function() {
			if(this.translator.isIntermediate()) {
				this.backTranslateTimerId = setInterval(function(){
					$('back-translate-time').innerHTML = parseInt($('back-translate-time').innerHTML) + 1;
				}.bind(this), 1000);
			} else {
				this.translateTimerId = setInterval(function(){
					$('time').innerHTML = parseInt($('time').innerHTML) + 1;
				}.bind(this), 1000);
			}
		},
		
		endProcess : function(message) {
			clearInterval(this.publishingTimerId);
			this.publishingTimerId = null;
			clearInterval(this.translateTimerId);
			this.translateTimerId = null;
			clearInterval(this.backTranslateTimerId);
			this.backTranslateTimerId = null;
			this.publishIndex = 0;
			this.invalidHilightSentenceCount = 0;
			this.isLicensed = false;
			this.isFreeTime = true;
			this.isCancel = false;
			this.hideTranslationInfo();
			
			Event.fire(document,"dom:TranslationDone");
		},
		
		cancel : function() {
			if( ! this.isFreeTime) {
				this.isCancel = true;
				$('cancelMessage').style.display = 'inline';
				$('translatingMessage').style.display = 'none';
				$('cancelBackMessage').style.display = 'inline';
				$('backtranslatingMessage').style.display = 'none';
			}
		},
		
		displayTranslatingSentence : function() {
			var sentence = this.translator.getTranslatingSentence();
			if(sentence == null || sentence == "undefined" || sentence == "") {
				return false;
			}
			if( ! this.translator.isIntermediate()) {
				$('traslatingSentence').innerHTML = sentence[0].slice(0, 24) + "...";
			}
			$('backtranslatingSentence').innerHTML = sentence[0].slice(0, 24) + "...";
			return true;
		},
		
		displayParsingInfo : function(isDisplay) {
			$('parsing').style.display = isDisplay ? 'inline' : "none";
		},
		
		displayTranslationInfo : function() {			
			if(this.translator.isIntermediate()) {
				$$('#translate-button span')[0].addClassName('btn_gray01');
				$$('#translate-button span')[0].removeClassName('btn_blue01');
				$('back-translate-button').style.display = 'none';
				$('back-translate-cancel-button').style.display = 'inline';
			} else {
				$('translating').style.display = 'inline';
				$('translate-button').style.display = 'none';
				$('cancel-button').style.display = 'inline';
			}
			$$('#back-translate-button span')[0].addClassName('btn_gray01');
			$$('#back-translate-button span')[0].removeClassName('btn_blue01');
		
			$$('#clear-button span')[0].addClassName('btn_gray01');
			$$('#clear-button span')[0].removeClassName('btn_blue01');
	
			$('backtranslating').style.display = 'inline';
		},
		
		hideTranslationInfo : function() {
			$('translating').style.display = 'none';
			$('translatingMessage').style.display = 'inline';
			$('cancelMessage').style.display = 'none';
	
			$('backtranslating').style.display = 'none';
			$('backtranslatingMessage').style.display = 'inline';
			$('cancelBackMessage').style.display = 'none';
	
			$('translate-button').style.display = 'inline';
			$('cancel-button').style.display = 'none';
			$('parsing').style.display = 'none';
	
			$('back-translate-button').style.display = 'inline';
			$('back-translate-cancel-button').style.display = 'none';
			$('translate-parsing').style.display = 'none';
			
			$$('#translate-button span')[0].removeClassName('btn_gray01');
			$$('#translate-button span')[0].addClassName('btn_blue01');
			
			$$('#clear-button span')[0].removeClassName('btn_gray01');
			$$('#clear-button span')[0].addClassName('btn_blue01');
			
			$$('#back-translate-button span')[0].removeClassName('btn_gray01');
			$$('#back-translate-button span')[0].addClassName('btn_blue01');
			this.initDisplay = true;
		}
});