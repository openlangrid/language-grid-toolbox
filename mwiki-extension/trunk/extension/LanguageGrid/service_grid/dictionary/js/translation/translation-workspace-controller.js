
var TranslationWorkspaceController = Class.create({

	_MIN_FONT_SIZE: 10,
	_MIN_AREA_HEIGHT: 50,

	_workspace: null,

	_dFontSize: 1,
	_dAreaSize: 100,

	initialize: function(workspace) {
		this._workspace = workspace;
	},

	addAction: function(element, eventType, action) {
		element.observe(eventType, this[action].bind(this));
	},

	// -
	// Accessor
	
	areas: function() {
		return [this._workspace.sourceArea(), this._workspace.targetArea(), this._workspace.backArea()];
	},

	currentFontSize: function() {
		var areas = this.areas();
		return parseInt(areas[0].style.fontSize);
	},

	currentAreaHeight: function() {
		var areas = this.areas();
		return parseInt(areas[1].style.height);
	},

	// -
	// Control Methods

	resizeFont: function(df) {
		this.areas().each(function(area) {
			var size = (parseInt(area.style.fontSize) + df);
			area.style.fontSize = size + 'px';
		});
	},

	resizeArea: function(dh) {
		this.areas().each(function(area) {
			var height = (parseInt(area.style.height) + dh);
			area.style.height = height + 'px';
		});
	},

	// -
	// Action
	
	clear: function() {
		this._workspace.clear();
	},

	resizeFontSmall: function() {
		var fontSize = this.currentFontSize();

		if(fontSize < this._MIN_FONT_SIZE) {
			alert(Const.Message.ErrorNoMoreSmallFont);
			return;
		}

		this.resizeFont(-this._dFontSize);
	},

	resizeFontLarge: function() {
		this.resizeFont(this._dFontSize);
	},

	resizeAreaSmall: function() {
		var areaHeight = this.currentAreaHeight();

		if(areaHeight <= this._MIN_AREA_HEIGHT) {
			alert(Const.Message.ErrorNoMoreSmallTextarea);
			return;
		}

		this.resizeArea(-this._dAreaSize);
	},

	resizeAreaLarge: function() {
		this.resizeArea(this._dAreaSize);
	}
});
