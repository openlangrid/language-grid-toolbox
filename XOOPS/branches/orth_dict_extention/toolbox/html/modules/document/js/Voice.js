//  ------------------------------------------------------------------------ //
// This is a module for Language Grid Toolbox. This allows a user to
// translate texts.
// Copyright (C) 2009-2010  Department of Social Informatics, Kyoto University
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
var targetTextChanged = false;
var targetTextWaiting = false;
var VoiceCreator = Class.create({

	_delegate: null,

	initialize: function(delegate) {
		this._delegate = delegate;
	},

	create: function(lang, sources) {
		var count = sources.length;
		var pointer = 0;
		var contents = [];
		
		var options = {
			onSuccess: function(transport) {
				if (!this._delegate) return;
				
				var result = transport.responseText.evalJSON();
				contents.push(result.contents[0]);
				
				if (pointer < count) {
					callAjax();
					return;
				}
				
				if (this._delegate) {
					this._delegate.createFinished(contents);
				}
			}.bind(this),

			onException: function(e) {
				if (!this._delegate || unload) return;
				
				this._delegate.createFailed(e);
			}.bind(this),

			onFailure: function(e) {
				if (!this._delegate) return;
				
				this._delegate.createFailed(e);
			}.bind(this)
		};
		
		var callAjax = function() {
			options.parameters = {
				language: lang,
				'sources[0]': sources[pointer++]
			};
			new Ajax.Request('./php/ajax/create-voices.php', options);
		}.bind(this);
		
		callAjax();
	},

	// -
	// Private Methods

	_buildParameters: function(lang, sources) {
		var p = {
			language: lang
		};

		var j = 0;
		sources.each(function(source, i) {
			var s = this._getValidSource(source);
			if (!s.blank()) {
				p['sources[' + (j++) + ']'] = s; 				
			}
		}.bind(this));

		return Object.toQueryString(p);
	},
	
	_getValidSource: function(source) {
		source = source.stripTags();
		source = source.replace('&nbsp;', '');
		return source;
	}
});

var VoiceCreatorDelegate = Class.create({
	createFinished: function() {},
	createFailed: function() {}
});

var VoiceManager = Class.create(VoiceCreatorDelegate, {

	_lang: null,
	_contents: null,
	_index: -1,
	_viewController: null,
	_id: 0,
	_isAutoPlay: false,
	_prevTime: 0,
	_prevIntervalTime: 0,
	_waitingAutoNext: false,
	_shouldAutoPlay: false,
	_volume: 256,

	initialize: function() {
		try {
			$('qt-message').show();

			this._creator = new VoiceCreator(this);
			this._viewController = new VoiceViewController();

			if (!haveQuickTime()) {
				return;
			}
			
			$('qt-message').update('');
			
			document.observe('sentence:hilight', this.indexChanged.bind(this));
			$('targetLang').observe('change', this.init.bind(this));
			// $('qt-player').observe('qt_ended', this.autoNext.bind(this));
			$('qt-prev').observe('click', this.prev.bind(this));
			$('qt-next').observe('click', this.next.bind(this));
			setTimeout(this.update.bind(this), 300);
			
			setInterval(function() {
				try {
					var p = this.getPlayer();
		
					if (p) {
						var time = p.GetTime();
						var endTime = p.GetEndTime();
						var status = p.GetPluginStatus();
	
						if (!this._waitingAutoNext && status == 'Complete' && endTime != 0 && (time == this._prevIntervalTime)) {
							//console.info(time + ':' + this._prevIntervalTime + ':' + endTime);
							//console.info();
							var p = this._prevIntervalTime;
							setTimeout(function() {
								if (!this._waitingAutoNext && p == time) {
									if (Prototype.Browser.IE)
										this.setAutoPlay(false);
									if (targetTextChanged) {
										targetTextWaiting = true;
									}
								}
							}.bind(this), 100);
						}
						
//						if (this._finish) {
//							setTimeout(function() {
//								if (this._finish && targetTextChanged) {
//									targetTextWaiting = true;
//								}
//							}.bind(this), 1000);
//						}
						
						this._prevIntervalTime = time;
					}
				} catch (e) {
					;
				}
			}.bind(this), 100);
		} catch (e) {
			// console.error(e);
		}
	},

	prev: function() {
		if (this._index == 0) {
			return;
		}
		
		this.setIndex(this._index-1);
	},

	next: function() {
		if ((this._contents.length-1) == this._index) {
			return;
		}
		
		this.setIndex(this._index-0+1);
	},

	autoNext: function() {
		if ((this._contents.length-1) == this._index) {
			this.setAutoPlay(false);
			this._waitingAutoNext = false;
			this.setIndex(0);
			return;
		}
		
		this.setAutoPlay(true);
		this.setIndex(this._index-0+1);
		
		var fn = function() {
			var p = this.getPlayer();
			
			if (p.GetTime() == 0) {
				setTimeout(fn, 10);
			} else {
				this._waitingAutoNext = false;
			}
		};
		
		fn();
	},

	init: function() {
		this._viewController.hidePlayer();
		this._viewController.setMessage();
		this.setAutoPlay(false);
	},

	onBlur: function() {
		//var text = targetArea.getEditorText();
		var text = targetArea.getVoiceText();

		if (text != this._source) {
			targetTextChanged = true;
			this.setSource(text);
			this.setSources(targetArea.getVoiceTexts());
		}
	},

	// -
	// Public Methods

	setState: function(state) {
		if (!haveQuickTime()) {
			return;
		}
		
		this.init();
		
		switch (state) {
		case 'LanguageChanged':
			break;
			
		case 'TranslationStarted':
			break;
			
		case 'TranslationFinished':
			break;
			
		case 'VoiceCreationStarted':
			this._viewController.setMessage(Const.Image.loading + ' ' + Const.Message.voiceCreating);
			break;
			
		case 'VoiceCreationFinished':
			this._viewController.showPlayer();
			break;
		}
	},

	create: function() {
		if (!haveQuickTime()) {
			$('qt-message').show();
			return;
		}
		
		if (!VoiceSetting[this._lang]) {
			this._viewController.hidePlayer();
			this._viewController.setMessage(Const.Message.voiceUnableLanguage);
			return;
		}

		if (!this._sources || !this._sources.length) {
			return;
		}
		
		this._contents = null;

		this.setState('VoiceCreationStarted');
		$('player-wrap').update('');
		this._index = -1;
		this._creator.create(this._lang, this._sources);
	},

	cancel: function() {
		this._creator._delegate = null;
		this._cretor = new VoiceCreator(this);
	},

	restoreContents: function(contents) {
		this._contents = contents;
		this.setState('VoiceCreationFinished');
		this._viewController.setContents(this._contents);
		this.setIndex(0);
	},
	
	// -
	// Delegate Methods
	createFinished: function(contents) {
		this.restoreContents(contents);
		saveInfoData();
	},

	createFailed: function(e) {
		alert(Const.Message.voiceFailured);
		$('player-wrap').update('');
		$('qt-message').update('');
	},

	// -
	// Action
	indexChanged: function(e) {
		this.setIndex(e.memo);
	},

	// -
	// Accessor

	setIndex: function(index) {
		if (this._index == index) {
			return;
		}

		this._index = index;
		try {
			var autoplay = (this._shouldAutoPlay || this.getAutoPlay()) ? 'true' : 'false';
			this._shouldAutoPlay = false;
			var src = this._contents[index] + '?' + (++this._id) || '';

			var html = QT_GenerateOBJECTText(src, '100%', '16', '', 'obj#id', 'qt-player-obj', 'emb#id', 'qt-player-emb', 'autoplay', autoplay, 'enablejavascript', 'true', 'postdomevents', 'true', 'volume', this._volume*100/256);
			
			$('player-wrap').update(html);
			
			this.getPlayer().observe('qt_pause', function() {
				this.setAutoPlay(false);
				this.setHighlightSentenceOfNumber(index, false);
			}.bind(this));

			this.getPlayer().observe('qt_play', function() {
				this.setAutoPlay(true);
				this.setHighlightSentenceOfNumber(index, true);
			}.bind(this));
			
			this.getPlayer().observe('qt_canplay', function() {
				var e = document.activeElement; 
				if (e.tagName == 'IFRAME') {
					e.blur();
					e.focus();
				}
			});

			var c = 0;
			this.getPlayer().observe('focus', function() {
				if (Prototype.Browser.IE) {
					if (!!currentArea) {
						currentArea.editor.focus();
						this.setHighlightSentenceOfNumber(index, true);
					}
					if (c++) {
						this.setHighlightSentenceOfNumber(index, true);
					}
				}
			}.bind(this));

			this._viewController.setIndex(index);
		} catch (e) {
			//console.error(e);
		}
	},

	update: function() {
		try {
			var p = this.getPlayer();

			if (p) {
				this._volume = p.GetVolume();
				
				var time = p.GetTime();
				var endTime = p.GetEndTime();
				
				if (time > this._prevTime) {
					this._waitingAutoNext = false;
					//console.info('playing time = ' + time + ', prev' + this._prevTime);
					if (targetTextChanged && targetTextWaiting) {
						targetTextChanged = false;
						targetTextWaiting = false;
						this._shouldAutoPlay = true;
						this.create();
					}
					if (Prototype.Browser.IE) {
						this.setHighlightSentenceOfNumber(this._index, true);
						this._finish = false;
						this.setAutoPlay(true);
					}
				}
				
				this._prevTime = time;

				if ((time == endTime) && (time != 0 && endTime != 0)) {
					if (!this._finish && Prototype.Browser.IE) {
						this.setHighlightSentenceOfNumber(this._index, false);
					}
					this._finish = true;
					this._waitingAutoNext = true;
					this.autoNext();
				}
			}
		} catch (e) {
//			console.error(e);
		} finally {
			setTimeout(this.update.bind(this), 30);
		}
	},

	play: function() {
		try {
			this.getPlayer().Play();
		} catch (e) {
			// console.info(e);
		}
	},

	getPlayer: function() {
		try {
			if (Prototype.Browser.IE) {
				return $('qt-player-obj');
			} else {
				return $('qt-player-emb');
			}
		} catch (e) {
			//console.info(e);
			return null;
		}
	},

	getAutoPlay: function() {
		return this._isAutoPlay;
	},

	setAutoPlay: function(flag) {
		this._isAutoPlay = flag;
	},

	setLanguage: function(lang) {
		this._lang = lang;
	},

	setSource: function(source) {
		this._source = source;
	},

	setSources: function(sources) {
		this._sources = sources;
	},

	setHighlightSentenceOfNumber : function(index, highlight) {
		if (highlight) {
			sourceArea.highlightSentenceOfNumber(index);
			targetArea.highlightSentenceOfNumber(index);
			backtransArea.highlightSentenceOfNumber(index);
		} else {
			sourceArea.clearHighlight();
			targetArea.clearHighlight();
			backtransArea.clearHighlight();
		}
	}
});

var VoiceViewController = Class.create({

	_contents: null,

	initialize: function() {
		$('qt-save').observe('click', this.saveClicked.bind(this));
	},

	// -
	// Action

	saveClicked: function() {
		if (!this._contents) return;

		var options = {
			dialogMode : 'Save',
			onOk : this.onDialogOk.bind(this),
			willHide: this.willHide.bind(this)
		};
		
		this.dialog = new FileSharingDialog('qt-save-dialog', options);
		this.dialog.show();
		this.hidePlayer();
	},

	onDialogOk: function(item) {
		Object.extend(item, {
			basename: this._contents[voiceManager._index]
		});

		new Ajax.Request('./php/ajax/save-voice.php', {
			asynchronous: false,
			postBody: Object.toQueryString(item),
			onSuccess: this.onSuccess.bind(this)
			//onException: this.onException.bind(this),
			//onFailure: this.onFailure.bind(this),
			//onComplete: this.onComplete.bind(this)
		});
	},
	
	willHide: function() {
		this.showPlayer();
	},

	onSuccess: function() {
	},

	setMessage: function(message) {
		if (!haveQuickTime()) {
			$('qt-message').hide();
			return;
		}
		
		if (!message) message = '';
		$('qt-message').update(message);
	},

	setContents: function(contents) {
		this._contents = contents;
	},

	setIndex: function(index) {
		$('qt-index').update((index-0+1) + ' / ' + this._contents.length);
	},

//	setSource: function(src) {
//		$('qt-player').writeAttribute('src', src);
//	},

	setNext: function(src, index) {
		//$('qt-player').writeAttribute('qtnext' + index, '<' + src + '>T<myself>');
		// $('qt-player').writeAttribute('autoplay', 'true');
	},

	showPlayer: function() {
		$('qt').show();
	},

	hidePlayer: function() {
		$('qt').hide();
	}
});