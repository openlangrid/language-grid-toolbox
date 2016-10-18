
var InputInspector = Class.create({
	initialize: function(tableElemId, dataWrapper){
		this.tableElemId = tableElemId;
		this.dataWrapper = dataWrapper;
		this.oldValue = '';

		var input = document.createElement( 'input' );
		input.type = 'text';
		Element.setStyle(input, {width: "95%"});
		Event.observe( input, 'blur', this.onblur.bindAsEventListener( this ) );
		Event.observe( input, 'keypress', this.onkeypress.bindAsEventListener( this ) );
		this.input = input;
	},

	show : function(element, row, col) {
		this.element = element;
		var value = element.innerHTML;
		this.oldValue = value;
		this.row = row;
		this.col = col;
		this.input.value = value;
		while ( element.firstChild ) element.removeChild( element.firstChild );
		element.appendChild(this.input);
		Field.activate(this.input);
	},

	hide: function(){
		var td = this.input.parentNode;
		this.input.parentNode.removeChild( this.input );
		if(td.childNodes.length == 0)
			td.appendChild( document.createTextNode( this.input.value ) );
	},

	cancel: function() {
		this.input.value = this.oldValue;
		this.hide();
	},

	submit: function(){
		this.hide();
		if ( this.input.value != this.oldValue ) {
			var value = this.input.value;

			this.dataWrapper.editText(this.row, this.col, value);
			dictionaryEditState.showEdit();

			Element.addClassName(this.element, 'eidt');
		}
	},

	onblur : function(event) {
		this.submit();
	},

	onkeypress : function(event) {
		switch ( event.keyCode ) {
		case Event.KEY_RETURN:
			this.submit();
			break;
		case Event.KEY_ESC:
			this.cancel();
			break;
		}
	}
});
