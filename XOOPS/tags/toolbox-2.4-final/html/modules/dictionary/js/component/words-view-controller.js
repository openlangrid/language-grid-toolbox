var WordsViewController = {
	initialize: function() {
		this.$.down(".close button").observe("click", this.onBackToDictionariesView.bind(this), false);
		this.$.down(".save button").observe("click", this.onSave.bind(this), false);
		this.$.down(".add button").observe("click", this.onNewRecord.bind(this), false);
		this.$.down(".add-language button").observe("click", this.onAddLanguages.bind(this), false);
		
	},
	
	show: function() {
		this.$.show();
	},
	
	hide: function() {
		this.$.hide();
		this.hideAllMessages();
	},
	
	afterLoadDictionary: function(dictionaryId, contents) {
		this.setDictionaryId(dictionaryId);
		this.setDictionaryProperties(contents);
		this.Table.clear();
		(function(){
			this.Table.appendRowsAll(contents.dictionary);
			new Pager(contents.paginateInfo, "WordsViewController").paginate(this.Table.$.down("tfoot td"));
			this.hideIndicator();
		}).bind(this).defer();
	},
	
	setDictionaryProperties: function(contents) {
		this.setDictionaryName(contents.dictionaryName);
		this.setLanguages($H(contents.dictionary.first()).keys().without("row"));
		this.setUpdateDate(contents.updateDate);
		this.$.removeClassName("permitEdit");
		this.$.select(".nav .create button").each(this._disableButton);
		this._disableButton(this.$.down("ul.nav .save button"));
		
		if(contents.permission.user.edit) {
			this.$.addClassName("permitEdit");
			this.$.select(".nav .create button").each(this._enableButton);
		}
		this.permission = contents.permission;
	},
	
	setDictionaryName: function(name) {
		this.$.down("h1").update(name);
	},
	
	setLanguages: function(langs) {
		var langs = langs.map(function(lang){
			return Language.getNameByTag(lang);
		}).join(", ");
		this.$.down("p .languages").update(langs);
	},
	
	getLanguages: function() {
		return this.$.down("p .languages").innerHTML.split(", ").map(function(name){
			return Language.getTagByName(name);
		});
	},
	
	setUpdateDate: function(updateDate) {
		this.$.down('input[name="updateDate"]').setValue(updateDate);
	},
	
	setDictionaryId: function(dictionaryId) {
		this.$.down('input[name="dictionaryId"]').setValue(dictionaryId);
	},
	
	getDictionaryId: function() {
		return this.$.down('input[name="dictionaryId"]').getValue();
	},
	
	onBackToDictionariesView: function(e) {
		if(this.confirmDiscardChanges()) {
			DialogViewController.hide();
			this.hide();
			DictionariesViewController.reload();
			DictionariesViewController.show();
		}
	},
	
	confirmDiscardChanges: function() {
		if(!this.Table._isModified() || confirm(Const.Message.Warning.discardChanges)) {
			this.Table.resetState();
			return true;
		}
		return false;
	},
	
	onSave: function(e) {
		if(this._isEnabled(e.element())) DialogViewController.SaveDictionary.open(this.$.down("h1").innerHTML);
	},
	
	onNewRecord: function(e) {
		if(this._isEnabled(e.element())) WordsViewController.Table.add();
	},
	
	onRemoveLanguage: function(lang) {
		this.Table.selectLanguage(lang);
		if(!this._validateForRemoveLanguage()) {
			this.Table.unSelectLanguage();
		} else {
			this.setLanguages(this.getLanguages().without(lang));
			this.Table.removeLanguage(lang);
		}
		return false;
	},
	
	onAddLanguages: function(e) {
		if(this._isEnabled(e.element())) DialogViewController.AddLanguage.open();
	},
	
	addLanugages: function(langs) {
		if(!Object.isArray(langs)) langs = [langs];
		this.setLanguages(this.getLanguages().concat(langs));
		langs.each(function(lang){ 
			WordsViewController.Table.addLanguage(lang);
		});
	},
	
	serializeParameters: function(isHash) {
		return this.$.down("form").serialize({hash: !!isHash});
	},
	
	hideAllMessages: function() {
		this.$.select(".messages p").invoke("hide");
	},
	
	showSaveFinishedMessage: function() {
		this.hideAllMessages();
		this.$.down(".messages .save-finished").show();
	},
	
	showDeleteRecordFinishedMessage: function() {
		this.hideAllMessages();
		this.$.down(".messages .delete-record-finished").show();
	},
	
	_validateForRemoveLanguage: function() {
		if(this.Table.$.select("thead th").length <= 2+1) {
			alert(Const.Message.Error.atLeastTwoLanguages);
			return false;
		} else if(!confirm(Const.Message.Confirm.deleteSelectedRows)) {
			 return false;
		}
		return true;
	},
	
	changePerPage: function(perPage) {
		if(this.confirmDiscardChanges()) {
			this.perPage = perPage;
			this.showIndicator();
			Communication.loadDictionary(this.getDictionaryId(), 1, perPage);
		}
	},
	
	reload: function(pageNo) {
		if(this.confirmDiscardChanges()) {
			this.showIndicator();
			if(!pageNo) pageNo = parseInt(this.Table.getCurrentPage());
			Communication.loadDictionary(this.getDictionaryId(), pageNo, this.perPage);
		}
	},
	
	showIndicator: function(msg) {
		var html = Const.Images.loading + "&nbsp;";
		html += (msg) ? msg : Const.Message.Status.nowLoading;
		this.$.down(".indicator").update(html).setStyle({visibility: "visible"});
	},
	
	hideIndicator: function() {
		this.$.down(".indicator").setStyle({visibility: "hidden"});
	},
	
	enableSaveButton: function() {
		this._enableButton(this.$.down("ul.nav .save button"));
	},
	
	_enableButton: function(btn) {
		btn.removeClassName("disabled");
	},
	
	_disableButton: function(btn) {
		btn.addClassName("disabled");
	},
	_isEnabled: function(element) {
		element = element.tagName.toLowerCase() == "button" ? element : element.up("button");
		return !element.hasClassName("disabled");
	}
};

WordsViewController.Table = Object.extend({}, WordTableController);
Object.extend(WordsViewController.Table, {
	
	appendRowsAll: function(dictionaryContents) {
		var langs = $H(dictionaryContents.shift()).keys().without("row");
		this.drawHeader(langs);
		(function(i) {
			(10).times(function(){
				if(i < dictionaryContents.length)
					WordsViewController.Table.appendRow(langs, dictionaryContents[i]);
				i++;
			});
			
			if(i < dictionaryContents.length - 1) {
				var func = arguments.callee;
				func.defer(i);
			}
		})(0);
		
		if(dictionaryContents.length == 0) this.add();
	},
	
	drawHeader: function(languages) {
		languages = this._sortLanguages(languages);
		var template = TMPL.getHeaderCellForTableWords();
		var header = languages.map(function(lang){
			var options = WordsViewController.Table._getOptionsForLanguages(languages, lang).join("");
			return template.sub(/<option[^>]*><\/option>/i, options).interpolate({
				langTag: lang, 
				langName: Language.getNameByTag(lang)
			});
		}).join("") + TMPL.getHeaderRemoveCellForTableWords();
		
		this.$.down("thead").update("<tr>" + header + "</tr>");
		this.$.down("tfoot tr td").setAttribute("colspan", languages.length + 1);
		this._updateVisibleLanguages();
	},
	
	_getOptionsForLanguages: function(languages, selectedLanguage) {
		return languages.map(function(lang){
			var name = Language.getNameByTag(lang);
			return (selectedLanguage == lang) ?
					"<option value='#{langTag}' selected>#{langName}</option>".interpolate({langTag: lang, langName: name}):
					"<option value='#{langTag}'>#{langName}</option>".interpolate({langTag: lang, langName: name});
		});
	},
	
	appendRow: function(languages, contents){
		var row = languages.map(function(lang){
			return TMPL.getCellForTableWords().interpolate({
				langTag: lang,
				"word value": contents[lang].escapeHTML().gsub('"', "&quot;"),
				wordDisp: contents[lang].escapeHTML() || "--", 
				rowno: contents["row"]
			});
		}).join("") + TMPL.getRemoveCellForTableWords();
		this.$.down("tbody").insert("<tr class='row"+ contents["row"] +"'>" + row + "</tr>");
	},
	
	clear: function() {
		this.$.down("tbody").update("");
		this.$.down("thead").update("");
	},
	
	add: function() {
		var counter = this.counter = (this.counter || 0) + 1;
		var header = this.$.down("thead tr");
		var row = $R(0, header.cells.length-2).map(function(i){
			var headerCell = $(header.cells[i]);
			return TMPL.getNewCellForTableWords().interpolate({
				langTag: headerCell.classNames().toArray().without("selected").join(""),
				tmpKey: counter
			});
		}).join("") + TMPL.getRemoveCellForTableWords();
		row = "<tr class='newRecord'>" + row + "</tr>";
		this.$.down("tbody").insert({top: row});
	},
	
	remove: function(targetCell) {
		var row = targetCell.up("tr").addClassName("selected");
		if (!confirm(Const.Message.Confirm.deleteSelectedColumns)) {
			row.removeClassName("selected");
			return false;
		}
		var tbody = targetCell.up("tbody");
		this._setModified(row);
		row.hide().select("input").invoke("clear");
		WordsViewController.showDeleteRecordFinishedMessage();
		return false;
	},
	
	removeNewRow: function() {
		this.$.select(".newRecord").invoke("remove");
	},
	
	editCell: function(targetCell, event) {
		if(WordsViewController.$.hasClassName("permitEdit")) {
			targetCell.down("span").hide();
			targetCell.down("input").show().focus();
			this.currentEdit = targetCell.down("input");
			this.valueBeforeEdit = this.currentEdit.getValue();
		}
	},
	
	afterEditCell: function(targetInput, event) {
		if(!targetInput) targetInput = this.currentEdit;
		var newValue = targetInput.value;
		targetInput.setValue(newValue).hide();
		targetInput.previous("span").update(newValue.escapeHTML()||"--").show();
		if(newValue != this.valueBeforeEdit) {
			this._setModified(targetInput.up("tr"));
			WordsViewController.enableSaveButton();
		}
	},
	
	addLanguage: function(lang) {
		var langs = this._getLanguageListCurrentOrder().concat(lang);
		this.drawHeader(langs);
		this.$.select("tbody tr").each(function(tr){
			var cell;
			if (tr.classNames().toString().indexOf("row") == 0) {
				cell = TMPL.getCellForTableWords().interpolate({
					langTag: lang,	word: "", wordDisp: "--", 
					rowno: /row(\d+) ?.*/.exec(tr.classNames().toString())[1]
				});
			} else {
				cell = TMPL.getNewCellForTableWords().interpolate({
					langTag: lang,
					tmpKey: /.*?\[(\d+)].*/.exec(tr.down("input").name)[1]
				});
			}
			tr.down(".remove").insert({before: cell});
			this._setModified(tr);
		}.bind(this));
		
		WordsViewController.enableSaveButton();
	},
	
	removeLanguage: function(langTag) {
		this.$.select("thead th." + langTag + ", tbody td." + langTag).invoke("remove");
		this.drawHeader(WordsViewController.getLanguages());
		this.$.select("tr").each(this._setModified);
		WordsViewController.$.down("form").insert(new Element("input", {name: "removeLanguages[]", value: langTag, type: "hidden"}));
	},
	
	selectLanguage: function(langTag) {
		this.$.select("tbody td." + langTag).each(function(e){ e.addClassName("selected"); });		
	},
	
	unSelectLanguage: function() {
		this.$.select("tbody .selected").each(function(e){ e.removeClassName("selected"); });
	},
	
	_setModified: function(tr) {
		tr.addClassName("modified");
		WordsViewController.enableSaveButton();
	},
	
	_isModified: function() {
		return this.$.select("tr.modified").length > 0;
	}
});


Event.observe(window, 'load', function(){
	WordsViewController.$ = $("words-container");
	WordsViewController.initialize();
	WordsViewController.Table.$ = $("words-container").down("table");
});


Event.observe(window, 'beforeunload', function(event) {
	if(WordsViewController.Table._isModified()) {
		event.returnValue = Const.Message.Warning.discardChanges;
	}
});
