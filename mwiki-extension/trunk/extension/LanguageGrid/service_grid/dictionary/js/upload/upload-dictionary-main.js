
Event.observe(window, 'load', function() {
	var uploadButton = $('upload-dictionary-edit');

	new UploadDictionaryWorkspace(uploadButton);
});