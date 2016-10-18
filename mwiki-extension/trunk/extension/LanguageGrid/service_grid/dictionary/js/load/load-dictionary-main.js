
Event.observe(window, 'load', function() {
	var loadButton = $('load-dictionary');

	new LoadDictionaryWorkspace(loadButton); 
});
