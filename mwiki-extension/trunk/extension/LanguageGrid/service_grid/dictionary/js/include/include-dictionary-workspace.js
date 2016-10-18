
var ImportDictionaryWorkspace = Class.create(ImportDictionaryManagerDelegate, {

	_button: null,
	_area: null,
	_dictionaries: null,

	_pane: null,

	_manager: null,

	initialize: function(button, area, dictionaries) {
		this._button = button;
		this._area = area;
		this._dictionaries = dictionaries;

		this._pane = new ImportDictionaryPane(this);
		this._manager = new ImportDictionaryManager(this);

		this.update();

		this.initEventListeners();
	},

	initEventListeners: function() {
		this._button.observe('click', this.buttonClicked.bind(this));
	},

	update: function() {
		var table = document.createElement('table');
		table.id = 'import-dictionary-table';

		this._dictionaries.each(function(dict, i) {
			var row = this.createRow(dict, i);
			table.appendChild(row);
		}.bind(this));

		this._area.update(table);
	},

	createRow: function(dict, i) {
		var tr = document.createElement('tr');

		var titleTd = document.createElement('td');
		titleTd.innerHTML = '<a href="./?title=' + dict + '&action=edit&pagedict">' + dict + '</a>';

		var deleteTd = document.createElement('td');
		deleteTd.className = 'delete';

		var deleteButton = document.createElement('span');
		deleteButton.className = 'link';
		deleteButton.innerHTML = Const.Message.Delete;

		Event.observe(deleteButton, 'click', this.deleteButtonClicked.bindAsEventListener(this, dict, i));

		deleteTd.appendChild(document.createTextNode('['));
		deleteTd.appendChild(deleteButton);
		deleteTd.appendChild(document.createTextNode(']'));
		tr.appendChild(titleTd);
		tr.appendChild(deleteTd);

		return tr;
	},

	deleteButtonClicked: function(e, title, i) {
		this._manager.deleteDictionary(title);
		this._dictionaries.splice(i, 1);
		this.update();
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

		this._dictionaries.unshift(params.title);
		this.update();
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

		this._pane.setStatus(Const.Message.NowIncluding);
		this._manager.importDictionary(title);
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
