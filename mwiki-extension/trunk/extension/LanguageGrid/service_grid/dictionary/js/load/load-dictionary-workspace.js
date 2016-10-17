
var LoadDictionaryWorkspace = Class.create(LoadDictionaryManagerDelegate, {

	_button: null,

	_pane: null,
	_manager: null,

	initialize: function(button) {
		this._button = button;

		this._pane = new LoadDictionaryPane(this);
		this._manager = new LoadDictionaryManager(this);

		this.initEventListeners();
	},

	initEventListeners: function() {
		this._button.observe('click', this.buttonClicked.bind(this));
	},

	// -
	// Delegate

	addSucceeded: function(params, response) {
		var result = response.responseText.evalJSON();

		if (result.status.toUpperCase() != 'OK') {
			throw new Error(result.message);
		}

		if (result.contents.status.toUpperCase() != 'OK') {
			throw new Error(result.contents.message);
		}

		dictionaryMain._doRefresh();
		this._pane.hide();
	},

	addFailured: function(e, params, response) {
		this._pane.setStatus(e.message);
	},

	importButtonClicked: function() {
		var title = this._pane.title();

		if (!title) {
			return;
		}

		this._pane.setStatus(Const.Message.NowImporting);
		this._manager.loadDictionary(title);
	},

	cancelLinkClicked: function() {
		this._pane.hide();
	},

	// -
	// Action

	buttonClicked: function(e) {
		this._pane.show();
	}
});
