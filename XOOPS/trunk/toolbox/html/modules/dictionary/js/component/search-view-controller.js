var SearchViewController = {
	TMPL_SRC_LANGUAGE_OPTION: "<option value='#{langTag}'>#{langName}</option>",
	
	TMPL_TGT_LANGUAGE_LI: "<li><label><input type='checkbox' checked name='targetLang[]' value='#{langTag}'>&nbsp;#{langName}</label></li>",
	
	initialize: function() {
		/*
		new Form.Observer(this.$.down('form'), 1.5, function(element, value){
			var params = SearchViewController.serializeParameters(true);
			if(params.keyword) Communication.countSearch(params);
		});*/
	},
	
	beforeAppear: function() {
		this.loadTargetLanguage();
	},
	
	loadTargetLanguage: function() {
		Communication.loadTargetLanguages(this);
	},
	
	afterLoadTargetLanguages: function(languages) {
		this.setLanguages(languages);
		this.onChangeSourceLanguage(this.$.down("#dictionary-source-language-area select"));
	},
	
	setLanguages: function(languages) {
		languages = languages.map(function(lang){ 
			return {langTag:lang, langName:Language.getNameByTag(lang) }
		}).sort(function(a, b){
			return a.langName.charAt(0) > b.langName.charAt(0);
		});
		
		var options = languages.map(function(lang){
			return SearchViewController.TMPL_SRC_LANGUAGE_OPTION.interpolate(lang);
		}).join("");
		this.$.down("#dictionary-source-language-area").update(
			"<select name='sourceLang' onChange='SearchViewController.onChangeSourceLanguage(this)'>" + options + "</select>"
		);
		
		var lists = languages.map(function(lang) {
			return SearchViewController.TMPL_TGT_LANGUAGE_LI.interpolate(lang);
		}).join("");
		this.$.down(".search-target-language-container").update(lists);
	},
	
	onChangeSourceLanguage: function(target) {
		var lang = $(target).getValue();
		this.$.select(".search-target-language-container li").each(function(e){
			e.removeClassName("disabled").down("input").enable();		
		});
		var checkbox = this.$.down(".search-target-language-container").down('input[value="'+lang+'"]');
		checkbox.disable().up("li").addClassName("disabled");
	},
	
	onChangeTargetLanguage: function(target) {
		var params = this.$.down("form").serialize({hash: true});
		var a = $(target).up("label").next("a");
		if(params.target_language == "all") {
			this.$.down(".search-target-language-container").hide();
			$(target).up("label").next("a").addClassName('disabled').removeClassName("open").stopObserving("click", this.toggleTargetLanguages);
			this.$.select(".search-target-language-container input").each(function(e){ e.checked = true });
		} else {
			$(target).up("label").next("a").removeClassName("disabled").observe("click", this.toggleTargetLanguages);
			this.$.select(".search-target-language-container input").each(function(e){ e.checked = false });
		}
	},
	
	toggleTargetLanguages: function() {
		SearchViewController.$.down('.search-target-language-container').toggle();
		SearchViewController.$.down("form .target-languages a").toggleClassName("open");
	},
	
	search: function() {
		var params = this.$.down("form").serialize({hash: true});
		if (params.keyword == '') {
			alert(Const.Message.Error.keywordEmpty);
		} else if (!params["targetLang[]"] || [].concat(params["targetLang[]"]).length < 1) {
			alert(Const.Message.Error.selectLanguage);
		} else {
			this.Table.hide();
			this.hideAllMessages();
			this.showIndicator();
			Communication.search(1, 10);
		}
		
		return false;
	},
	
	afterSearch: function(contents, isSuccess) {
		if(isSuccess) {
			if(contents.results.length > 0) {
				this.Table.clear();
				(function(){
					this.Table.appendRowsAll(contents.results);
					this.Table.show();
					new Pager(contents.paginateInfo, "SearchViewController").paginate(this.Table.$.down("tfoot td"));
				}).bind(this).defer();
			} else {
				this.showNotFoundMessage();
			}
		}
		this.hideIndicator();
	},
	
	afterCountSearch: function(contents, isSuccess) {
		this.$.down(".count-result").update(contents);
	},
	
	getSourceLanguage: function() {
		return this.$.down("#dictionary-source-language-area select").getValue();
	},
	
	changePerPage: function(perPage) {
		this.perPage = perPage;
		this.showIndicator();
		Communication.search(1, perPage);
	},
	
	reload: function(pageNo) {
		this.showIndicator();
		if(!pageNo) pageNo = parseInt(this.Table.getCurrentPage());
		Communication.search(pageNo, this.perPage);
	},
	
	hideAllMessages: function() {
		this.$.select(".messages p").invoke("hide");
	},
	
	showNotFoundMessage: function() {
		this.hideAllMessages();
		this.$.down(".messages .notfound").show();
	},
	
	serializeParameters: function(isHash) {
		return this.$.down("form").serialize({hash: !!isHash});
	},
	
	showIndicator: function(msg) {
		this.$.down(".indicator").setStyle({visibility: "visible"});
		this.$.down(".return2top").hide();
	},
	
	hideIndicator: function() {
		this.$.down(".indicator").setStyle({visibility: "hidden"});
		this.$.down(".return2top").show();
	}
};



SearchViewController.Table = Object.extend({}, WordTableController);
Object.extend(SearchViewController.Table, {
	
	appendRowsAll: function(records) {
		var srcLang = SearchViewController.getSourceLanguage();
		var languages = $H(records.first().languages).keys().without(srcLang);
		languages.unshift(srcLang);
		this.drawHeader(languages);
		records.each(function(record){
			this.appendRow(languages, record);
		}.bind(this));
	},
	
	drawHeader: function(languages) {
		languages = this._sortLanguages(languages);
		var template = TMPL.getHeaderCellForTableSearchResult();
		var options = this._getOptionsForLanguages(languages, "").join("");
		var header = languages.map(function(lang){	
			return template.sub(/<option[^>]*><\/option>/i, options).interpolate({ langTag: lang });
		}.bind(this)).join("");
		header += TMPL.getHeaderResourceNameCellForTableSearchResult();
		this.$.down("thead").update("<tr>" + header + "</tr>");
		this.$.down("tfoot tr td").setAttribute("colspan", languages.length + 1);
		this._updateVisibleLanguages();
	},
	
	appendRow: function(languages, contents) {
		var row = languages.map(function(lang, i) {
			var cell = "<td class='" + lang + "'>#{"+lang+"}</td>";
			return cell.interpolate(contents.languages);
		}).join("");
		row += TMPL.getResourceNameCellForTableSearchResult().interpolate(contents);
		this.$.down("tbody").insert(row);
	}
});

Event.observe(window, 'load', function(){
	SearchViewController.$ = $("search-container");
	SearchViewController.initialize();
	SearchViewController.Table.$ = $("dictionary-search-result");
});