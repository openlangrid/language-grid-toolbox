var WordTableController = {
	// ヘッダ行に表示する言語の数
	DISPLAY_NUM: 2,
	
	show: function() {
		this.$.show();
	},
	
	hide: function() {
		this.$.hide();
	},
	
	clear: function() {
		this.$.down("tbody").update("");
	},
	
	onChangeDisplayLanguage: function(targetSelectElement) {
		var newLanguage = $(targetSelectElement).getValue();
		var oldLanguage = $(targetSelectElement).up("th").down('input').getValue();
		this._swapLanguage(newLanguage, oldLanguage);
		this._updateVisibleLanguages();
	},
	
	getPagenateInfo: function() {
		return Form.serialize(this.$.down("tfoot"),{hash: true});
	},
	
	getCurrentPage: function() {
		return this.$.select("tfoot .page-link span").pluck("innerHTML").detect(function(e){
			return e.match(/^\d+$/);
		});
	},
	
	resetState: function() {
		this.languageOrder = null;
		this.clear();
	},
	
	_getOptionsForLanguages: function(languages, selectedLanguage) {
		return languages.map(function(lang){
			var name = Language.getNameByTag(lang);
			return (selectedLanguage == lang) ?
					"<option value='#{langTag}' selected>#{langName}</option>".interpolate({langTag: lang, langName: name}):
					"<option value='#{langTag}'>#{langName}</option>".interpolate({langTag: lang, langName: name});
		});
	},
	
	_swapLanguage: function(langTagA, langTagB) {
		var headers = this.$.select("thead th");
		var langAIndex = headers.indexOf(this.$.down("thead th." + langTagA));
		var langBIndex = headers.indexOf(this.$.down("thead th." + langTagB));
		this.$.select("thead tr, tbody tr").each(this._swapElementFunction(langAIndex, langBIndex));
		this.$.select("thead th").each(function(e){
			var inputTag = e.down('input');
			var select = e.down("select");
			if (inputTag && select) { 
				var lang = inputTag.getValue();
				select.setValue(lang);
			};
		});
	},
	
	_swapElementFunction: function(langAIndex, langBIndex) {
		return function(tr) {
			var a = tr.cells[langAIndex], b = tr.cells[langBIndex]
			var cloneA = a.cloneNode(true);
			a.parentNode.replaceChild(b.cloneNode(true), a);
			b.parentNode.replaceChild(cloneA, b);
		};
	},
	
	_getStyleDisplayLanguageSelector: function(langTag) {
		return "#" + this.$.id + " ." + langTag;
	},
	
	_isVisibleLanguage: function(langTag) {
		return !CSSUtil.isSetStyleRule(this._getStyleDisplayLanguageSelector(langTag), "display: none;");
	},
	
	_hideLanguage: function(langTag) {
		if(this._isVisibleLanguage(langTag)) {
			CSSUtil.addStyleRule(this._getStyleDisplayLanguageSelector(langTag), "display: none;");
		}
	},
	
	_showLanguage: function(langTag) {
		CSSUtil.removeStyleRule(this._getStyleDisplayLanguageSelector(langTag), "display: none;");	
	},
	
	_showAllLanguage: function() {
		this._getLanguageListCurrentOrder().each(this._showLanguage.bind(this));
	},
	
	_updateVisibleLanguages: function() {
		this._showAllLanguage();
		var langs = this._getLanguageListCurrentOrder();
		if(langs.length > this.DISPLAY_NUM) {
			langs.slice(this.DISPLAY_NUM - langs.length).each(this._hideLanguage.bind(this));
		}
		langs.slice(0, this.DISPLAY_NUM).each(function(lang){
			var select = this.$.down("th." + lang + " select");
			if(select) select.setValue(lang);
		}.bind(this));
		this.languageOrder = this._getLanguageListCurrentOrder();
	},
	
	_sortLanguages: function(languages) {
		if(this.languageOrder) {
			languages = languages.sort(function(langA, langB){
				if(this.languageOrder.indexOf(langA) != -1 && this.languageOrder.indexOf(langB) != -1) {
					return this.languageOrder.indexOf(langA) - this.languageOrder.indexOf(langB);	
				} else {
					return this.languageOrder.indexOf(langB);
				}
			}.bind(this));
		}
		return languages;
	},
	
	_getLanguageListCurrentOrder: function() {
		return this.$.select("thead th input").invoke('getValue');
	}
};
