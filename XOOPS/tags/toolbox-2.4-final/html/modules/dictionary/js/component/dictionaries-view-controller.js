var DictionariesViewController = {
	initialize: function() {
		this.$.down(".create button").observe("click", this.onNewDictionary.bind(this));
		this.$.down(".upload button").observe("click", this.onImportDictionary.bind(this));
	},
	
	onNewDictionary: function(e) {
		DialogViewController.CreateDictionary.open();
	},
	
	onImportDictionary: function(e) {
		DialogViewController.ImportDictionary.open();
	},
	
	hide: function() {
		this.$.hide();
		this.hideIndicator();
	},
	
	show: function() {
		this.$.show();
	},
	
	showIndicator: function(msg) {
		var html = Const.Images.loading + "&nbsp;";
		html += (msg) ? msg : Const.Message.Status.nowLoading;
		this.$.down(".indicator").update(html).setStyle({visibility: "visible"});
	},
	
	hideIndicator: function() {
		this.$.down(".indicator").setStyle({visibility: "hidden"});
	},
	
	loadAllDictionaries: function() {
		this.showIndicator();
		var typeId = this.$.down('input[name="typeId"]').getValue();
		Communication.loadAllDictionaries(typeId);
	},
	
	afterLoadAllDictionaries: function(contents) {
		if(contents.length == 0) {
			alert(Const.Message.Error.dictionaryNotFound);
			this.hideIndicator();
		} else {
			DictionariesViewController.Table.clear();
			DictionariesViewController.Table.appendRowsAll(contents.dictionaries);
			new Pager(contents.paginateInfo, "DictionariesViewController").paginate(this.Table.$.down("tfoot td"));
			this.hideIndicator();
		}
	},
	
	changePerPage: function(perPage) {
		this.perPage = perPage;
		this.showIndicator();
		var typeId = this.$.down('input[name="typeId"]').getValue();
		Communication.loadAllDictionaries(typeId, 1, perPage);
	},
	
	reload: function(pageNo) {
		this.showIndicator();
		var typeId = this.$.down('input[name="typeId"]').getValue();
		if(!pageNo) pageNo = parseInt(this.Table.getCurrentPage());
		Communication.loadAllDictionaries(typeId, pageNo, this.perPage);
	}
};


DictionariesViewController.Table = {
	TMPL_WSDL_LINK: '<a target="_blank" href="./services/wsdl/#{wsdl}?serviceId=#{serviceId}">WSDL</a>',
	
	IMAGE_OK: '<img src="./img/icon_check.png" />',
	
	appendRow: function(dictionary) {
		var datasource = this._dictionaryResponseToDatasource(dictionary);
		var row = "<tr id='#{dictId}' class='#{rowclass}'>"+TMPL.getColumnsForTableDictionaries()+"</tr>";
		this.$.down("tbody").insert(row.interpolate(datasource));
		this.showTable();
	},
	
	appendRowsAll: function(dictionaries) {
		dictionaries.each(function(dict){
			DictionariesViewController.Table.appendRow(dict);
		});
	},
	
	clear: function() {
		this.$.down("tbody").update("");
	},
	
	showTable: function() {
		if(!this.$.visible()) this.$.show();  
	},
	
	hideMenusAll: function() {
		this.$.select(".pop-menu-container").invoke("hide");
	},
	
	showMenu: function(button) {
		var menuContainer = button.up("td").down(".pop-menu-container");
		var menu = menuContainer.down("ul");
		if(!menu.innerHTML) {
			this._initPopMenu(menu, button.up("tr").id.sub(/dict-id-/, ""));
		}
		
		if(menuContainer.visible()) {
			this.hideMenusAll();	
		} else {
			this.hideMenusAll();	
			menuContainer.show();	
		}
	},
	
	load: function(dictId) {
		this.hideMenusAll();
		Communication.loadDictionary(dictId, 1, WordsViewController.perPage||10);
	},
	
	download: function(dictId) {
		this.hideMenusAll();
		location.href = './?page=download&dictionaryId=' + dictId;
	},
	
	deploy: function(dictId) {
		this.hideMenusAll();
		if (!confirm(Const.Message.Confirm.deployDictionary)) {
			return
		}
		DictionariesViewController.showIndicator();
		Communication.deployDictionary(dictId);
	},
	
	afterDeployed: function(dictId, status) {
		if(status) {
			var name = $("dict-id-" + dictId).down('input[name="resourceName"]').getValue().trim();
			$("dict-id-" + dictId).down(".wsdl").update(this._getServiceLink(name));
			$("dict-id-" + dictId).removeClassName("deployable").addClassName("undeployable");
		}
		DictionariesViewController.hideIndicator();
	},
	
	undeploy: function(dictId) {
		this.hideMenusAll();
		if (!confirm(Const.Message.Confirm.undeployDictionary)) {
			return
		}
		DictionariesViewController.showIndicator();
		Communication.undeployDictionary(dictId);
	},
	
	afterUndeployed: function(dictId, status) {
		if(status) {
			$("dict-id-" + dictId).down(".wsdl").update("-");
			$("dict-id-" + dictId).removeClassName("undeployable").addClassName("deployable");
		}
		DictionariesViewController.hideIndicator();
	},
	
	remove: function(dictId) {
		this.hideMenusAll();
		if (!confirm(Const.Message.Confirm.resourceRemove)) {
			return;
		}
		DictionariesViewController.showIndicator(Const.Message.Status.nowRemoving);
		Communication.removeDictionary(dictId);
	},
	
	afterRemoved: function(dictId, status) {
		if(status) {
			$("dict-id-" + dictId).remove();
			DictionariesViewController.reload();
		} else {
			alert(Const.Message.Error.failedToRemove);
		}
		DictionariesViewController.hideIndicator();
	},
	
	removeWsdlLink: function(dictId) {
		$("dict-id-" + dictId).down("wsdl").update("");
	},
	
	getPagenateInfo: function() {
		return Form.serialize(this.$.down("tfoot"),{hash: true});
	},
	
	getCurrentPage: function() {
		return this.$.select("tfoot .page-link span").pluck("innerHTML").detect(function(e){
			return e.match(/^\d+$/);
		});
	},
	
	_dictionaryResponseToDatasource: function(dict) {
		return {
			id: dict.id
			,dictId: "dict-id-" + dict.id
			,rowclass: this._getStyleClassForRow(dict)
			,resource_name: dict.name
			,languages: this._getSupportedLanguageAsJoinedString(dict)
			,read: this._getPermissionLabel(dict.view)
			,edit: this._getPermissionLabel(dict.edit)
			,service: this._getServiceLavel(dict)
			,creator: dict.userName
			,last_update: dict.updateDateFormat
			,entries: dict.count
			,menuLabel: "▼"
		}
	},
	
	_getStyleClassForRow: function(dict) {
		var classes = [];
		if(dict.view) classes.push("permitView");
		if(dict.edit && !dict.deployFlag) classes.push("deployable");
		if(dict.edit && dict.deployFlag) classes.push("undeployable");
		if(!!dict['delete']) classes.push("removable");
		return classes.join(" ");
	},
	
	_getSupportedLanguageAsJoinedString: function(dict) {
		return dict.languages.split(",").map(function(lang) {
			return Language.getNameByTag(lang);
		}).join(", ");
	},
	
	_getPermissionLabel: function(permission) {
		return permission ? this.IMAGE_OK : "";
	},
	
	_getServiceLavel: function(dict) {
		return !!dict.deployFlag && dict.view ?
			this._getServiceLink(dict.name) : "-";
	},
	
	_getServiceLink: function(dictName) {
		var typeId = DictionariesViewController.$.down('input[name="typeId"]').getValue();
		var wsdlValue = {
				0:'billingualdictionary_wsdl.php', 
				1:'paralleltext_wsdl.php', 
				5:'paraphrasedictionary_wsdl.php'
		}[typeId];
		if(!wsdlValue) wsdlValue = '';
		
		return this.TMPL_WSDL_LINK.interpolate({
			wsdl: wsdlValue, serviceId: encodeURI(dictName.replace(/ /g, '_')) 
		});
	},
	
	_initPopMenu: function(menu, dictId) {
		var tmplMenu = $("templates").down(".dictionaries .pop-menu").innerHTML.interpolate({id: dictId});
		menu.insert(tmplMenu);
	}
	
};


Event.observe(window, 'load', function() {
	DictionariesViewController.$ = $("dictionaries-container");
	DictionariesViewController.Table.$ = $("dictionaries-container").down("table");
	DictionariesViewController.initialize();
	DictionariesViewController.loadAllDictionaries();
});
