
var TranslationWorkspace = Class.create(TranslatorDelegate, {

	_sourceArea: null,
	_targetArea: null,
	_backArea: null,

	_licenseArea: null,
	_timer: null,

	_languageSelectorPair: null,
	_translationButton: null,

	_translator: null,

	_state: null,

	// -
	// init 

	initialize: function(sourceArea, targetArea, backArea, sourceLang, targetLang, translationButton, licenseArea, timeArea) {
		this._sourceArea = sourceArea;
		this._targetArea = targetArea;
		this._backArea = backArea;

		//this._licenseArea = new LicenseArea(licenseArea);
		this._timer = new TranslationTimer(timeArea);

		this._languageSelectorPair = new LanguageSelectorPair(sourceLang, targetLang, TranslationLanguagePairs);
		this._languageSelectorPair._workspace = this;

		this._translationButton = translationButton;

		this._translator = new Translator(this);

		this._state = TranslationWorkspace.State.Waiting;

		this.initEventListeners();
	},

	initEventListeners: function() {
		this._translationButton.observe('click', this.translationButtonClicked.bind(this));
	},

	translate: function() {
		this.changeState(TranslationWorkspace.State.Translating);

		var sourceLang = this._languageSelectorPair.sourceLanguage();
		var targetLang = this._languageSelectorPair.targetLanguage();
		var source = this._sourceArea.value;

		this._translator.translate(sourceLang, targetLang, source);
	},

	clear: function() {
		this._sourceArea.value = '';
		this._targetArea.value = '';
		this._backArea.value = '';
	},

	stop: function() {
		this._translator._delegate = null;
		this._translator = new Translator(this);
		this.changeState(TranslationWorkspace.State.Waiting);
	},

	changeState: function(state) {
		if (this._state == state) {
			return;
		}

		switch (state) {
			case TranslationWorkspace.State.Waiting:
				this._translationButton.value = Const.Message.Translate;
				this._timer.stop();
				$('translation-loading').hide();
				break;
			case TranslationWorkspace.State.Translating:
				this._translationButton.value = Const.Message.Stop;
				this._timer.start();
				$('translation-loading').show();
				break;
		}

		this._state = state;
	},

	// -
	// Accessor
	
	sourceArea: function() {
		return this._sourceArea;
	},

	targetArea: function() {
		return this._targetArea;
	},

	backArea: function() {
		return this._backArea;
	},

	// -
	// Action
	
	sourceLanguageChanged: function(lang) {
		var dir = (lang == 'ar') ? 'rtl' : 'ltr';

		this._sourceArea.dir = dir;
		this._backArea.dir = dir;
	},

	targetLanguageChanged: function(lang) {
		var dir = (lang == 'ar') ? 'rtl' : 'ltr';

		this._targetArea.dir = dir;
	},

	translationButtonClicked: function() {
		if (this._state == TranslationWorkspace.State.Translating) {
			this.stop();
			return;
		}

		if (!this._sourceArea.value) return; 

		this._targetArea.value = '';
		this._backArea.value = '';
		this.translate();
	},

	// -
	// Delegate

	translationSucceeded: function(translator, params, response) {
		if (translator != this._translator) return;

		var result = response.responseText.evalJSON();

		if (result.status.toUpperCase() != 'OK') {
			throw new Error();
		}

		this._targetArea.value = result.contents.Translation;
		this._backArea.value = result.contents.BackTranslation;
		//this._licenseArea.update(result.contents.LicenseInformation);
	},

	translationFailured: function(translator, e, params, response) {
		if (translator != this._translator) return;

		alert('Error: ' + e.message);
	},

	translationFinished: function(translator) {
		if (translator != this._translator) return;

		this.changeState(TranslationWorkspace.State.Waiting);
	}
});

TranslationWorkspace.State = {
	Waiting: 0,
	Translating: 1
};
