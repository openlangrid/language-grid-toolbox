
Event.observe(window, 'load', function() {

	if (Object.isArray(TranslationLanguagePairs)) {
		$('translation-area').hide();
		$('translation-message').show();
	} else {
		initWorkspace();
	}

	function initWorkspace() {
		var sourceArea = $('source-area');
		var targetArea = $('target-area');
		var backArea = $('back-area');

		var sourceLang = $('source-language');
		var targetLang = $('target-language');
		var translationButton = $('translation-button');

		//var licenseArea = $('license-area');

		var timeArea = $('translation-time');

		var w = new TranslationWorkspace(sourceArea, targetArea, backArea, sourceLang, targetLang, translationButton, null, timeArea);

		var wc = new TranslationWorkspaceController(w);

		wc.addAction($('clear-button'), 'click', 'clear');

		wc.addAction($('source-font-smaller'), 'click', 'resizeFontSmall');
		wc.addAction($('target-font-smaller'), 'click', 'resizeFontSmall');

		wc.addAction($('source-font-larger'), 'click', 'resizeFontLarge');
		wc.addAction($('target-font-larger'), 'click', 'resizeFontLarge');

		wc.addAction($('source-area-smaller'), 'click', 'resizeAreaSmall');
		wc.addAction($('target-area-smaller'), 'click', 'resizeAreaSmall');

		wc.addAction($('source-area-larger'), 'click', 'resizeAreaLarge');
		wc.addAction($('target-area-larger'), 'click', 'resizeAreaLarge');

		new AreaExpandCollapseController($('translation-area-controller'), $('translation-wrapper'), $('translation-area-expand'), $('translation-area-collapse')); 
		new AreaExpandCollapseController($('back-translation-area-controller'), $('back-translation-wrapper'), $('back-translation-area-expand'), $('back-translation-area-collapse')); 
	}
});
