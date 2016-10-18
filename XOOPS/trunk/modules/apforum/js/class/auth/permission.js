
var Permission = Class.create();

Permission.prototype = {
	
	initialize : function(){
		this.initEventListener();
	},
	initEventListener : function() {
		$$('.bbs-preview-permission-checkbox').each(function(checkbox){
			Event.observe(checkbox,'click', this._allCheckFalse.bind(this));
		}.bind(this));
		Event.observe('bbs-preview-permission-all-checkbox', 'click', this._allCheckTrue.bind(this));
	},
	
	_allCheckTrue : function(event) {
		var check_box_array = document.getElementsByName("authGroup[]");
		var all_check_box = document.getElementById("bbs-preview-permission-all-checkbox");
		if(all_check_box.checked){
			for(count = 0; count < check_box_array.length; count++){
				check_box_array[count].checked = true;
			}
		}
	},
	
	_allCheckFalse : function(event) {
		var element = Event.element(event);
		var all_check_box = document.getElementById("bbs-preview-permission-all-checkbox");
		if(!element.checked){
			all_check_box.checked = false;
		}
	}
};