var DialogViewController = {
	initialize: function() {
		Event.observe(window, 'scroll', this.onScrollWindow.bind(this));
		Event.observe(window, 'resize', this.onResizeWindow.bind(this));
		this.onResizeWindow();
	},
	
	positionContents: function() {
		var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
		var offset = (scrollY < 100) ? 80 : scrollY + 40;
		this.$.down(".contents-frame").setStyle({top: offset + "px"});
	},
	
	show: function(contents) {
		DictionariesViewController.Table.hideMenusAll();
		this.$.show();
		this.positionContents();
		if(Object.isElement(contents)) {
			var tmp = new Element("div");
			contents.wrap(tmp);
			this.$.down(".contents-container").update(tmp.innerHTML);	
		} else if(Object.isString(contents)) {
			this.$.down(".contents-container").update(contents);	
		}
	},
	
	hide: function(){
		this.$.hide();
	},

	onScrollWindow : function(event) {
		this.$.down(".filter").setStyle({
			top : this.getWindowScrollOffsets().top+'px'
			,left : this.getWindowScrollOffsets().left+'px'
		});
	},

	onResizeWindow : function(event) {
		this.$.down(".filter").setStyle({
			width : this.getWindowDimensions().width + 'px',
			height : this.getWindowDimensions().height + 'px'
		});
	},

	getWindowDimensions : function() {
		return document.viewport.getDimensions();
	},

	getWindowScrollOffsets : function() {
		return document.viewport.getScrollOffsets();
	},
	
	_getTypeId: function(dict) {
		if(Const.Mode == "Dictionary") return 0;
		if(Const.Mode == "ParallelText") return 1;
		
		return 0;
	},
	
	showIndicator: function() {
		var indicator = this.$.down(".indicator");
		if(indicator) {
			indicator.setStyle({visibility: "visible"}); 
		}
	},
	
	hideIndicator: function() {
		var indicator = this.$.down(".indicator");
		if(indicator) {
			indicator.setStyle({visibility: "hidden"}); 
		}
	}
};

DialogViewController.CreateDictionary = {
	open: function() {
		DialogViewController.show($("templates").down(".create-language-resource"));
	},
	
	create: function() {
		var form = DialogViewController.$.down("form").serialize({hash: true});
		
		if (!form["supportedLanguages[]"] || 
				!Object.isArray(form["supportedLanguages[]"]) || 
				form["supportedLanguages[]"].length < 2) {
			alert(Const.Message.Error.selectAtLeastTwoLanguages);
			return;
		}
		
		if (form.dictionaryName == '') {
			alert(Const.Message.Error.dictionaryNameEmpty);
			return;
		}

		if(!Dictionary.isValidName(form.dictionaryName)) {
			alert(Const.Message.Error.invalidDictionaryName);
			return;
		}
		
		DialogViewController.showIndicator();
		Communication.createDictionary();
	},
	
	onChangeEditPermission: function(select) {
		var editPermission = $(select).value;
		var selectElement = DialogViewController.$.down('select[name="viewPermission"]');
		selectElement.removeClassName("edit-user").removeClassName("edit-all");
		selectElement.addClassName("edit-" + editPermission);
	},
	
	afterCreate: function(contents) {
		DialogViewController.hideIndicator();
		DialogViewController.hide();
		Communication.loadDictionary(contents.dictionaryId);
	}
};


DialogViewController.ImportDictionary = {
	open: function() {
		DialogViewController.show($("templates").down(".import-dictionary"));
	},
	
	submit: function() {
		var params = DialogViewController.$.down("form").serialize({hash: true});
		var file = DialogViewController.$.down("form").dictfile;
		if (!file.value) {
			alert(Const.Message.Error.fileRequired, 1);
			return;
		}

		if (!params.dictionary_name ) {
			alert(Const.Message.Error.dictionaryNameEmpty);
			return;
		}

		if(!Dictionary.isValidName(params.dictionary_name)) {
			alert(Const.Message.Error.invalidDictionaryName);
			return;
		}
		
		DialogViewController.showIndicator();
		DialogViewController.$.down("form").submit();		
	},
	
	onChangeEditPermission: function(select) {
		var editPermission = $(select).value;
		var selectElement = DialogViewController.$.down('select[name="view_permission"]');
		selectElement.removeClassName("edit-user").removeClassName("edit-all");
		selectElement.addClassName("edit-" + editPermission);
	},
	
	afterImport: function(dictId) {
		DialogViewController.hideIndicator();
		DialogViewController.hide();
		Communication.loadDictionary(dictId);
	}
};

DialogViewController.SaveDictionary = {
	open: function(dictionaryName) {
		$("templates").down(".save-dictionary .dictionary-name").update(dictionaryName || "");
		DialogViewController.show($("templates").down(".save-dictionary"));
		
		if(WordsViewController.permission.user.admin) {
			DialogViewController.$.down('select[name="editPermission"]')
				.down('option[value="'+ WordsViewController.permission.dictionary.edit + '"]').selected = true;
			DialogViewController.$.down('select[name="viewPermission"]')
				.down('option[value="' + WordsViewController.permission.dictionary.view + '"]').selected = true;
			this.onChangeEditPermission(DialogViewController.$.down('select[name="editPermission"]'));
		} else {
			DialogViewController.$.down(".permission").remove();
		}		
	},
	
	save: function() {
		DialogViewController.showIndicator();
		Communication.saveDictionary();
	},
	
	onChangeEditPermission: function(select) {
		var editPermission = $(select).value;
		var selectElement = DialogViewController.$.down('select[name="viewPermission"]');
		selectElement.removeClassName("edit-user").removeClassName("edit-all");
		selectElement.addClassName("edit-" + editPermission);
		if(editPermission == "all") selectElement.setValue("all");
	},
	
	afterSave: function(isSuccess) {
		DialogViewController.hideIndicator();
		DialogViewController.hide();
		if(isSuccess) {
			WordsViewController.showSaveFinishedMessage();
			WordsViewController.$.select('input[name^="removeLanguage"]').invoke("remove");
			WordsViewController.$.select('.modified').each(function(e){
				e.removeClassName('modified');
			});
			WordsViewController.reload();
		}
	}
};

DialogViewController.AddLanguage = {
	open: function() {
		DialogViewController.show($("templates").down(".add-language"));
		var langs = WordsViewController.getLanguages();
		
		DialogViewController.$.select("ul input").each(function(input){
			if(langs.include(input.value)) {
				input.setValue(true);
				input.writeAttribute("readonly");
				input.writeAttribute("disabled");
			}
		})
	},
	
	add: function() {
		var params = Form.serialize(DialogViewController.$.down("ul"),{hash: true});
		WordsViewController.addLanugages(params["supportedLanguages[]"]);
		DialogViewController.hide();
	}
};


Event.observe(window, 'load', function(){
	DialogViewController.$ = $("light-popup-panel-wrapper");
	DialogViewController.initialize();
	
	setTimeout(function(){

	}, 2000);
});