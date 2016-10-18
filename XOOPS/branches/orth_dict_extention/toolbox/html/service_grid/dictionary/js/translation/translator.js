
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
			onSuccess: this._delegate.translationSucceeded.bind(this._delegate),
			onFailure: this._delegate.translationFailured.bind(this._delegate),
			onComplete: this._delegate.translationFinished.bind(this._delegate)
		};

		new AjaxWrapper(url, action, options);

		//sajax_request_type = 'POST';
		//sajax_do_call(url, [action, params], this.translationFinished.bind(this));
	},

	translationFinished: function(response) {
		if (!this._delegate) return;

		this._delegate.translationFinished();

		try {
			if (!response || response.status != 200) {
				throw new Error();
			}

			this._delegate.translationSucceeded(result);
		} catch (e) {
			this._delegate.translationFailured(e);
		} finally {
			this._delegate.translationFinished();
		}
	}
});

var TranslatorDelegate = Class.create({
	translationSucceeded: function(params, response) {},
	translationFailured: function(e, params, response) {},
	translationFinished: function() {}
});
