var Communication = {
	start: function() {
		DialogViewController.hide();
	},
	
	isError: function(response) {
		return response.status.toLowerCase() == 'error';
	},
	
	onError: function(response) {
		if(response.message == 'SESSIONTIMEOUT'){
			redirect2top();
		} else {
			alert(response.message);
		}
	},
	
	onFailure :function(){
		alert(Const.Message.Error.serverError);
	},
	
	_getTypeId: function() {
		if(Const.Mode == "Dictionary") return 0;
		if(Const.Mode == "ParallelText") return 1;
		
		return 0;
	},
	
	loadAllDictionaries: function(typeId, pageNo, perPage) {
		var params = {
			"typeId": typeId,
			"pageNo": pageNo || 1,
			"perPage": perPage || 10
		};
		
		new Ajax.Request('./?page=load', {
			method: "get",
			parameters: $H(params).toQueryString(),
			onSuccess	:function(result) {
				var response = result.responseText.evalJSON();
				
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				} else if (response.status == 'WARNING') {
					alert(response.message);
					return;
				}
				
				DictionariesViewController.afterLoadAllDictionaries(response.contents);
			},
			onFailure: Communication.onFailure,
			onExeption: Communication.onExeption
		});
	},
	
	
	loadDictionary: function(dictId, pageNo, perPage) {
		this.start();
		var params = {
			'id' : dictId, 
			'pageNo': pageNo || 1,
			'perPage': perPage || 10
		};
		
		DictionariesViewController.hide();
		WordsViewController.show();
		WordsViewController.showIndicator();
		
		new Ajax.Request('./?page=read', {
			method : 'post',
			postBody : $H(params).toQueryString(),
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				
				WordsViewController.afterLoadDictionary(dictId, response.contents);
			},
			onFailure: Communication.onFailure,
			onExeption: Communication.onExeption
		});
	},
	
	deployDictionary: function(dictId) {
		this.start();
		var params = { user_dictionary_id : dictId };
		new Ajax.Request('./?page=deploy', {
			postBody : $H(params).toQueryString(),
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				DictionariesViewController.Table.afterDeployed(dictId, true);
			},
			onFailure : function() {
				DictionariesViewController.Table.afterDeployed(dictId, false);
			}
		});
	},
	
	undeployDictionary: function(dictId) {
		this.start();
		var params = { user_dictionary_id : dictId };
		new Ajax.Request('./?page=undeploy', {
			postBody : $H(params).toQueryString(),
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				DictionariesViewController.Table.afterUndeployed(dictId, true);
			},
			onFailure : function() {
				DictionariesViewController.Table.afterUndeployed(dictId, false);
			}
		});
	},
	
	removeDictionary: function(dictId) {
		this.start();
		var params = { dictionaryId : dictId };
		new Ajax.Request('./?page=delete', {
			postBody : $H(params).toQueryString(),
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				DictionariesViewController.Table.afterRemoved(dictId, true);
			},
			onFailure : function() {
				DictionariesViewController.Table.afterRemoved(dictId, false);
			}
		});
	},
	
	createDictionary: function() {
		var params = DialogViewController.$.down("form").serialize({hash: true});
		params.deployFlag = false;

		new Ajax.Request('./?page=create', {
//			asynchronous : true,
			postBody : $H(params).toQueryString(),
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					DialogViewController.hideIndicator();
					return;
				}
				DialogViewController.CreateDictionary.afterCreate(response.contents);
			},
			onFailure: Communication.onFailure,
			onExeption: Communication.onExeption
		});
	},
	
	saveDictionaryUrl: './?page=update',
	saveDictionary: function(overwrite) {
		var params = WordsViewController.serializeParameters(true);
		params.overwrite = overwrite || 'false';
		
		var permission = WordsViewController.permission;
		if (permission.user.admin) {
			var addParams = DialogViewController.$.down(".save-dictionary form").serialize({hash: true});
			params = $H(params).merge(addParams).toObject()
		}
		
		params['valueToSave'] = [];
		
		var afterSaveParam = false;

		new Ajax.Request(this.saveDictionaryUrl, {
//			asynchronous: false,
			parameters	: $H(params).toQueryString(),
			onSuccess: function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}

				if (overwrite != "true" && response.status == 'WARNING') {
					if (!confirm(response.message)) {
						return;
					}
					
					setTimeout(function(){
						Communication.saveDictionary("true");
					}, 0);
				} else if (response.status != 'OK') {
					alert(response.message);
				} else {
					WordsViewController.setUpdateDate(response.contents.updateDate);
					afterSaveParam = true;
				}
			},
			onFailure: Communication.onFailure,
			onExeption: Communication.onExeption,
			onComplete: function(){ DialogViewController.SaveDictionary.afterSave(afterSaveParam); }
		});
	},
	
	loadTargetLanguages: function(delegate) {
		var params = { typeId: this._getTypeId() };
		new Ajax.Request('./?page=load-target-languages', {
			parameters: $H(params).toQueryString(),
			method: "get",
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				
				delegate.afterLoadTargetLanguages(response.contents);
			}
		});
	},
	
	search: function(pageNo, perPage) {
		var params = SearchViewController.$.down("form").serialize({hash: true});
		params.pageNo = pageNo || 1;
		params.perPage = perPage || 10;
		
		new Ajax.Request('./?page=search', {
			parameters : $H(params).toQueryString(),
			method: "get",
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				
				SearchViewController.afterSearch(response.contents, true);
			},
			onFailure: Communication.onFailure,
			onExeption: Communication.onExeption
		});
	},
	
	countSearch:function(params) {
		new Ajax.Request('./?page=count-search', {
			parameters : $H(params).toQueryString(),
			method: "get",
			onSuccess : function(result) {
				var response = result.responseText.evalJSON();
				if(Communication.isError(response)) {
					Communication.onError(response);
					return;
				}
				
				SearchViewController.afterCountSearch(response.contents, true);
			},
			onFailure: Communication.onFailure,
			onExeption: Communication.onExeption
		});
	}
};