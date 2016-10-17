
var Translator = Class.create({

	_delegate: null,

	initialize: function(delegate) {
		this._delegate = delegate;
	},

	translate: function(sourceLang, targetLang, source) {
		var url = 'LanguageGridAjaxController::invoke';
		var action = 'Translation:Translate';

		var params = Object.toQueryString({
			sourceLanguage: sourceLang,
			targetLanguage: targetLang,
			source: source,
			title: Const.Wiki.Title
		});

		var options = {
			parameters: params,
			onSuccess: this._delegate.translationSucceeded.bind(this._delegate, this),
			onFailure: this._delegate.translationFailured.bind(this._delegate, this),
			onComplete: this._delegate.translationFinished.bind(this._delegate, this)
		};

		new AjaxWrapper(url, action, options);
	}
});

var TranslatorDelegate = Class.create({
	translationSucceeded: function(params, response) {},
	translationFailured: function(e, params, response) {},
	translationFinished: function() {}
});
