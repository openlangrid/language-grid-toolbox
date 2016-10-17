
var UploadDictionaryPane = Class.create({

	_delegate: null,
	_dialog: null,

	initialize:function(delegate) {
		this._delegate = delegate;
		this._dialog = SingletonDialog.instance();
	},

	createPane: function() {
		var wrapper = document.createElement('div');
		wrapper.id = 'import-dictionary-pane';

		var form = document.createElement('form');
		form.id = 'dict-upload-form';
		form.enctype = 'multipart/form-data';
		form.action = Const.URL.UpLoad;
		form.method = 'post';
		form.target = 'dummyframe';

		var title = document.createTextNode(Const.Message.UploadFile);
		form.appendChild(title);
		
		var fileInput = document.createElement('input');
		fileInput.type = 'file';
		fileInput.id = 'dictfile';
		fileInput.name = 'dictfile';
		fileInput.size = 30;
		form.appendChild(fileInput);

		var hidden = document.createElement('input');
		hidden.type = 'hidden';
		hidden.name = 'title_db_key';
		hidden.value = Const.Wiki.TitleDBKey;
		form.appendChild(hidden);

		this._status = document.createElement('div');
		this._status.id = 'import-dictionary-status';
		form.appendChild(this._status);
		
		var buttonSet = document.createElement('div');
		buttonSet.id = 'import-dictionary-button-set';

		var cancelLink = document.createElement('span');
		cancelLink.appendChild(document.createTextNode(Const.Message.Cancel));
		cancelLink.className = 'link';
		Event.observe(cancelLink, 'click', this._delegate.cancelButtonClicked.bind(this._delegate));
		buttonSet.appendChild(cancelLink);
		
		var uploadButton = document.createElement('input');
		uploadButton.value = Const.Message.Upload;
		uploadButton.type = 'submit';
		uploadButton.className = 'import-dictionary-button';
		buttonSet.appendChild(uploadButton);

		form.appendChild(buttonSet);
		wrapper.appendChild(form);
		
		var dummyFrame = document.createElement('iframe');
		dummyFrame.id = 'dummyframe';
		dummyFrame.name = 'dummyframe';
		dummyFrame.style.display = 'none';
		wrapper.appendChild(dummyFrame);
		
		return wrapper;
	},

	show: function() {
		this._dialog.show(Const.Message.UploadDictionary, this.createPane(), 410);
	},

	hide: function() {
		this._dialog.hide();
	}
});