
var AbstractSingletonPane = Class.create();
var UploadDictionary = Class.create();

AbstractSingletonPane.prototype = {
	initialize: function() {
		if($('singleton-pane')) {
			$('singleton-pane').remove();
		}
		this.paneID = 'singleton-pane';
		var pane = '<div class="popupbox" id="' + this.paneID + '"></div>'
		try {
			new Insertion.Bottom($$('body')[0], pane);
			this.hidePane();
		} catch(e) {
			;
		}
	},

	showPane : function(x, y) {

		if (this.notShowPane()) {
			return false;
		}

		$(this.paneID).setStyle({
			position	: 'absolute' ,
			left		: x + 'px' ,
			top			: y + 'px'
		});

		$(this.paneID).innerHTML = this.getPane();
		$(this.paneID).show();
		this.onShowPane();
	},

	setZindex: function(zIndex) {
		$(this.paneID).style.zIndex = zIndex;
	},

	setStatus : function(message, index) {
		var index = index || 0;
		$('singleton-pane-status-' + index).innerHTML = message;
		if (!message) {
			$('singleton-pane-status-' + index).hide();
		} else {
			$('singleton-pane-status-' + index).show();
		}
	},

	notShowPane : function() {
		return false;
	},

	onShowPane : function() {
		return;
	},

	getPane : function() {
		return;
	},

	submit : function() {
		return;
	},

	hidePane: function(){
		$(this.paneID).hide();
	}
};

Object.extend(UploadDictionary.prototype, AbstractSingletonPane.prototype);
Object.extend(UploadDictionary.prototype, {
	getPane: function() {
		var html = new Array();
		html.push('<div id="" class="subwindow-border">');
		html.push('<form ');
		html.push(' target="dummyframe" ');
		html.push('id="dict-upload-form" enctype="multipart/form-data" ');
		html.push('action="' + Const.URL.UpLoad + '" method="post">');
		html.push('<div class="info-body">');


		html.push('<div class="inner">')
		html.push('<input size="75" type="file" id="dictfile" name="dictfile">');
		html.push('<input type="hidden" name="title_db_key" value="' + Const.Wiki.TitleDBKey + '">');
		html.push('</div>');

		// Buttons
		html.push('<div class="inner">');
		html.push('<input type="submit" value="' + Const.Message.Import + '" id="" name="Import"/>');
		html.push('<span style="margin-left: 20px;"><a href="javascript:void(0);" onclick="uploadDictionary.hidePane();">' + Const.Message.Cancel + '</a></span>');
		html.push('</div>');
		html.push('</form>');
		html.push('<iframe id="dummyframe" name="dummyframe" style="display: none;"></iframe>');
		html.push('</div>');
		return html.join('');
	}
});
