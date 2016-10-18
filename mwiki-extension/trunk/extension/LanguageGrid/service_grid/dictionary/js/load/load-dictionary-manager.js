
var LoadDictionaryManager = Class.create({

	_url: 'LanguageGridAjaxController::invoke',
	_loadAction: 'ImportDictionary:Load',

	_delegate: null,

	initialize: function(delegate) {
		this._delegate = delegate;
	},

	loadDictionary: function(title) {
		var params = Object.toQueryString({
			title: title,
			title_db_key: Const.Wiki.TitleDBKey
		});

		var options = {
			parameters: params,
			onSuccess: this._delegate.addSucceeded.bind(this._delegate),
			onFailure: this._delegate.addFailured.bind(this._delegate)
		};

		new AjaxWrapper(this._url, this._loadAction, options);
	}
});

var LoadDictionaryManagerDelegate = Class.create({
	addSucceeded: function(result){},
	addFailured: function(e){}
});
