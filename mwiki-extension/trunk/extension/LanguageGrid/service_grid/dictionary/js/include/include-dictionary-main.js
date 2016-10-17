
Event.observe(window, 'load', function() {
	var importButton = $('import-dictionary-button');
	var importArea = $('import-dictionary-area');

	new ImportDictionaryWorkspace(importButton, importArea, ImportedDictionaries); 
});
