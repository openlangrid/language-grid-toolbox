

var AreaExpandCollapseController = Class.create({

	_controller: null,
	_area: null,
	_expand: null,
	_collapse: null,

	initialize: function(controller, area, expand, collapse) {
		this._controller = controller;
		this._area = area;
		this._expand = expand;
		this._collapse = collapse;

		this.initEventListeners();
	},

	initEventListeners: function() {
		this._controller.observe('click', this.toggle.bind(this));
	},

	toggle: function() {
		var action = (this._area.visible()) ? 'hide' : 'show';
		this[action]();
	},

	show: function() {
		this._expand.hide();
		this._collapse.show();
		this._area.show();
	},

	hide: function() {
		this._expand.show();
		this._collapse.hide();
		this._area.hide();
	}
});
