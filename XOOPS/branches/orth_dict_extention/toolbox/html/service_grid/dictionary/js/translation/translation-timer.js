
var TranslationTimer = Class.create({

	_timer: null,
	_time: 0,
	_area: null,

	initialize: function(area) {
		this._area = area;
		this.draw();
	},

	start: function() {
		this._timer = new PeriodicalExecuter(this.update.bind(this), 1);
		this._time = 0;
		this.draw();
	},

	update: function() {
		this._time++;
		this.draw();
	},

	stop: function() {
		this._timer.stop();
	},

	draw: function() {
		this._area.update(Const.Message.Second.replace('%d', this._time));
	}
});
