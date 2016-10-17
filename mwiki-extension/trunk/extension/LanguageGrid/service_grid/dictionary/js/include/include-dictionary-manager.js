
var ImportDictionaryManager = Class.create({

	_url: 'LanguageGridAjaxController::invoke',
	_addAction: 'ImportDictionary:Add',
	_deleteAction: 'ImportDictionary:Delete',

	_delegate: null,

	initialize: function(delegate) {
		this._delegate = delegate;
	},

	importDictionary: function(title) {
		var params = Object.toQueryString({
			title: title,
			title_db_key: Const.Wiki.TitleDBKey
		});

		var options = {
			parameters: params,
			onSuccess: this._delegate.addSucceeded.bind(this._delegate),
			onFailure: this._delegate.addFailured.bind(this._delegate)
		};

		new AjaxWrapper(this._url, this._addAction, options);
	},

	deleteDictionary: function(title) {
		var params = Object.toQueryString({
			title: title,
			title_db_key: Const.Wiki.TitleDBKey
		});

		var options = {
			parameters: params,
			onSuccess: this._delegate.deleteSucceeded.bind(this._delegate),
			onFailure: this._delegate.deleteFailured.bind(this._delegate)
		};

		new AjaxWrapper(this._url, this._deleteAction, options);
	}
});

var ImportDictionaryManagerDelegate = Class.create({
	addSucceeded: function(result){},
	addFailured: function(e){},
	deleteSucceeded: function(result){},
	deleteFailured: function(e){}
});
