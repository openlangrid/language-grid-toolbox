var uploadDictionary = null;

var UploadDictionaryWorkspace = Class.create( {

	_button: null,
	_pane: null,

	initialize: function(button) {
		this._button = button;

		this._pane = new UploadDictionaryPane(this);
        uploadDictionary = this._pane;
        
		this.initEventListeners();
	},

	initEventListeners: function() {
		this._button.observe('click', this.buttonClicked.bind(this));
	},
    
	cancelButtonClicked: function() {
		this._pane.hide();
	},
    
	// -
	// Action

	buttonClicked: function(e) {
		this._pane.show();
	}
});