
var SingletonDialog = Class.create({

	ID: 'singleton-dialog',

	MAIN_ID: 'singleton-dialog-main',
	MASK_ID: 'singleton-dialog-mask',
	
	TITLE_ID: 'singleton-dialog-title',
	BODY_ID: 'singleton-dialog-body',
	
	body: null,
	delegate: null,
	
	OPACITY : 0.6,

	STATIC: {
		init: false
	},
	
	initialize : function() {
		this.setup();
		this.initEventListeners();
		this.STATIC.init = true;
	},

	setup: function() {
		if (this.STATIC.init) return;

		var pane = document.createElement('div');
		pane.id = this.ID;

		var main = document.createElement('div');
		main.id = this.MAIN_ID;

		var title = document.createElement('h2');
		title.id = this.TITLE_ID;

		var body = document.createElement('div');
		body.id = this.BODY_ID;

		main.appendChild(title);
		main.appendChild(body);

		var mask = document.createElement('div');
		mask.id = this.MASK_ID;

		pane.appendChild(main);
		pane.appendChild(mask);

		new Insertion.Bottom($$('body')[0], pane);
	},

	initEventListeners : function() {
		if (!this.STATIC.init) {
			Event.observe(window, 'scroll', this.onScrollWindowEvent.bind(this));
			Event.observe(window, 'resize', this.onResizeWindowEvent.bind(this));
		}
	},

	show : function(title, body, width) {
		this.setupMask();
		this.update(title, body);
		$(this.ID).show();
		this.setupPanel(width);
	},

	hide: function() {
		this.update('');
		$(this.ID).hide();
	},
	
	update: function(title, body) {
		$(this.TITLE_ID).update(title);
		$(this.BODY_ID).update(body);
	},
	
	setupMask : function() {
		$(this.MASK_ID).setStyle({
			filter : 'alpha(opacity=' + (this.OPACITY * 10) + ')'
			, background: '#000'
			, position: 'absolute'
			, zIndex: 5
			, MozOpacity : this.OPACITY / 10
			, opacity : this.OPACITY
			, top : this.getWindowScrollOffsets().top + 'px'
			, left: this.getWindowScrollOffsets().left + 'px'
			, width: this.getWindowDimensions().width + 'px'
			, height: this.getWindowDimensions().height + 'px'
			//, top : '0px'
			//, left: '0px'
			//, width: this.getPageSize()[0] + 'px'
			//, height: this.getPageSize()[1] + 'px'
		});
	},

	setupPanel : function(width) {
		$(this.MAIN_ID).setStyle({
			width : width + 'px'
			, position: 'absolute'
			, zIndex: 10
			, background: '#f5f5f5'
			, padding: '0 5px 5px 5px'
			, border: '1px solid #aaa'
		});

		var vp = this.getWindowDimensions();
		var vp_sc = this.getWindowScrollOffsets();
		var left = ((vp.width - $(this.MAIN_ID).offsetWidth) / 2) + vp_sc.left;
		var top = ((vp.height - $(this.MAIN_ID).offsetHeight) / 2) + vp_sc.top;

		if((top + $(this.MAIN_ID).offsetHeight - vp_sc.top) > vp.height){
			top = vp.height - $(this.MAIN_ID).offsetHeight + vp_sc.top;
		}

		if((left + $(this.MAIN_ID).offsetWidth - vp_sc.left) > vp.width){
			left = vp.width - $(this.MAIN_ID).offsetWidth + vp_sc.left;
		}

		$(this.MAIN_ID).setStyle({
			left: left + 'px'
			, top: top + 'px'
		});
	},

	onScrollWindowEvent : function(event) {
		$(this.MASK_ID).setStyle({
			top : this.getWindowScrollOffsets().top+'px'
			, left : this.getWindowScrollOffsets().left+'px'
		});
	},

	onResizeWindowEvent : function(event) {
		$(this.MASK_ID).setStyle({
			width : this.getWindowDimensions().width + 'px'
			, height : this.getWindowDimensions().height + 'px'
		});
	},

	getWindowDimensions : function() {
		return document.viewport.getDimensions();
	},

	getWindowScrollOffsets : function() {
		return document.viewport.getScrollOffsets();
	},

    getPageSize: function() {
		var xScroll, yScroll;
		
		if (window.innerHeight && window.scrollMaxY) {	
			xScroll = window.innerWidth + window.scrollMaxX;
			yScroll = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight){ // all but Explorer Mac
			xScroll = document.body.scrollWidth;
			yScroll = document.body.scrollHeight;
		} else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
			xScroll = document.body.offsetWidth;
			yScroll = document.body.offsetHeight;
		}
		
		var windowWidth, windowHeight;
		
		if (self.innerHeight) {	// all except Explorer
			if(document.documentElement.clientWidth){
				windowWidth = document.documentElement.clientWidth; 
			} else {
				windowWidth = self.innerWidth;
			}
			windowHeight = self.innerHeight;
		} else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
			windowWidth = document.documentElement.clientWidth;
			windowHeight = document.documentElement.clientHeight;
		} else if (document.body) { // other Explorers
			windowWidth = document.body.clientWidth;
			windowHeight = document.body.clientHeight;
		}	
		
		// for small pages with total height less then height of the viewport
		if(yScroll < windowHeight){
			pageHeight = windowHeight;
		} else { 
			pageHeight = yScroll;
		}
	
		// for small pages with total width less then width of the viewport
		if(xScroll < windowWidth){	
			pageWidth = xScroll;		
		} else {
			pageWidth = windowWidth;
		}

		return [pageWidth,pageHeight];
	}
});

SingletonDialog.instance = function() {
	var instance = new SingletonDialog();

	return (function() {
		return instance;
	})();
};
