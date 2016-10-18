var AuthChildPreview = Class.create();
AuthChildPreview.prototype = {
	_authGroupIds : new Array(),
	_selectedIds : new Array(),
	initialize : function(authGroupIds,selectedIds){
		console.log(authGroupIds);
		console.log(selectedIds);
		this.setAuthGroupIds(authGroupIds);
		this.setSelectedIds(selectedIds);
	},
	
	
	auth_validate : function() {
		for(var i=0; i < this.getAuthGroupIds().length; i++){
			if(!this.getSelectedIds().include(this.getAuthGroupIds()[i])){
				return false;
			}
		} 
		return true;
	},
	getSelectedIds : function() {
		return this._selectedIds;
	},
	setSelectedIds : function(selectedIds) {
		this._selectedIds = selectedIds;
		return this;
	},
	getAuthGroupIds : function() {
		return this._authGroupIds;
	},
	setAuthGroupIds : function(authGroupIds) {
		this._authGroupIds = authGroupIds;
		return this;
	}
}