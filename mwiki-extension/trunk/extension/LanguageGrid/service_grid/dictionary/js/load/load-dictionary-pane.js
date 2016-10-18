
var LoadDictionaryPane = Class.create({

	_delegate: null,
	_dialog: null,

	_titleInput: null,
	_status: null,

	initialize: function(delegate) {
		this._delegate = delegate;
		this._dialog = SingletonDialog.instance();
	},

	// -
	// Accessor

	title: function() {
		return this._titleInput.value;
	},

	setStatus: function(state) {
		this._status.innerHTML = state;
	},

	createPane: function() {
		var wrapper = document.createElement('div');
		wrapper.id = 'import-dictionary-pane';

		var title = document.createTextNode(Const.Message.Title);
		wrapper.appendChild(title);

		this._titleInput = document.createElement('input');
		this._titleInput.id = 'import-dictionary-title';
		this._titleInput.type = 'text';
		wrapper.appendChild(this._titleInput);

		this._status = document.createElement('div');
		this._status.id = 'import-dictionary-status';
		wrapper.appendChild(this._status);

		var buttonSet = document.createElement('div');
		buttonSet.id = 'import-dictionary-button-set';

		var cancelLink = document.createElement('span');
		cancelLink.appendChild(document.createTextNode(Const.Message.Cancel));
		cancelLink.className = 'link';
		Event.observe(cancelLink, 'click', this._delegate.cancelLinkClicked.bind(this._delegate));
		buttonSet.appendChild(cancelLink);
        
		var importButton = document.createElement('input');
		importButton.value = Const.Message.Import;
		importButton.type = 'button';
		importButton.className = 'import-dictionary-button';
		Event.observe(importButton, 'click', this._delegate.importButtonClicked.bind(this._delegate));
		buttonSet.appendChild(importButton);

		wrapper.appendChild(buttonSet);

		return wrapper;
	},

	show: function() {
		this._dialog.show(Const.Message.ImportDictionary, this.createPane(), 410);
		this._titleInput.focus();
	},

	hide: function() {
		this._dialog.hide();
	}
});
