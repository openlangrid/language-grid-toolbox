
var LanguageSelectorPair = Class.create({

	_sourceLanguage: null,
	_targetLanguage: null,
	_languagePairs: null,
	_workspace: null,

	// -
	// init 

	initialize: function(sourceLang, targetLang, langPairs) {
		this._sourceLanguage = sourceLang;
		this._targetLanguage = targetLang;

		this._languagePairs = $H(langPairs);

		this.updateSourceLanguage('en');
		this.updateTargetLanguage('ja');

		this.initEventListeners();
	},

	initEventListeners: function() {
		this._sourceLanguage.observe('change', this.sourceLanguageChanged.bind(this));
		this._targetLanguage.observe('change', this.targetLanguageChanged.bind(this));
	},

	updateSourceLanguage: function(selectedLang) {
		var langs = this._languagePairs.keys();
		this.updateLanguage(this._sourceLanguage, langs, selectedLang);
	},

	updateTargetLanguage: function(selectedLang) {
		var langs = this._languagePairs.get(this.sourceLanguage());
		this.updateLanguage(this._targetLanguage, langs, selectedLang);
	},

	updateLanguage: function(selector, langs, selectedLang) {
		selector.update();
		langs.each(function(lang) {
			var option = document.createElement('option');
			option.value = lang;
			option.selected = (lang == selectedLang);

			var langName = LanguageUtil.getNameByCode(lang);
			option.appendChild(document.createTextNode(langName));
			selector.appendChild(option);
		});
	},

	// -
	// Accessor

	sourceLanguage: function() {
		return this._sourceLanguage.value;
	},

	targetLanguage: function() {
		return this._targetLanguage.value;
	},

	// -
	// Action

	sourceLanguageChanged: function() {
		this.updateTargetLanguage(this.targetLanguage());
		this._workspace.sourceLanguageChanged(this.sourceLanguage());
	},

	targetLanguageChanged: function() {
		this._workspace.targetLanguageChanged(this.targetLanguage());
	}
});
